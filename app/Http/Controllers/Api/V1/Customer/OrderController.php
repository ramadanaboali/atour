<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Customer\BookingTripRequest;
use App\Http\Requests\Customer\BookingEffectivenesRequest;
use App\Http\Requests\Customer\BookingGiftRequest;
use App\Http\Resources\OrderCustomerResource;
use App\Http\Resources\OrderResource;
use App\Mail\OrderDetailsMail;
use App\Models\BookingEffectivene;
use App\Models\BookingGift;
use App\Models\BookingTrip;
use App\Models\Effectivenes;
use App\Models\Gift;
use App\Models\Order;
use App\Models\Trip;
use App\Models\OrderFee;
use App\Services\OneSignalService;
use App\Services\TapService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use function response;

class OrderController extends Controller
{
    public function index(PaginateRequest $request)
    {
        $data = Order::where('user_id', auth()->user()->id)->get();
        $data = OrderCustomerResource::collection($data);
        return response()->apiSuccess($data);

    }

    public function show($id)
    {
        $item = Order::findOrFail($id);
        $data = new OrderCustomerResource($item);
        return response()->apiSuccess($data);
    }




    public function getNextBookingDate($bookingDay)
    {
        // Normalize input
        $bookingDay = strtolower($bookingDay);

        // Get today
        $today = Carbon::today();

        // Map days to Carbon constants
        $daysMap = [
            'sunday'    => Carbon::SUNDAY,
            'monday'    => Carbon::MONDAY,
            'tuesday'   => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY,
            'thursday'  => Carbon::THURSDAY,
            'friday'    => Carbon::FRIDAY,
            'saturday'  => Carbon::SATURDAY,
        ];

        if (!array_key_exists($bookingDay, $daysMap)) {
            throw new Exception("Invalid booking day: $bookingDay");
        }

        // Get the next occurrence of that day
        $bookingDate = $today->next($daysMap[$bookingDay]);

        return $bookingDate->toDateString(); // returns in Y-m-d format
    }

