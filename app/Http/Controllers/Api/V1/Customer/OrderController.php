<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Customer\BookingTripRequest;
use App\Http\Requests\Customer\BookingEffectivenesRequest;
use App\Http\Requests\Customer\BookingGiftRequest;
use App\Http\Resources\OrderResource;
use App\Models\BookingEffectivene;
use App\Models\BookingGift;
use App\Models\BookingTrip;
use App\Models\Effectivenes;
use App\Models\Gift;
use App\Models\Order;
use App\Models\Trip;
use App\Services\TapService;
use Illuminate\Http\Request;

use function response;

class OrderController extends Controller
{
    public function index(PaginateRequest $request)
    {
        $data = Order::where('user_id', auth()->user()->id)->get();
        $data = OrderResource::collection($data);
        return response()->apiSuccess($data);

    }

    public function show($id)
    {
        $item = Order::findOrFail($id);
        $data = new OrderResource($item);
        return response()->apiSuccess($data);
    }

    public function bookingTrip(BookingTripRequest $request)
    {
        $item = Trip::findOrFail($request->trip_id);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['payment_status'] = 'pendding';
        $data['status'] = 0;
        $data['total'] = $item->price;

        $data['lat'] = $request->lat;
        $data['long'] = $request->long;

        $data['vendor_id'] = $item->vendor_id;
        $order = BookingTrip::create($data);
        if ($request->payment_way == 'online') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'trip']);
            $tap = $payment->pay($item->price);
            if ($tap['success']) {
                $order->payment_id = $tap['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($tap);
        }
        $order = new OrderResource($order);
        return response()->apiSuccess($order);
    }
    public function tripPay($id)
    {
        $order = BookingTrip::findOrFail($id);
        if ($order->payment_status != 'CAPTURED') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'trip']);
            $result = $payment->pay($order->total);
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
        $data['user_id'] = auth()->user()->id;
        $data['payment_status'] = 'pendding';
        $data['status'] = 0;
        $data['total'] = $item->price;
        $data['vendor_id'] = $item->vendor_id;
        $data['effectivene_id'] = $request->effectivene_id;
        $order = BookingEffectivene::create($data);
        if ($request->payment_way == 'online') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'effectivenes']);
            $tap = $payment->pay($item->price);
            if ($tap['success']) {
                $order->payment_id = $tap['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($tap);
        }
        $order = new OrderResource($order);
        return response()->apiSuccess($order);
    }

    public function effectivenePay($id)
    {
        $order = BookingEffectivene::findOrFail($id);
        if ($order->payment_status != 'CAPTURED') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'effectivenes']);
            $result = $payment->pay($order->total);
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
        $data['user_id'] = auth()->user()->id;
        $data['payment_status'] = 'pendding';
        $data['status'] = 0;
        $data['total'] = $item->price;
        $data['vendor_id'] = $item->vendor_id;
        $data['gift_id'] = $request->gift_id;
        $data['lat'] = $request->lat;
        $data['long'] = $request->long;
        $data['delivery_address'] = $request->delivery_address;
        $data['delivery_way'] = $request->delivery_way;
        $data['quantity'] = $request->quantity;
        $order = BookingGift::create($data);
        if ($request->payment_way == 'online') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'gift']);
            $tap = $payment->pay($item->price);
            if ($tap['success']) {
                $order->payment_id = $tap['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($tap);
        }
        $order = new OrderResource($order);
        return response()->apiSuccess($order);
    }

    public function GiftPay($id)
    {
        $order = BookingGift::findOrFail($id);
        if ($order->payment_status != 'CAPTURED') {
            $payment = new TapService();
            $payment->callback_url = route('callBack', ['type' => 'gift']);
            $result = $payment->pay($order->total);
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
        $data['curren']['gifts'] = BookingGift::with(['gift.city','vendor'])->where('status', BookingGift::STATUS_PENDING)->where('user_id', auth()->user()->id)->get();
        $data['curren']['effectivenes'] = BookingEffectivene::with(['effectivene.city','vendor'])->where('status', BookingEffectivene::STATUS_PENDING)->where('user_id', auth()->user()->id)->get();
        $data['curren']['trips'] = BookingTrip::with(['trip.city','vendor'])->where('status', BookingTrip::STATUS_PENDING)->where('user_id', auth()->user()->id)->get();
        $data['ended']['gifts'] = BookingGift::with(['gift.city','vendor'])->where('status', [BookingGift::STATUS_REJECTED,BookingGift::STATUS_CANCELED,BookingGift::STATUS_COMPLEATED])->where('user_id', auth()->user()->id)->get();
        $data['ended']['effectivenes'] = BookingEffectivene::with(['effectivene.city','vendor'])->where('status', [BookingEffectivene::STATUS_REJECTED,BookingEffectivene::STATUS_CANCELED,BookingEffectivene::STATUS_COMPLEATED])->where('user_id', auth()->user()->id)->get();
        $data['ended']['trips'] = BookingTrip::with(['trip.city','vendor'])->where('status', [BookingTrip::STATUS_REJECTED,BookingTrip::STATUS_CANCELED,BookingTrip::STATUS_COMPLEATED])->where('user_id', auth()->user()->id)->get();
        return response()->apiSuccess($data);

    }

    public function callBack(Request $request, $type)
    {
        $payment = new TapService();
        $result = $payment->callBack($request->tap_id, $type);
        if ($result['success']) {
            return "success";
            // return response()->apiSuccess($result['data']);
        }
        return "error";
        // return response()->apiFail($result['message']);

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
        if ($type == 'gift') {
            $order = BookingGift::findOrFail($id);
        } elseif ($type == 'effectivene') {
            $order = BookingEffectivene::findOrFail($id);
        } else {
            $order = BookingTrip::findOrFail($id);
        }
        $order->status = BookingTrip::STATUS_CANCELED;
        $order->save();
        return response()->apiSuccess($order);
    }
}
