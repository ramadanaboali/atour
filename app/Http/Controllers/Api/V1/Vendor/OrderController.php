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

        $data['pendding_requests']['gifts'] = BookingGift::with(['gift', 'user'])->where('status', BookingGift::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['pendding_requests']['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->where('status', BookingEffectivene::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['pendding_requests']['trips'] = BookingTrip::with(['trip', 'user'])->where('status', BookingTrip::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['profit'] = 0;
        $data['debit'] = 0;
        $data['balance'] = 0;
        $data['day_invoice']['gifts'] = BookingGift::with(['gift', 'user'])->whereDate('created_at', Carbon::today())->where('vendor_id', auth()->user()->id)->get();
        $data['day_invoice']['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->whereDate('created_at', Carbon::today())->where('vendor_id', auth()->user()->id)->get();
        $data['day_invoice']['trips'] = BookingTrip::with(['trip', 'user'])->where('booking_date', date('Y-m-d'))->where('vendor_id', auth()->user()->id)->get();
        return response()->apiSuccess($data);
    }
    public function walletPage()
    {
        $total_gifts = BookingGift::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');
        $total_effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');
        $total_trips = BookingTrip::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->sum('total');

        $profit_gifts = BookingGift::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');
        $profit_effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');
        $profit_trips = BookingTrip::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_WITHDRWAL, Order::STATUS_COMPLEALED])->sum('total');

        $gifts = BookingGift::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $trips = BookingTrip::where('vendor_id', auth()->user()->id)->whereIn('status', [Order::STATUS_PENDING,Order::STATUS_WITHDRWAL])->select(['created_at','status','total'])->get();
        $mergedCollection = $gifts->concat($effectivenes)->concat($trips);
        $orders = $mergedCollection->sortByDesc('created_at')->values();


        $data['debit'] = 0;
        $data['profit'] = $profit_gifts + $profit_effectivenes + $profit_trips;
        $data['balance'] = $total_gifts + $total_effectivenes + $total_trips;

        $data['operations'] = $orders;
        return response()->apiSuccess($data);
    }
    public function withdrwal()
    {
        $total_gifts = BookingGift::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->update(['status'=>Order::STATUS_WITHDRWAL]);
        $total_effectivenes = BookingEffectivene::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->update(['status'=>Order::STATUS_WITHDRWAL]);
        $total_trips = BookingTrip::where('vendor_id', auth()->user()->id)->where('status', Order::STATUS_COMPLEALED)->update(['status'=>Order::STATUS_WITHDRWAL]);
        return response()->apiSuccess(true);
    }
    public function penddingRequests()
    {

        $data['gifts'] = BookingGift::with(['gift', 'user'])->where('status', BookingGift::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->where('status', BookingEffectivene::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();
        $data['trips'] = BookingTrip::with(['trip', 'user'])->where('status', BookingTrip::STATUS_PENDING)->where('vendor_id', auth()->user()->id)->get();

        return response()->apiSuccess($data);
    }
    public function invoices()
    {
        $data['gifts'] = BookingGift::with(['gift', 'user'])->where('vendor_id', auth()->user()->id)->get();
        $data['effectivenes'] = BookingEffectivene::with(['effectivene', 'user'])->where('vendor_id', auth()->user()->id)->get();
        $data['trips'] = BookingTrip::with(['trip', 'user'])->where('vendor_id', auth()->user()->id)->get();
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
        $order->status = BookingTrip::STATUS_ACCEPTED;
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
        $order->status = BookingTrip::STATUS_CANCELED;
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

}
