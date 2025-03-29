<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendPasswordMail;
use App\Models\BookingEffectivene;
use App\Models\BookingGift;
use App\Models\BookingTrip;
use App\Models\Order;
use App\Models\OrderFee;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserFee;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Str;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    private $viewIndex  = 'admin.pages.suppliers.index';
    private $viewEdit   = 'admin.pages.suppliers.create_edit';
    private $viewShow   = 'admin.pages.suppliers.show';
    private $route      = 'admin.suppliers';

    public function index(Request $request): View
    {
        $status = null;
        $created_at = null;
        return view($this->viewIndex, get_defined_vars());
    }
    public function requestJoin(Request $request): View
    {
        $status = 'pendding';
        $created_at = null ;
        return view($this->viewIndex, get_defined_vars());
    }
    public function newSuppliers(Request $request): View
    {
        $status = null;
        $created_at = date('Y-m-d');
        return view($this->viewIndex, get_defined_vars());
    }
    public function currentSuppliers(Request $request): View
    {
        $status = 'accepted';
        $created_at = null ;
        return view($this->viewIndex, get_defined_vars());
    }


    public function create(): View
    {
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = Supplier::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $user=User::findOrFail($id);
        $trips = Trip::where('vendor_id', $id)->orderByDesc('id')->get();
        return view($this->viewShow, get_defined_vars());
    }


    public function status($id)
    {
        $item = User::findOrFail($id);
        if ($item->status == 'pendding') {
            $password = Str::random(8);
            $item->password = Hash::make($password);
            Mail::to($item->email)->send(new SendPasswordMail($item->email, $password));
        }
        if ($item->active == 0) {
            $item->active = 1;
            $item->status = "accepted";
        } else {
            $item->active = 0;
        }

        $item->save();
        flash(__('suppliers.messages.updated'))->success();

        return back();
    }
    public function setting($id)
    {
        $item = User::findOrFail($id);
        $user_fee = UserFee::where('user_id', $item->id)->first();
        return view('admin.pages.suppliers.settings', ['item' => $item,'user_fee' => $user_fee]);
    }
    public function saveSetting(Request $request, $id)
    {
        $item = User::findOrFail($id);
        if ($request->can_cancel) {
            $item->can_cancel = 1;

        } else {
            $item->can_cancel = 0;
        }
        if ($request->can_pay_later) {
            $item->can_pay_later = 1;

        } else {
            $item->can_pay_later = 0;
        }

        if ($request->pay_on_deliver) {
            $item->pay_on_deliver = 1;

        } else {
            $item->pay_on_deliver = 0;
        }

        if ($request->ban_vendor) {
            $item->ban_vendor = 1;

        } else {
            $item->ban_vendor = 0;
        }
        $payment = [
        'tax_type' => $request->tax_type,
        'tax_value' => $request->tax_value,
        'payment_way_type' => $request->payment_way_type,
        'payment_way_value' => $request->payment_way_value,
        'admin_type' => $request->admin_type,
        'admin_value' => $request->admin_value,
        'admin_fee_type' => $request->admin_fee_type,
        'admin_fee_value' => $request->admin_fee_value,
        ];
        UserFee::updateOrCreate(['user_id' => $item->id], $payment);

        $item->save();

        flash(__('suppliers.messages.updated'))->success();

        return back();
    }
    public function destroy($id): RedirectResponse
    {
        $item = Supplier::findOrFail($id);
        if ($item->delete()) {
            flash(__('suppliers.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function select(Request $request): JsonResponse|string
    {
        $data = User::distinct()
                 ->where(function ($query) use ($request) {
                     if ($request->filled('q')) {
                         return $query->where('name', 'like', '%'.$request->q.'%');

                     }
                 })->select('id', 'name as text')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('category.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }


    public function list(Request $request)
    {
        $data = User::leftJoin('suppliers', 'suppliers.user_id', 'users.id')
        ->where(function ($query) use ($request) {
            if ($request->filled('name')) {
                $query->where('users.name', 'like', '%'. $request->name .'%');
            }
            if ($request->filled('city_id')) {
                $query->where('suppliers.city_id', $request->city_id);
            }
            if ($request->filled('active')) {
                $query->where('users.active', $request->active);
            }

            if ($request->filled('type')) {
                $query->where('suppliers.type', $request->type);
            }
            if ($request->filled('status')) {
                $query->where('users.status', $request->status);
            }
            if ($request->filled('created_at')) {

                $query->where('users.created_at', '>=', $request->created_at.' 00:00:00');
            }

        })->where('users.type', User::TYPE_SUPPLIER)->select(['users.*']);
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item?->photo . '" height="100px" width="100px">';
        })
        ->editColumn('active', function ($item) {
            return $item?->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })


        ->rawColumns(['photo','active'])
        ->make(true);
    }

    public function orders(Request $request): JsonResponse
    {


        $bookingGift = BookingGift::with(['user', 'vendor'])
            ->where('vendor_id', $request->user_id)
            ->select('id', 'admin_value', 'status', 'total', 'created_at', DB::raw("'BookingGift' as source"))
            ->get();

        $bookingTrip = BookingTrip::with(['user', 'vendor'])
            ->where('vendor_id', $request->user_id)
            ->select('id', 'admin_value', 'status', 'total', 'created_at', DB::raw("'BookingTrip' as source"))
            ->get();

        $bookingEffectivene = BookingEffectivene::with(['user', 'vendor'])
            ->where('vendor_id', $request->user_id)
            ->select('id', 'admin_value', 'status', 'total', 'created_at', DB::raw("'BookingEffectivene' as source"))
            ->get();

        // Merge collections to retain relationships
        $data = $bookingGift->merge($bookingTrip)->merge($bookingEffectivene);

        // Sort by created_at manually (since `orderBy` won't work with collections)
        $data = $data->sortByDesc('created_at')->values();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('client', function ($item) {
                return $item->user?->name;
            })
            ->addColumn('vendor', function ($item) {
                return $item->vendor?->name;
            })
            ->addColumn('status', function ($item) {
                return __('admin.orders_statuses.' . $item->status);
            })
            ->editColumn('source', function ($item) {
                return __('admin.' . $item->source);
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at?->format('Y-m-d H:i');
            })
            ->rawColumns(['source', 'created_at', 'client', 'vendor'])
            ->make(true);


    }
    public function payments(Request $request)
    {
        if ($request->ajax()) {

            $data = OrderFee::with(['vendor','effectiveness','trip','gift'])
                ->leftJoin('trips', function ($join) {
                    $join->on('trips.id', '=', 'order_fees.order_id')
                        ->where('order_fees.order_type', 'trip');
                })
                ->leftJoin('gifts', function ($join) {
                    $join->on('gifts.id', '=', 'order_fees.order_id')
                        ->where('order_fees.order_type', 'gift');
                })
                ->leftJoin('effectivenes', function ($join) {
                    $join->on('effectivenes.id', '=', 'order_fees.order_id')
                        ->where('order_fees.order_type', 'effectivenes');
                })
                ->select([
                    'order_fees.*'
                ])
                ->orderByDesc('created_at');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('vendor', function ($item) {
                    return $item->vendor?->name;
                })
                ->addColumn('status', function ($item) {
                    return __('admin.orders_statuses.' . $item->status);
                })
            ->editColumn('source_name', function ($item) {
                if ($item->trip) {
                    return $item->trip?->title;
                }
                if ($item->gift) {
                    return $item->gift?->title;
                }
                if ($item->effectiveness) {
                    return $item->effectiveness?->title;
                }
            })
            ->editColumn('source', function ($item) {
                if ($item->trip) {
                    return __('admin.source_types.trip');
                }
                if ($item->gift) {
                    return __('admin.source_types.gift');
                }
                if ($item->effectiveness) {
                    return __('admin.source_types.effectivenes');
                }
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at?->format('Y-m-d H:i');
            })
            ->rawColumns(['source','source_name','created_at','vendor'])
            ->make(true);
        }
        return view('admin.pages.suppliers.payments');
    }

    public function settlement($id)
    {
        $data = OrderFee::where('vendor_id', $id)->update(['status' => 1]);
        flash(__('admin.messages.updated'))->success();
        return to_route('admin.accounts.suppliers');

    }
    public function suppliers(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('users')
                ->where('users.type', User::TYPE_SUPPLIER)
                ->leftJoin(DB::raw("(SELECT vendor_id, COUNT(id) as count_booking_gift, COALESCE(SUM(customer_total), 0) as sum_booking_gift FROM booking_gifts GROUP BY vendor_id) as gifts"), 'users.id', '=', 'gifts.vendor_id')
                ->leftJoin(DB::raw("(SELECT vendor_id, COUNT(id) as count_booking_trib, COALESCE(SUM(customer_total), 0) as sum_booking_trib FROM booking_trips GROUP BY vendor_id) as trips"), 'users.id', '=', 'trips.vendor_id')
                ->leftJoin(DB::raw("(SELECT vendor_id, COUNT(id) as count_booking_effectivenes, COALESCE(SUM(customer_total), 0) as sum_booking_effectivenes FROM booking_effectivenes GROUP BY vendor_id) as effects"), 'users.id', '=', 'effects.vendor_id')
                ->leftJoin(DB::raw("(SELECT vendor_id, 
                    COALESCE(SUM(tax_value), 0) as total_tax_value,
                    COALESCE(SUM(payment_way_value), 0) as total_payment_way_value,
                    COALESCE(SUM(admin_value), 0) as total_admin_value,
                    COALESCE(SUM(admin_fee_value), 0) as total_admin_fee_value,
                    COALESCE(SUM(CASE WHEN status = 0 THEN (tax_value + payment_way_value + admin_value + admin_fee_value) ELSE 0 END), 0) as total_order_fees_0,
                    COALESCE(SUM(CASE WHEN status = 1 THEN (tax_value + payment_way_value + admin_value + admin_fee_value) ELSE 0 END), 0) as total_order_fees_1
                    FROM order_fees GROUP BY vendor_id) as orders"), 'users.id', '=', 'orders.vendor_id')
                ->select(
                    'users.name',
                    'users.id',
                    DB::raw('COALESCE(gifts.count_booking_gift, 0) as count_booking_gift'),
                    DB::raw('COALESCE(trips.count_booking_trib, 0) as count_booking_trib'),
                    DB::raw('COALESCE(effects.count_booking_effectivenes, 0) as count_booking_effectivenes'),
                    DB::raw('COALESCE(gifts.sum_booking_gift, 0) + COALESCE(trips.sum_booking_trib, 0) + COALESCE(effects.sum_booking_effectivenes, 0) as total_money'),
                    DB::raw('COALESCE(orders.total_tax_value, 0) as total_tax_value'),
                    DB::raw('COALESCE(orders.total_payment_way_value, 0) as total_payment_way_value'),
                    DB::raw('COALESCE(orders.total_admin_value, 0) as total_admin_value'),
                    DB::raw('COALESCE(orders.total_admin_fee_value, 0) as total_admin_fee_value'),
                    DB::raw('COALESCE(orders.total_order_fees_0, 0) as total_order_fees_0'),
                    DB::raw('COALESCE(orders.total_order_fees_1, 0) as total_order_fees_1')
                )
                ->groupBy('users.id', 'users.name')->orderBy('total_order_fees_0', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
            ->addColumn('remain', function ($item) {
                return ($item->total_order_fees_0 > $item->total_order_fees_1) ? ($item->total_order_fees_0 - $item->total_order_fees_1) : 0;
            })
            ->rawColumns(['remain'])
            ->make(true);
        }
        return view('admin.pages.suppliers.payment_suppliers');
    }
}
