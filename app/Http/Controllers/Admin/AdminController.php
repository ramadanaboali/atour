<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingEffectivene;
use App\Models\BookingGift;
use App\Models\BookingTrip;
use App\Models\Effectivenes;
use App\Models\Favorite;
use App\Models\Gift;
use App\Models\Order;
use App\Models\Rate;
use App\Models\Supplier;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    private $viewIndex  = 'admin.pages.dashboard.index';

    public function index(Request $request)
    {
        // Customers & Suppliers
        $customers = User::where('type', User::TYPE_CLIENT)->whereNotNull('email')->count();
        $suppliers = User::where('type', User::TYPE_SUPPLIER)->whereNotNull('email')->count();

        // Orders
        $current_orders   = BookingTrip::where('status', Order::STATUS_PENDING)->count();
        $old_orders       = BookingTrip::where('status', Order::STATUS_COMPLEALED)->count();
        $canceled_orders  = BookingTrip::where('status', Order::STATUS_CANCELED)->count();

        $effectiveness = BookingEffectivene::count();
        $gifts = BookingGift::count();
        $trips = Trip::count();
        
        $getChartData = function ($model) {
            return [
                __('orders.current')   => $model::whereIn('status', [
                                    Order::STATUS_PENDING,
                                    Order::STATUS_ONPROGRESS,
                                    Order::STATUS_ACCEPTED
                                ])->count(),
                __('orders.completed') => $model::where('status', Order::STATUS_COMPLEALED)->count(),
                __('orders.canceled')  => $model::where('status', Order::STATUS_CANCELED)->count(),
            ];
        };

        // Charts
        $tripChart          = $getChartData(BookingTrip::class);
        $giftChart          = $getChartData(BookingGift::class);
        $effectivenessChart = $getChartData(BookingEffectivene::class);

        // Additional chart data for modern dashboard
        $monthlyRevenue = $this->getMonthlyRevenue();
        $serviceDistribution = [
            'trips' => $trips,
            'gifts' => $gifts,
            'effectiveness' => $effectiveness
        ];
        
        $userGrowth = $this->getUserGrowthData();
        $topSuppliers = $this->getTopSuppliers();
        $recentActivity = $this->getRecentActivity();
        
        // Revenue statistics
        $totalRevenue = BookingTrip::where('status', Order::STATUS_COMPLEALED)->sum('total') +
                       BookingGift::where('status', Order::STATUS_COMPLEALED)->sum('total') +
                       BookingEffectivene::where('status', Order::STATUS_COMPLEALED)->sum('total');
        
        $monthlyRevenueAmount = BookingTrip::where('status', Order::STATUS_COMPLEALED)
                               ->whereMonth('created_at', now()->month)
                               ->sum('total') +
                               BookingGift::where('status', Order::STATUS_COMPLEALED)
                               ->whereMonth('created_at', now()->month)
                               ->sum('total') +
                               BookingEffectivene::where('status', Order::STATUS_COMPLEALED)
                               ->whereMonth('created_at', now()->month)
                               ->sum('total');

        return view($this->viewIndex, compact(
            'customers',
            'suppliers',
            'current_orders',
            'old_orders',
            'canceled_orders',
            'trips',
            'tripChart',
            'giftChart',
            'gifts',
            'effectiveness',
            'effectivenessChart',
            'monthlyRevenue',
            'serviceDistribution',
            'userGrowth',
            'topSuppliers',
            'recentActivity',
            'totalRevenue',
            'monthlyRevenueAmount'
        ));
    }

    private function getMonthlyRevenue()
    {
        $months = [];
        $revenues = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $revenue = BookingTrip::where('status', Order::STATUS_COMPLEALED)
                      ->whereMonth('created_at', $date->month)
                      ->whereYear('created_at', $date->year)
                      ->sum('total') +
                      BookingGift::where('status', Order::STATUS_COMPLEALED)
                      ->whereMonth('created_at', $date->month)
                      ->whereYear('created_at', $date->year)
                      ->sum('total') +
                      BookingEffectivene::where('status', Order::STATUS_COMPLEALED)
                      ->whereMonth('created_at', $date->month)
                      ->whereYear('created_at', $date->year)
                      ->sum('total');
                      
            $revenues[] = $revenue;
        }
        
        return [
            'labels' => $months,
            'data' => $revenues
        ];
    }

    private function getUserGrowthData()
    {
        $months = [];
        $customers = [];
        $suppliers = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $customers[] = User::where('type', User::TYPE_CLIENT)
                          ->whereMonth('created_at', $date->month)
                          ->whereYear('created_at', $date->year)
                          ->count();
                          
            $suppliers[] = User::where('type', User::TYPE_SUPPLIER)
                          ->whereMonth('created_at', $date->month)
                          ->whereYear('created_at', $date->year)
                          ->count();
        }
        
        return [
            'labels' => $months,
            'customers' => $customers,
            'suppliers' => $suppliers
        ];
    }

    private function getTopSuppliers()
    {
        return User::where('type', User::TYPE_SUPPLIER)
                  ->withCount(['supplierTrips', 'supplierGifts', 'supplierEffectivenes'])
                  ->orderBy('supplier_trips_count', 'desc')
                  ->limit(5)
                  ->get()
                  ->map(function ($supplier) {
                      return [
                          'name' => $supplier->name,
                          'total_services' => $supplier->supplier_trips_count + 
                                            $supplier->supplier_gifts_count + 
                                            $supplier->supplier_effectivenes_count
                      ];
                  });
    }

    private function getRecentActivity()
    {
        $activities = collect();
        
        // Recent bookings
        $recentBookings = BookingTrip::with('user', 'trip')
                         ->latest()
                         ->limit(5)
                         ->get()
                         ->map(function ($booking) {
                             return [
                                 'type' => 'booking',
                                 'message' => $booking->user->name . ' booked ' . $booking->trip->title,
                                 'time' => $booking->created_at->diffForHumans(),
                                 'icon' => 'calendar',
                                 'color' => 'success'
                             ];
                         });
        
        // Recent users
        $recentUsers = User::where('type', User::TYPE_CLIENT)
                          ->latest()
                          ->limit(3)
                          ->get()
                          ->map(function ($user) {
                              return [
                                  'type' => 'user',
                                  'message' => 'New customer: ' . $user->name,
                                  'time' => $user->created_at->diffForHumans(),
                                  'icon' => 'user-plus',
                                  'color' => 'info'
                              ];
                          });
        
        return $activities->merge($recentBookings)
                         ->merge($recentUsers)
                         ->sortByDesc('time')
                         ->take(8);
    }

    public function updateToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token' => $request->fcm_token]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }


    public function listRate(Request $request)
    {
        $data = Rate::with('vandor')->where(function ($query) use ($request) {
            if ($request->filled('user_id')) {
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
    public function listFavorite(Request $request)
    {
        $data = Favorite::with('trip')->where(function ($query) use ($request) {
            if ($request->filled('user_id')) {
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
    public function selectTrip(Request $request)
    {
        $data = Trip::distinct()
               ->where('active', true)
               ->where(function ($query) use ($request) {
                   if ($request->filled('q')) {
                       if (App::isLocale('en')) {
                           return $query->where('title_en', 'like', '%'.$request->q.'%');
                       } else {
                           return $query->where('title_ar', 'like', '%'.$request->q.'%');
                       }
                   }
                   if ($request->filled('vendor_id')) {
                       return $query->where('vendor_id', $request->vendor_id);
                   }
               })->select('id', 'title_en', 'title_ar', 'description_ar', 'description_en', 'start_point_en', 'start_point_ar')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('category.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }
    public function selectGift(Request $request)
    {
        $data = Gift::distinct()
               ->where('active', true)
               ->where(function ($query) use ($request) {
                   if ($request->filled('q')) {
                       if (App::isLocale('en')) {
                           return $query->where('title_en', 'like', '%'.$request->q.'%');
                       } else {
                           return $query->where('title_ar', 'like', '%'.$request->q.'%');
                       }
                   }
                   if ($request->filled('vendor_id')) {
                       return $query->where('vendor_id', $request->vendor_id);
                   }
               })->select('id', 'title_en', 'title_ar', 'description_ar', 'description_en')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('category.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }
    public function selectEffectivenes(Request $request)
    {
        $data = Effectivenes::distinct()
               ->where('active', true)
               ->where(function ($query) use ($request) {
                   if ($request->filled('q')) {
                       if (App::isLocale('en')) {
                           return $query->where('title_en', 'like', '%'.$request->q.'%');
                       } else {
                           return $query->where('title_ar', 'like', '%'.$request->q.'%');
                       }
                   }
                   if ($request->filled('vendor_id')) {
                       return $query->where('vendor_id', $request->vendor_id);
                   }

               })->select('id', 'title_en', 'title_ar', 'description_ar', 'description_en')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('category.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }
    public function getOffers(Request $request)
    {
        $data = [];
        if ($request->type == "effectivenes") {
            $data = Effectivenes::distinct()
            ->where('active', true)
            ->where(function ($query) use ($request) {
                if ($request->filled('q')) {
                    if (App::isLocale('en')) {
                        return $query->where('title_en', 'like', '%'.$request->q.'%');
                    } else {
                        return $query->where('title_ar', 'like', '%'.$request->q.'%');
                    }
                }
                if ($request->filled('vendor_id')) {
                    return $query->where('vendor_id', $request->vendor_id);
                }

            })->select('id', 'title_en', 'title_ar', 'description_ar', 'description_en')->get();
        }
        if ($request->type == "trip") {
            $data = Trip::distinct()
                ->where('active', true)
                ->where(function ($query) use ($request) {
                    if ($request->filled('q')) {
                        if (App::isLocale('en')) {
                            return $query->where('title_en', 'like', '%' . $request->q . '%');
                        } else {
                            return $query->where('title_ar', 'like', '%' . $request->q . '%');
                        }
                    }
                    if ($request->filled('vendor_id')) {
                        return $query->where('vendor_id', $request->vendor_id);
                    }
                })->select('id', 'title_en', 'title_ar', 'description_ar', 'description_en')->get();
        }
        if ($request->type == "gift") {
            $data = Gift::distinct()
                ->where('active', true)
                ->where(function ($query) use ($request) {
                    if ($request->filled('q')) {
                        if (App::isLocale('en')) {
                            return $query->where('title_en', 'like', '%' . $request->q . '%');
                        } else {
                            return $query->where('title_ar', 'like', '%' . $request->q . '%');
                        }
                    }


                })->where('vendor_id', $request->vendor_id)->select('id', 'title_en', 'title_ar', 'description_ar', 'description_en')->get();
        }

        $html = '<option value="">'. __('admin.select') .'</option>';
        foreach ($data as $row) {
            $selected = '';
            if ($request->trip_id == $row->id) {
                $selected = 'selected';
            }
            if ($request->effectivenes_id == $row->id) {
                $selected = 'selected';
            }
            if ($request->gift_id == $row->id) {
                $selected = 'selected';
            }
            $html .= '<option value="'.$row->id.'" '.$selected.'>'.$row->title.'</option>';
        }
        return $html;
    }
}
