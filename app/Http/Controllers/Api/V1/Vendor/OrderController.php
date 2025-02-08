<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Models\BookingEffectivene;
use App\Models\BookingGift;
use App\Models\BookingTrip;
use App\Models\Order;
use Carbon\Carbon;

use function response;

class OrderController extends Controller
{
    public function homePage()
    {

        $data['pendding_requests']['gifts'] = BookingGift::with(['gift', 'user'])->where('status', Order::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['pendding_requests']['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->whereHas('effectivene', function ($query) {
            return $query->where('to_date','<=',date('Y-m-d') );
        })->where('status', Order::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['pendding_requests']['trips'] = BookingTrip::with(['trip', 'user'])->where('status', Order::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();


        $profit_gifts = BookingGift::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');
        $profit_effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');
        $profit_trips = BookingTrip::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');
        $total_effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');
        $total_gifts = BookingGift::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');
        $total_trips = BookingTrip::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');


        $data['debit'] = 0;
        $data['profit'] = $profit_gifts + $profit_effectivenes + $profit_trips;
        $data['balance'] = $total_gifts + $total_effectivenes + $total_trips;


        $data['day_invoice']['gifts'] = BookingGift::with(['gift', 'user'])->whereDate('created_at', Carbon::today())->where('vendor_id', auth()->user()->id)->get();
        $data['day_invoice']['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->whereHas('effectivene', function ($query) {
            return $query->where('to_date','<=',date('Y-m-d') );
        })->whereDate('created_at', Carbon::today())->where('vendor_id', auth()->user()->id)->get();
        $data['day_invoice']['trips'] = BookingTrip::with(['trip', 'user'])->where('booking_date', date('Y-m-d'))->where('vendor_id', auth()->user()->id)->get();
        $data['can_pay_later'] = auth()->user()->can_pay_later;
        $data['can_cancel'] = auth()->user()->can_cancel;
        return response()->apiSuccess($data);
    }
    public function walletPage()
    {
        $currentDate = Carbon::now();

        $total_gifts = BookingGift::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');
        $total_effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');
        $total_trips = BookingTrip::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');

        $profit_gifts = BookingGift::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');
        $profit_effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');
        $profit_trips = BookingTrip::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');

        $gifts_month = BookingGift::whereMonth('created_at', $currentDate->month)->whereYear('created_at', $currentDate->year)->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $effectivenes_month = BookingEffectivene::whereMonth('created_at', $currentDate->month)->whereYear('created_at', $currentDate->year)->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $trips_month = BookingTrip::whereMonth('created_at', $currentDate->month)->whereYear('created_at', $currentDate->year)->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();

        $gifts_3 = BookingGift::whereBetween('created_at', [
                                    $currentDate->copy()->subMonths(3)->startOfMonth(),
                                    $currentDate->endOfMonth(),
                                ])->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $effectivenes_3 = BookingEffectivene::whereBetween('created_at', [
                                        $currentDate->copy()->subMonths(3)->startOfMonth(),
                                        $currentDate->endOfMonth(),
                                    ])->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $trips_3 = BookingTrip::whereBetween('created_at', [
                                $currentDate->copy()->subMonths(3)->startOfMonth(),
                                $currentDate->endOfMonth(),
                            ])->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();

        $gifts_6 = BookingGift::whereBetween('created_at', [
                                    $currentDate->copy()->subMonths(6)->startOfMonth(),
                                    $currentDate->endOfMonth(),
                                ])->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $effectivenes_6 = BookingEffectivene::whereBetween('created_at', [
                                        $currentDate->copy()->subMonths(6)->startOfMonth(),
                                        $currentDate->endOfMonth(),
                                    ])->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $trips_6 = BookingTrip::whereBetween('created_at', [
                                $currentDate->copy()->subMonths(6)->startOfMonth(),
                                $currentDate->endOfMonth(),
                            ])->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();

        $gifts_year = BookingGift::whereYear('created_at', $currentDate->year)->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $effectivenes_year = BookingEffectivene::whereYear('created_at', $currentDate->year)->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $trips_year = BookingTrip::whereYear('created_at', $currentDate->year)->where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();


        $mergedCollection_month = $gifts_month->concat($effectivenes_month)->concat($trips_month);
        $mergedCollection_3 = $gifts_3->concat($effectivenes_3)->concat($trips_3);
        $mergedCollection_6 = $gifts_6->concat($effectivenes_6)->concat($trips_6);
        $mergedCollection_year = $gifts_year->concat($effectivenes_year)->concat($trips_year);

        $orders_months = $mergedCollection_month->sortByDesc('created_at')->values();
        $orders_3 = $mergedCollection_3->sortByDesc('created_at')->values();
        $orders_6 = $mergedCollection_6->sortByDesc('created_at')->values();
        $orders_year = $mergedCollection_year->sortByDesc('created_at')->values();
        $data['debit'] = 0;
        $data['profit'] = $profit_gifts + $profit_effectivenes + $profit_trips;
        $data['balance'] = $total_gifts + $total_effectivenes + $total_trips;

        $data['operations']['month'] = $orders_months;
        $data['operations']['month_3'] = $orders_3;
        $data['operations']['month_6'] = $orders_6;
        $data['operations']['year'] = $orders_year;
        return response()->apiSuccess($data);
    }
    public function withdrwal()
    {
        $total_gifts = BookingGift::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->update(['status' => Order::STATUS_WITHDRWAL]);
        $total_effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->update(['status' => Order::STATUS_WITHDRWAL]);
        $total_trips = BookingTrip::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->update(['status' => Order::STATUS_WITHDRWAL]);
        return response()->apiSuccess(true);
    }
    public function penddingRequests()
    {

        $data['gifts'] = BookingGift::with(['gift', 'user'])->where('status', Order::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->where('status', Order::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['trips'] = BookingTrip::with(['trip', 'user'])->where('status', Order::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();

        return response()->apiSuccess($data);
    }
    public function invoices()
    {
        $data['current']['gifts'] = BookingGift::with(['gift', 'user'])->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_ACCEPTED,Order::STATUS_ONPROGRESS])->where('vendor_id', auth()->user()->id)->get();
        $data['current']['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_ACCEPTED,Order::STATUS_ONPROGRESS])->where('vendor_id', auth()->user()->id)->get();
        $data['current']['trips'] = BookingTrip::with(['trip', 'user'])->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_ACCEPTED,Order::STATUS_ONPROGRESS])->where('vendor_id', auth()->user()->id)->get();
        $data['compleated']['gifts'] = BookingGift::with(['gift', 'user'])->whereIn('status', [Order::STATUS_COMPLEALED,Order::STATUS_REJECTED,Order::STATUS_WITHDRWAL])->where('vendor_id', auth()->user()->id)->get();
        $data['compleated']['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->whereIn('status', [Order::STATUS_COMPLEALED,Order::STATUS_REJECTED,Order::STATUS_WITHDRWAL])->where('vendor_id', auth()->user()->id)->get();
        $data['compleated']['trips'] = BookingTrip::with(['trip', 'user'])->whereIn('status', [Order::STATUS_COMPLEALED,Order::STATUS_REJECTED,Order::STATUS_WITHDRWAL])->where('vendor_id', auth()->user()->id)->get();
        return response()->apiSuccess($data);
    }
    public function acceptOrder($type, $id)
    {
        if ($type == 'gift') {
            $order = BookingGift::findOrFail($id);
        } elseif ($type == 'effectivene') {
            $order = BookingEffectivene::findOrFail($id);
        } else {
            $order = BookingTrip::findOrFail($id);
        }
        $order->status = Order::STATUS_ACCEPTED;
        $order->save();
        return response()->apiSuccess($order);
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
        $order->status = Order::STATUS_CANCELED;
        $order->save();
        return response()->apiSuccess($order);
    }
    public function showOrder($type, $id)
    {
        if ($type == 'gift') {
            $order = BookingGift::with(['gift', 'user'])->findOrFail($id);
            return response()->apiSuccess($order);
        } elseif ($type == 'effectivene') {
            $order = BookingEffectivene::with(['effectivene', 'user'])->findOrFail($id);
            return response()->apiSuccess($order);
        } else {
            $order = BookingTrip::with(['trip', 'user'])->findOrFail($id);
            return response()->apiSuccess($order);
        }
    }
    public function getAll($type)
    {
        if ($type == 'gifts') {
            $order = BookingGift::with(['gift', 'user'])->get();
            return response()->apiSuccess($order);
        } elseif ($type == 'effectivenes') {
            $order = BookingEffectivene::with(['effectivene', 'user'])->get();
            return response()->apiSuccess($order);
        } else {
            $order = BookingTrip::with(['trip', 'user'])->get();
            return response()->apiSuccess($order);
        }
    }

}
