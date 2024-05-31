<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class OrderController extends Controller
{
    private $viewIndex  = 'admin.pages.orders.index';
    private $viewEdit   = 'admin.pages.orders.create_edit';
    private $viewShow   = 'admin.pages.orders.show';
    private $route      = 'admin.orders';

    public function index(Request $request): View
    {
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
                    if(App::isLocale('en')) {
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
        $data= $request->except(['_token', '_method']);

        $item = $item->fill($data);
            if($request->filled('active')){
                $item->active = 1;
            }else{
                $item->active = 0;
            }
        if ($item->save()) {

            if ($request->hasFile('image')) {
                $image= $request->file('image');
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
        $data = Order::where(function ($query) use ($request) {
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        })->select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item->photo . '" height="100px" width="100px">';
        })
        ->addColumn('vendor', function ($item) {
            return '';
        })
        ->addColumn('members', function ($item) {
            return '';
        })
        ->addColumn('booking_date', function ($item) {
            return '';
        })
        ->addColumn('meeting_place', function ($item) {
            return '';
        })
        ->rawColumns(['active','members','booking_date','meeting_place'])
        ->make(true);
    }
}
