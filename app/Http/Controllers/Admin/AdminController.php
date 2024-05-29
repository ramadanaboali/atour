<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private $viewIndex  = 'admin.pages.dashboard.index';

    public function index(Request $request)
    {
        $customers = User::where('type',User::TYPE_CLIENT)->count();
        $suppliers = Supplier::count();
        $current_orders = Order::where('status',Order::STATUS_PENDING)->count();
        $old_orders = Order::where('status',Order::STATUS_COMPLEALED)->count();
        $canceled_orders = Order::where('status',Order::STATUS_CANCELED)->count();
        $trips = Trip::count();
    $current_orders_chart=[];
    $old_orders_chart=[];
    $canceled_orders_chart=[];
    $period = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    foreach ($period as $dt) {
        $current_orders_chart[]=Order::whereMonth('created_at', $dt)->where('status',Order::STATUS_PENDING)->count();
        $old_orders_chart[]=Order::whereMonth('created_at', $dt)->where('status',Order::STATUS_COMPLEALED)->count();
        $canceled_orders_chart[]=Order::whereMonth('created_at', $dt)->where('status',Order::STATUS_CANCELED)->count();
    }
    $max_order_chart = max(max(count($current_orders_chart),count($old_orders_chart)), count($current_orders_chart));

        return view($this->viewIndex, get_defined_vars());
    }
    public function updateToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token' => $request->fcm_token]);
            return response()->json([
                'success' => true
            ]);
        } catch(\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }
    public function notification(Request $request)
    {
    }
}