    public function bookingTrip(BookingTripRequest $request)
    {
        Log::info(json_encode($request->all()));
        $item = Trip::findOrFail($request->trip_id);
        if ($item->vendor?->active == 0) {
            return response()->apiFail(__('api.vendor_not_active'));
        }
        $booking_date = $this->getNextBookingDate($request->booking_day);
        Log::info($booking_date);
        $booking_count = BookingTrip::where('trip_id', $request->trip_id)->whereNotIn('status', [Order::STATUS_REJECTED, Order::STATUS_CANCELED])->where('booking_date', $request->booking_date)->selectRaw('SUM(people_number + children_number) as total')->first()->total;
        if (((int)$booking_count + (int)$request->children_number + (int)$request->people_number) > (int)$item->people) {
            return response()->apiFail(__('api.trip_compleated_cant_compleate_reservation'));
        }
        $data = $request->all();
        $quantity = ((int) $request->children_number + (int) $request->people_number) ?? 1;
        $data['user_id'] = auth()->user()->id;
        $data['payment_status'] = 'pendding';
        $data['status'] = 0;
        $data['booking_date'] = $request->booking_date??$booking_date;
        $data['payment_way'] = $request->payment_way;

        $data['total'] = $item->price * $quantity;
        $data['lat'] = $request->lat;
        $data['long'] = $request->long;
        $data['vendor_id'] = $item->vendor_id;
        $admin_value = 0;
        $order_fees = [];
        if ($item->vendor?->feeSetting) {
            if ($item->vendor?->feeSetting?->tax_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->tax_value;
                $order_fees['tax_value'] = $item->vendor?->feeSetting?->tax_value;
            } else {
                $admin_value += ($item->vendor?->feeSetting?->tax_value * $item->price) / 100;
                $order_fees['tax_value'] = ($item->vendor?->feeSetting?->tax_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->payment_way_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->payment_way_value;
                $order_fees['payment_way_value'] = $item->vendor?->feeSetting?->payment_way_value;

            } else {
                $admin_value += ($item->vendor?->feeSetting?->payment_way_value * $item->price) / 100;
                $order_fees['payment_way_value'] = ($item->vendor?->feeSetting?->payment_way_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->admin_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->admin_value;
                $order_fees['admin_value'] = $item->vendor?->feeSetting?->admin_value;
            } else {
                $admin_value += ($item->vendor?->feeSetting?->admin_value * $item->price) / 100;
                $order_fees['admin_value'] = ($item->vendor?->feeSetting?->admin_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->admin_fee_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->admin_fee_value;
                $order_fees['admin_fee_value'] = $item->vendor?->feeSetting?->admin_fee_value;
            } else {
                $admin_value += ($item->vendor?->feeSetting?->admin_fee_value * $item->price) / 100;
                $order_fees['admin_fee_value'] = ($item->vendor?->feeSetting?->admin_fee_value * $item->price) / 100;
            }
        }

        $data['customer_total'] = ($item->price + $item->calculateAdminFees()) * $quantity;
        $order = BookingTrip::create($data);
        if (count($order_fees) > 0) {
            $order_fees['order_id'] = $order->id;
            $order_fees['trip_id'] = $item->id;
            $order_fees['vendor_id'] = $item->vendor_id;
            $order_fees['order_type'] = 'trip';
            OrderFee::create($order_fees);
        }

        if ($request->payment_way == 'online') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'trip']);
            $tap = $payment->pay($order->customer_total);
            if ($tap['success']) {
                $order->payment_id = $tap['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($tap);
        } else {
            $order->payment_status = 'CAPTURED';
            $order->save();
        }
        $order = new OrderCustomerResource($order);

        try {
            Mail::to($order->user?->email)->send(new OrderDetailsMail($order));

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            OneSignalService::sendToUser($item->vendor_id, __('api.new_order'), __('api.new_trip_booking_code', ['item_name' => $item->title]));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return response()->apiSuccess($order);
    }
    public function tripPay($id)
    {
        $order = BookingTrip::findOrFail($id);
        if ($order->payment_status != 'CAPTURED') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'trip']);
            $result = $payment->pay($order->customer_total);
            if ($result['success']) {
                $order->payment_id = $result['data']['id'];
                $order->save();
            }

            return response()->apiSuccess($result);
        } else {

            return response()->apiFail(__('api.order_paid_befor'));
        }
    }
    public function bookingEffectivenes(BookingEffectivenesRequest $request)
    {
        $item = Effectivenes::findOrFail($request->effectivene_id);

        if ($item->vendor?->active == 0) {
            return response()->apiFail(__('api.vendor_not_active'));
        }

        if ($item->people) {
            $booking_count = BookingEffectivene::where('effectivene_id', $request->effectivene_id)->whereNotIn('status', [Order::STATUS_REJECTED, Order::STATUS_CANCELED])->count();
            if (($booking_count + 1) > $item->people) {
                return response()->apiFail(__('api.trip_compleated_cant_compleate_reservation'));
            }
        }



        $data['user_id'] = auth()->user()->id;
        $data['payment_status'] = 'pendding';
        $data['status'] = 0;
        $data['payment_way'] = $request->payment_way;
        $data['total'] = $item->price;
        $data['vendor_id'] = $item->vendor_id;
        $data['effectivene_id'] = $request->effectivene_id;
        $data['customer_total'] = ($item->price + $item->calculateAdminFees());

        $admin_value = 0;
        $order_fees = [];
        if ($item->vendor?->feeSetting) {
            if ($item->vendor?->feeSetting?->tax_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->tax_value;
                $order_fees['tax_value'] = $item->vendor?->feeSetting?->tax_value;
            } else {
                $admin_value += ($item->vendor?->feeSetting?->tax_value * $item->price) / 100;
                $order_fees['tax_value'] = ($item->vendor?->feeSetting?->tax_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->payment_way_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->payment_way_value;
                $order_fees['payment_way_value'] = $item->vendor?->feeSetting?->payment_way_value;

            } else {
                $admin_value += ($item->vendor?->feeSetting?->payment_way_value * $item->price) / 100;
                $order_fees['payment_way_value'] = ($item->vendor?->feeSetting?->payment_way_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->admin_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->admin_value;
                $order_fees['admin_value'] = $item->vendor?->feeSetting?->admin_value;
            } else {
                $admin_value += ($item->vendor?->feeSetting?->admin_value * $item->price) / 100;
                $order_fees['admin_value'] = ($item->vendor?->feeSetting?->admin_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->admin_fee_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->admin_fee_value;
                $order_fees['admin_fee_value'] = $item->vendor?->feeSetting?->admin_fee_value;

            } else {
                $admin_value += ($item->vendor?->feeSetting?->admin_fee_value * $item->price) / 100;
                $order_fees['admin_fee_value'] = ($item->vendor?->feeSetting?->admin_fee_value * $item->price) / 100;
            }
        }

        $order = BookingEffectivene::create($data);
        if (count($order_fees) > 0) {
            $order_fees['order_id'] = $order->id;

            $order_fees['effectivenes_id'] = $item->id;
            $order_fees['vendor_id'] = $item->vendor_id;

            $order_fees['order_type'] = 'effectivenes';
            OrderFee::create($order_fees);
        }

        if ($request->payment_way == 'online') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'effectivenes']);
            $tap = $payment->pay($order->customer_total);
            if ($tap['success']) {
                $order->payment_id = $tap['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($tap);
        }


        try {
            Mail::to($order->user?->email)->send(new OrderDetailsMail($order));

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            OneSignalService::sendToUser($item->vendor_id, __('api.new_order'), __('api.new_effectivnes_booking_code', ['item_name' => $item->title]));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return response()->apiSuccess($order);
    }

    public function effectivenePay($id)
    {
        $order = BookingEffectivene::findOrFail($id);
        if ($order->payment_status != 'CAPTURED') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'effectivenes']);
            $result = $payment->pay($order->customer_total);
            if ($result['success']) {
                $order->payment_id = $result['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($result);
        } else {

            return response()->apiFail(__('api.order_paid_befor'));
        }
    }
    public function bookingGifts(BookingGiftRequest $request)
    {


        $item = Gift::findOrFail($request->gift_id);

        if ($item->vendor?->active == 0) {
            return response()->apiFail(__('api.vendor_not_active'));
        }

        $data['customer_total'] = ($item->price + $item->calculateAdminFees()) * ($request->quantity ?? 1);
        $data['user_id'] = auth()->user()->id;
        $data['payment_status'] = 'pendding';
        $data['status'] = 0;
        $data['total'] = $item->price * ($request->quantity ?? 1);
        $data['vendor_id'] = $item->vendor_id;
        $data['gift_id'] = $request->gift_id;
        $data['lat'] = $request->lat;
        $data['long'] = $request->long;
        $data['payment_way'] = $request->payment_way;

        $data['delivery_address'] = $request->delivery_address;
        $data['delivery_number'] = $request->delivery_number;
        $data['delivery_way'] = $request->delivery_way;
        $data['quantity'] = $request->quantity;
        $admin_value = 0;
        $order_fees = [];
        if ($item->vendor?->feeSetting) {
            if ($item->vendor?->feeSetting?->tax_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->tax_value;
                $order_fees['tax_value'] = ($request->quantity ?? 1) * $item->vendor?->feeSetting?->tax_value;
            } else {
                $admin_value += ($item->vendor?->feeSetting?->tax_value * $item->price) / 100;
                $order_fees['tax_value'] = ($request->quantity ?? 1) * ($item->vendor?->feeSetting?->tax_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->payment_way_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->payment_way_value;
                $order_fees['payment_way_value'] = ($request->quantity ?? 1) * $item->vendor?->feeSetting?->payment_way_value;

            } else {
                $admin_value += ($item->vendor?->feeSetting?->payment_way_value * $item->price) / 100;
                $order_fees['payment_way_value'] = ($request->quantity ?? 1) * ($item->vendor?->feeSetting?->payment_way_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->admin_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->admin_value;
                $order_fees['admin_value'] = ($request->quantity ?? 1) * $item->vendor?->feeSetting?->admin_value;
            } else {
                $admin_value += ($item->vendor?->feeSetting?->admin_value * $item->price) / 100;
                $order_fees['admin_value'] = ($request->quantity ?? 1) * ($item->vendor?->feeSetting?->admin_value * $item->price) / 100;
            }
            if ($item->vendor?->feeSetting?->admin_fee_type == 'const') {
                $admin_value += $item->vendor?->feeSetting?->admin_fee_value;
                $order_fees['admin_fee_value'] = ($request->quantity ?? 1) * $item->vendor?->feeSetting?->admin_fee_value;

            } else {
                $admin_value += ($item->vendor?->feeSetting?->admin_fee_value * $item->price) / 100;
                $order_fees['admin_fee_value'] = ($request->quantity ?? 1) * ($item->vendor?->feeSetting?->admin_fee_value * $item->price) / 100;
            }
            $admin_value = $admin_value * ($request->quantity ?? 1);
        }

        $order = BookingGift::create($data);
        if (count($order_fees) > 0) {
            $order_fees['vendor_id'] = $item->vendor_id;
            $order_fees['order_id'] = $order->id;
            $order_fees['order_type'] = 'gift';
            $order_fees['gift_id'] = $item->id;

            OrderFee::create($order_fees);
        }
        if ($request->payment_way == 'online') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'gift']);
            $tap = $payment->pay($order->customer_total);
            if ($tap['success']) {
                $order->payment_id = $tap['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($tap);
        }


        try {
            Mail::to($order->user?->email)->send(new OrderDetailsMail($order));

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        try {
            OneSignalService::sendToUser($item->vendor_id, __('api.new_order'), __('api.new_gift_booking_code', ['item_name' => $item->title]));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return response()->apiSuccess($order);
    }

    public function GiftPay($id)
    {
        $order = BookingGift::findOrFail($id);
        if ($order->payment_status != 'CAPTURED') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'gift']);
            $result = $payment->pay($order->customer_total);
            if ($result['success']) {
                $order->payment_id = $result['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($result);
        } else {

            return response()->apiFail(__('api.order_paid_befor'));
        }
    }
    public function bookings(Request $request)
    {

        $data['curren']['gifts'] = BookingGift::with(['gift' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->where('status', Order::STATUS_PENDING)->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        $data['curren']['effectivenes'] = BookingEffectivene::with(['effectivene' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->where('status', Order::STATUS_PENDING)->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        $data['curren']['trips'] = BookingTrip::with(['trip' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->where('status', Order::STATUS_PENDING)->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        $data['ended']['gifts'] = BookingGift::with(['gift' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->whereIn('status', [Order::STATUS_REJECTED, Order::STATUS_CANCELED])->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        $data['ended']['effectivenes'] = BookingEffectivene::with(['effectivene' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->whereIn('status', [Order::STATUS_REJECTED, Order::STATUS_CANCELED])->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        $data['ended']['trips'] = BookingTrip::with(['trip' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->whereIn('status', [Order::STATUS_REJECTED, Order::STATUS_CANCELED])->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        $data['compleated']['gifts'] = BookingGift::with(['gift' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->where('status', Order::STATUS_COMPLEALED)->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        $data['compleated']['effectivenes'] = BookingEffectivene::with(['effectivene' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->where('status', Order::STATUS_COMPLEALED)->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        $data['compleated']['trips'] = BookingTrip::with(['trip' => function ($query) {
            $query->withTrashed()->with('city');
        },'vendor' => function ($query) {
            $query->withTrashed();
        }])->where('status', Order::STATUS_COMPLEALED)->where('user_id', auth()->user()->id)->get()->each(function ($item) {
            $item->total = (string)$item->customer_total;
        });

        return response()->apiSuccess($data);


    }

    public function callBack(Request $request, $type)
    {
        $payment = new TapService();
        $result = $payment->callBack($request->tap_id, $type);
        $order = null;
        $message = "";
        if ($type == 'gift') {
            $order = BookingGift::with('gift')->where('payment_id', $result['data']['id'])->first();
            $message = __('api.new_gift_booking_code', ['item_name' => $order->gift?->title]);
        } elseif ($type == 'gift') {
            $order = BookingTrip::with('trip')->where('payment_id', $result['data']['id'])->first();
            $message = __('api.new_trip_booking_code', ['item_name' => $order->trip?->title]);
        } elseif ($type == 'effectivenes') {
            $order = BookingEffectivene::with('effectivene')->where('payment_id', $result['data']['id'])->first();
            $message = __('api.new_effectivnes_booking_code', ['item_name' => $order->effectivene?->title]);
        }
        if (!$order) {
            return response()->apiFail(__('api.order_not_found_contact_us'));
        }
        if ($result['success']) {
            // return "success";
            
try {
    Mail::to($order->user?->email)->send(new OrderDetailsMail($order));

} catch (Exception $e) {
    Log::error($e->getMessage());
}

            try {
                OneSignalService::sendToUser($order->vendor_id, __('api.new_order'), $message);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }

            return response()->apiSuccess($result['data']);
        }
        // return "error";
        return response()->apiFail($result['message']);

    }
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        $data = ['status' => Order::STATUS_CANCELED];
        return response()->apiSuccess($order->update($data, $order));
    }
    public function delete($id)
    {

        $order = Order::findOrFail($id);
        return response()->apiSuccess($order->delete());
    }

    public function cancelOrder($type, $id)
    {
        Log::info('sss');
        Log::info($type);
        Log::info($id);
        Log::info('sss');
        if ($type == 'gift') {
            $order = BookingGift::findOrFail($id);
        } elseif ($type == 'effectivene') {
            $order = BookingEffectivene::findOrFail($id);
        } else {
            $order = BookingTrip::findOrFail($id);
        }

        $order->cancel_date = date('Y-m-d H:-i:s');

        $order->status = Order::STATUS_CANCELED;
        $order->save();
        return response()->apiSuccess($order);
    }
}
