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
use App\Models\SupplierService;
use App\Models\User;
use App\Models\UserFee;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
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
        $created_at = null;
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
        $created_at = null;
        return view($this->viewIndex, get_defined_vars());
    }


    public function create(): View
    {
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = User::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $user = User::findOrFail($id);
        // dd($user->supplier);
        $trips = Trip::where('vendor_id', $id)->orderByDesc('id')->get();
        return view($this->viewShow, get_defined_vars());
    }


    public function status($id)
    {
        $item = User::findOrFail($id);
        if (!$item->email) {
            flash(__('يرجى اضافة بريد الكترونى اولا'))->error();

            return back();

        }
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
        return view('admin.pages.suppliers.settings', ['item' => $item, 'user_fee' => $user_fee]);
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
        $item = User::findOrFail($id);
        if ($item->delete()) {
            flash(__('suppliers.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function select(Request $request): JsonResponse|string
    {
        $data = User::distinct()->whereNotNull('email')
            ->where(function ($query) use ($request) {
                if ($request->filled('q')) {
                    return $query->where('name', 'like', '%' . $request->q . '%');
                }
            })->select('id', 'name as text')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">' . __('category.select') . '</option>';
            foreach ($data as $row) {
                $html .= '<option value="' . $row->id . '">' . $row->text . '</option>';
            }
            return $html;
        }
        return response()->json($data);
    }


    public function list(Request $request)
    {
        $data = User::leftJoin('suppliers', 'suppliers.user_id', 'users.id')
            ->whereNotNull('email')->where(function ($query) use ($request) {
                if ($request->filled('name')) {
                    $query->where('users.name', 'like', '%' . $request->name . '%');
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

                    $query->where('users.created_at', '>=', $request->created_at . ' 00:00:00');
                }
            })->where('users.type', User::TYPE_SUPPLIER)->select(['users.*']);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('photo', function ($item) {
                return '<img src="' . $item?->photo . '" height="100px" width="100px">';
            })
            ->editColumn('name', function ($item) {
                if (auth()->user()->can('suppliers.show')) {
                    return '<a href="' . route('admin.suppliers.show', $item->id) . '">' . $item->name . '</a>';
                }
                return $item->name;
            })
            ->editColumn('code', function ($item) {
                if (auth()->user()->can('suppliers.show')) {
                    return '<a href="' . route('admin.suppliers.show', $item->id) . '">' . 'P-' . $item->code . '</a>';
                }
                return $item->code;
            })
            ->editColumn('active', function ($item) {
                return $item?->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
            })

            ->editColumn('created_at', function ($item) {
                return $item->created_at?->format('Y-m-d H:i');
            })
            ->rawColumns(['photo', 'active','code','created_at','name','code'])
            ->make(true);
    }

    public function orders(Request $request): JsonResponse
    {


        $bookingGift = BookingGift::with(['user', 'vendor'])
            ->where('vendor_id', $request->user_id)
            ->select('id', 'user_id', 'admin_value', 'status', 'total', 'created_at', DB::raw("'BookingGift' as source"))
            ->get();

        $bookingTrip = BookingTrip::with(['user', 'vendor'])
            ->where('vendor_id', $request->user_id)
            ->select('id', 'user_id', 'admin_value', 'status', 'total', 'created_at', DB::raw("'BookingTrip' as source"))
            ->get();

        $bookingEffectivene = BookingEffectivene::with(['user', 'vendor'])
            ->where('vendor_id', $request->user_id)
            ->select('id', 'user_id', 'admin_value', 'status', 'total', 'created_at', DB::raw("'BookingEffectivene' as source"))
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

            $data = OrderFee::with(['vendor', 'effectiveness', 'trip', 'gift'])
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
                    return $item->vendor?->name.' (P-'.$item->vendor?->code.')';
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
                ->rawColumns(['source', 'source_name', 'created_at', 'vendor'])
                ->make(true);
        }
        return view('admin.pages.suppliers.payments');
    }

    public function settlement($id)
    {
        $data = OrderFee::where('vendor_id', $id)->get();//update(['status' => 1]);

        $data = DB::select("SELECT orders.*
                FROM users
                INNER JOIN (
                    SELECT 
                        order_of.*,
                        COALESCE(bt.vendor_id, bg.vendor_id, be.vendor_id) AS vendor_idss
                    FROM order_fees AS order_of
                    LEFT JOIN booking_trips AS bt ON order_of.order_id = bt.id AND bt.status = ".Order::STATUS_COMPLEALED."
                    LEFT JOIN booking_gifts AS bg ON order_of.order_id = bg.id AND bg.status = ".Order::STATUS_COMPLEALED."
                    LEFT JOIN booking_effectivenes AS be ON order_of.order_id = be.id AND be.status = ".Order::STATUS_COMPLEALED."
                    GROUP BY order_of.id 
                ) AS orders 
                ON users.id = orders.vendor_idss
                WHERE users.id = $id;
                ");
        foreach ($data as $row) {
            OrderFee::where('id', $row->id)->update(['status' => 1]);
        }


        flash(__('admin.messages.updated'))->success();
        return to_route('admin.accounts.suppliers');
    }
    public function suppliers(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('users')
            ->whereNotNull('users.email')
                ->where('users.type', User::TYPE_SUPPLIER)
                // Gifts subquery
                ->leftJoin(DB::raw("(
                SELECT vendor_id, COUNT(id) AS count_booking_gift, 
                    COALESCE(SUM(customer_total), 0) AS sum_booking_gift 
                FROM booking_gifts 
                WHERE status = " . Order::STATUS_COMPLEALED . " 
                GROUP BY vendor_id
            ) AS gifts"), 'users.id', '=', 'gifts.vendor_id')

                // Trips subquery
                ->leftJoin(DB::raw("(
                SELECT vendor_id, COUNT(id) AS count_booking_trib, 
                    COALESCE(SUM(customer_total), 0) AS sum_booking_trib 
                FROM booking_trips 
                WHERE status = " . Order::STATUS_COMPLEALED . " 
                GROUP BY vendor_id
            ) AS trips"), 'users.id', '=', 'trips.vendor_id')

                // Effectiveness subquery
                ->leftJoin(DB::raw("(
                SELECT vendor_id, COUNT(id) AS count_booking_effectivenes, 
                    COALESCE(SUM(customer_total), 0) AS sum_booking_effectivenes 
                FROM booking_effectivenes 
                WHERE status = " . Order::STATUS_COMPLEALED . " 
                GROUP BY vendor_id
            ) AS effects"), 'users.id', '=', 'effects.vendor_id')

                ->leftJoin(DB::raw("(
                    SELECT 
                        COALESCE(bt.vendor_id, bg.vendor_id, be.vendor_id) AS vendor_id,
                        COALESCE(SUM(order_of.tax_value), 0) AS total_tax_value,
                        COALESCE(SUM(order_of.payment_way_value), 0) AS total_payment_way_value,
                        COALESCE(SUM(order_of.admin_value), 0) AS total_admin_value,
                        COALESCE(SUM(order_of.admin_fee_value), 0) AS total_admin_fee_value,
                        COALESCE(SUM(CASE WHEN order_of.status = 0 THEN (order_of.tax_value + order_of.payment_way_value + order_of.admin_value + order_of.admin_fee_value) ELSE 0 END), 0) AS total_order_fees_0,
                        COALESCE(SUM(CASE WHEN order_of.status = 1 THEN (order_of.tax_value + order_of.payment_way_value + order_of.admin_value + order_of.admin_fee_value) ELSE 0 END), 0) AS total_order_fees_1
                    FROM order_fees AS order_of
                    LEFT JOIN booking_trips AS bt ON order_of.order_id = bt.id and bt.status=" . Order::STATUS_COMPLEALED . " 
                    LEFT JOIN booking_gifts AS bg ON order_of.order_id = bg.id and bg.status=" . Order::STATUS_COMPLEALED . " 
                    LEFT JOIN booking_effectivenes AS be ON order_of.order_id = be.id and be.status=" . Order::STATUS_COMPLEALED . " 
                    GROUP BY vendor_id
                ) AS orders"), 'users.id', '=', 'orders.vendor_id')
                // Select fields
                ->select(
                    'users.id',
                    DB::raw("CONCAT( users.name,' (P-', users.code, ')') AS name"),
                    DB::raw('COALESCE(gifts.count_booking_gift, 0) AS count_booking_gift'),
                    DB::raw('COALESCE(trips.count_booking_trib, 0) AS count_booking_trib'),
                    DB::raw('COALESCE(effects.count_booking_effectivenes, 0) AS count_booking_effectivenes'),
                    DB::raw('ROUND(COALESCE(gifts.sum_booking_gift, 0) + COALESCE(trips.sum_booking_trib, 0) + COALESCE(effects.sum_booking_effectivenes, 0),2) AS total_money'),
                    DB::raw('COALESCE(orders.total_tax_value, 0) AS total_tax_value'),
                    DB::raw('COALESCE(orders.total_payment_way_value, 0) AS total_payment_way_value'),
                    DB::raw('COALESCE(orders.total_admin_value, 0) AS total_admin_value'),
                    DB::raw('COALESCE(orders.total_admin_fee_value, 0) AS total_admin_fee_value'),
                    DB::raw('COALESCE(orders.total_order_fees_0, 0) AS total_order_fees_0'),
                    DB::raw('COALESCE(orders.total_order_fees_1, 0) AS total_order_fees_1')
                )
                ->groupBy('users.id', 'name', 'gifts.count_booking_gift', 'gifts.sum_booking_gift', 'trips.count_booking_trib', 'trips.sum_booking_trib', 'effects.count_booking_effectivenes', 'effects.sum_booking_effectivenes', 'orders.total_tax_value', 'orders.total_payment_way_value', 'orders.total_admin_value', 'orders.total_admin_fee_value', 'orders.total_order_fees_0', 'orders.total_order_fees_1')
                ->orderByDesc('total_order_fees_0');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('remain', function ($item) {
                    return max($item->total_order_fees_0 - $item->total_order_fees_1, 0);
                })
                ->rawColumns(['remain'])
                ->make(true);
        }

        return view('admin.pages.suppliers.payment_suppliers');
    }

    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        // try {
        // User fields
        $userData = $request->only([
            'name', 'phone', 'email', 'type', 'active', 'address', 'reset_code', 'password',
            'fcm_token', 'code', 'birthdate', 'joining_date_from', 'joining_date_to', 'city_id',
            'created_by', 'updated_by', 'last_login', 'can_pay_later', 'can_cancel', 'nationality',
            'ban_vendor', 'pay_on_deliver', 'status', 'temperory_email', 'bank_account', 'bank_name',
            'bank_iban', 'tax_number', 'temperory_phone'
        ]);

        // Handle password
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        } else {
            unset($userData['password']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $userData['image'] = $request->file('image')->store('users', 'public');
        }

        // Set type to supplier
        $userData['type'] = User::TYPE_SUPPLIER;

        $userData['code'] = $this->generateCode();
        $userData['temporary_email'] = $request->temporary_email;
        $userData['temporary_phone'] = $request->temporary_phone;

        $user = User::create($userData);

        // Supplier fields
        $supplierData = $request->only([
            'tour_guid', 'rerequest_reason', 'licence_image', 'profile', 'type', 'country_id', 'city_id', 'streat',
            'postal_code', 'national_id', 'user_id', 'description', 'short_description', 'url', 'profission_guide',
            'job', 'experience_info', 'languages', 'banck_name', 'banck_number', 'tax_number', 'place_summary',
            'place_content', 'expectations', 'general_name', 'nationality'
        ]);
        $supplierData['user_id'] = $user->id;

        // Handle licence_image upload
        if ($request->hasFile('licence_image')) {
            $supplierData['licence_image'] = $request->file('licence_image')->store('suppliers', 'public');
        }
        if ($request->hasFile('profile')) {
            $supplierData['profile'] = $request->file('profile')->store('suppliers', 'public');
        }
        if ($request->filled('languages')) {
            $supplierData['languages'] = json_encode($request->input('languages'));
        }



        $supplierData['user_id'] = $user->id;
        $supplier = Supplier::create($supplierData);

        if ($supplier) {
            foreach ($request->sub_category_id as $sub_category_id) {
                SupplierService::create([
                    'supplier_id' => $supplier->id,
                    'sub_category_id' => $sub_category_id
                ]);
            }
        }

        DB::commit();
        flash(__('suppliers.messages.created'))->success();
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     flash(__('admin.messages.error') . ' ' . $e->getMessage())->error();
        // }
        return to_route($this->route . '.index');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $userData = $request->only([
                'name', 'phone', 'email',  'active', 'address', 'reset_code', 'password',
                'fcm_token', 'code', 'birthdate', 'joining_date_from', 'joining_date_to', 'city_id',
                'created_by', 'updated_by', 'last_login', 'can_pay_later', 'can_cancel', 'nationality',
                'ban_vendor', 'pay_on_deliver', 'status', 'temperory_email', 'bank_account', 'bank_name',
                'bank_iban', 'tax_number', 'temperory_phone'
            ]);
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            } else {
                unset($userData['password']);
            }


            $image = $request->file('image');
            if ($image) {
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $request->image->move(public_path('storage/users'), $fileName);
                $userData['image'] = $fileName;
            }


            $user->update($userData);
            $supplierData = $request->only([
                'tour_guid', 'rerequest_reason', 'licence_image', 'profile', 'type', 'country_id', 'city_id', 'streat',
                'postal_code', 'national_id', 'user_id', 'description', 'short_description', 'url', 'profission_guide',
                'job', 'experience_info', 'languages', 'banck_name', 'banck_number', 'tax_number', 'place_summary',
                'place_content', 'expectations', 'general_name', 'nationality'
            ]);
            $supplierData['user_id'] = $user->id;
            if ($request->hasFile('licence_image')) {
                $supplierData['licence_image'] = $request->file('licence_image')->store('suppliers', 'public');
            }

            $supplier = Supplier::where('user_id', $user->id)->first();
            if ($supplier) {
                $supplier->update($supplierData);
            } else {
                Supplier::create($supplierData);
            }

            DB::commit();
            flash(__('suppliers.messages.updated'))->success();
        } catch (\Exception $e) {
            DB::rollBack();
            flash(__('admin.messages.error') . ' ' . $e->getMessage())->error();
        }
        return to_route($this->route . '.index');
    }
    private function generateCode()
    {

        $code = 2500001;
        $user = User::where('type', User::TYPE_SUPPLIER)->whereNotNull('code')->orderby('id', 'desc')->first();
        if ($user && $user->code != null) {
            return intval($user->code) + 1;
        }
        return $code;
    }
}
