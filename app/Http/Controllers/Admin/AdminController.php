<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Rate;
use App\Models\Supplier;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    private $viewIndex  = 'admin.pages.dashboard.index';

    public function index(Request $request)
    {
        $customers = User::where('type', User::TYPE_CLIENT)->count();
        $suppliers = Supplier::count();
        $current_orders = Order::where('status', Order::STATUS_PENDING)->count();
        $old_orders = Order::where('status', Order::STATUS_COMPLEALED)->count();
        $canceled_orders = Order::where('status', Order::STATUS_CANCELED)->count();
        $trips = Trip::count();
        $current_orders_chart = [];
        $old_orders_chart = [];
        $canceled_orders_chart = [];
        $vendors_chart = [];
        $clients_chart = [];
        $period = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($period as $dt) {
            $current_orders_chart[] = Order::whereMonth('created_at', $dt)->where('status', Order::STATUS_PENDING)->count();
            $old_orders_chart[] = Order::whereMonth('created_at', $dt)->where('status', Order::STATUS_COMPLEALED)->count();
            $canceled_orders_chart[] = Order::whereMonth('created_at', $dt)->where('status', Order::STATUS_CANCELED)->count();
            $vendors_chart[] = User::where('type',User::TYPE_SUPPLIER)->whereMonth('created_at', $dt)->count();
            $clients_chart[] = User::where('type',User::TYPE_CLIENT)->whereMonth('created_at', $dt)->count();
        }
        $max_order_chart = max(max($current_orders_chart), max($old_orders_chart), max($current_orders_chart));
        $max_clients_vendors_chart = max(max($clients_chart),max($vendors_chart));

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


    public function listRate(Request $request){
        $data = Rate::with('vandor')->where(function($query) use ($request){
            if($request->filled('user_id')){
                $query->where('user_id', $request->user_id);
            }
        })->select('*');
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('vandor', function ($item) {
            return  $item->vandor?->name;
        })
        ->rawColumns(['vendor'])
        ->make(true);
    }
    public function listFavorite(Request $request){
        $data = Favorite::with('trip')->where(function($query) use ($request){
            if($request->filled('user_id')){
                $query->where('user_id', $request->user_id);
            }
        })->select('*');
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('trip', function ($item) {
            return  $item->trip?->title;
        })
        ->rawColumns(['vendor'])
        ->make(true);
    }


    public function notification(Request $request)
    {
    }
}
