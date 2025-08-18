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

        $effectiveness = BookingEffectivene::where('status', Order::STATUS_CANCELED)->count();
        $gifts = BookingGift::where('status', Order::STATUS_CANCELED)->count();
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
            'effectivenessChart'
        ));
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
