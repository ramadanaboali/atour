<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\BookingEffectivene;
use App\Models\BookingGift;
use App\Models\BookingTrip;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    private $viewIndex  = 'admin.pages.orders.index';
    private $viewEdit   = 'admin.pages.orders.create_edit';
    private $viewShow   = 'admin.pages.orders.show';
    private $route      = 'admin.orders';

    public function index(Request $request): View
    {
        $title = __('admin.new_orders');

        $status = [Order::STATUS_COMPLEALED];

        return view($this->viewIndex, get_defined_vars());
    }
    public function newOrders(Request $request): View
    {
        $title = __('admin.new_orders');

        $status = [Order::STATUS_PENDING];

        return view($this->viewIndex, get_defined_vars());
    }
    public function currentOrders(Request $request): View
    {
        $status = [Order::STATUS_ACCEPTED,Order::STATUS_ONPROGRESS];
        $title = __('admin.current_orders');
        return view($this->viewIndex, get_defined_vars());
    }
    public function canceledOrders(Request $request): View
    {
        $status = [Order::STATUS_CANCELED,Order::STATUS_REJECTED];
        $title = __('admin.canceled_orders');
        return view($this->viewIndex, get_defined_vars());
    }

    public function create(): View
    {
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = Order::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Order::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Order::findOrFail($id);
        if ($item->delete()) {
            flash(__('orders.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(OrderRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('orders.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
        $data = Order::distinct()
                 ->where(function ($query) use ($request) {
                     if ($request->filled('q')) {
                         if (App::isLocale('en')) {
                             return $query->where('title_en', 'like', '%'.$request->q.'%');
                         } else {
                             return $query->where('title_ar', 'like', '%'.$request->q.'%');
                         }
                     }
                 })->select('id', 'title_en', 'title_ar')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('category.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }


    public function update(OrderRequest $request, $id): RedirectResponse
    {
        $item = Order::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('orders.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Order|null
    {
        $item = $id == null ? new Order() : Order::find($id);
        $data = $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if ($request->filled('active')) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }
        if ($item->save()) {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $item->image->move(public_path('storage/orders'), $fileName);
                $item->image = $fileName;
                $item->save();
            }
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {

        $data =  BookingGift::with(['user','vendor'])
        ->whereIn('status', (array) $request->status)
         ->where(function ($query) use ($request) {
             if ($request->filled('user_id')) {
                 $query->where('user_id', $request->user_id);
             }
         })
        ->select('id', 'admin_value', 'status', 'total','customer_total', 'created_at', DB::raw("'BookingGift' as source"))
        ->union(
            BookingTrip::with(['user','vendor'])
            ->where(function ($query) use ($request) {
                if ($request->filled('user_id')) {
                    $query->where('user_id', $request->user_id);
                }
            })
                ->whereIn('status', (array) $request->status)
                ->select('id', 'admin_value', 'status', 'total','customer_total', 'created_at', DB::raw("'BookingTrip' as source"))
        )
        ->union(
            BookingEffectivene::with(['user','vendor'])
             ->where(function ($query) use ($request) {
                 if ($request->filled('user_id')) {
                     $query->where('user_id', $request->user_id);
                 }
             })
                ->whereIn('status', (array) $request->status)
                ->select('id', 'admin_value', 'status', 'total','customer_total', 'created_at', DB::raw("'BookingEffectivene' as source"))
        )
        ->orderBy('created_at', 'desc')
        ->get();

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
            return __('admin.'.$item->source);
        })
        ->editColumn('created_at', function ($item) {
            return $item->created_at?->format('Y-m-d H:i');
        })


        ->rawColumns(['source','created_at','client','vendor'])
        ->make(true);
    }
    public function accountants(Request $request)
    {
        $total = Order::where('status', Order::STATUS_COMPLEALED)->sum('total');
        return view('admin.pages.orders.accountants', ['total' => $total]);
    }
}
