<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class ClientController extends Controller
{
    private $viewIndex  = 'admin.pages.clients.index';
    private $viewEdit   = 'admin.pages.clients.create_edit';
    private $viewShow   = 'admin.pages.clients.show';
    private $route      = 'admin.clients';

    public function index(Request $request): View
    {

        return view($this->viewIndex, get_defined_vars());
    }
    public function newClients(Request $request): View
    {
        $status = "pendding";
        return view($this->viewIndex, get_defined_vars());
    }
    public function status($id)
    {
        $item=Client::findOrFail($id);
        if($item->active == 0){
            $item->active = 1;
            $item->status = "accepted";

        }else{
            $item->active = 0;
        }
        $item->save();

        flash(__('clients.messages.updated'))->success();

        return back();
    }
    public function currentClients(Request $request): View
    {

        $status = "accepted";
        return view($this->viewIndex, get_defined_vars());
    }

    public function create(): View
    {
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = Client::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Client::findOrFail($id);
        $compleated_orders=Order::where("user_id", $item->id)->where('status',Order::STATUS_COMPLEALED)->get();
        $pendding_orders=Order::where("user_id", $item->id)->where('status',Order::STATUS_PENDING)->get();       
        $status = [Order::STATUS_COMPLEALED,Order::STATUS_PENDING,Order::STATUS_ACCEPTED,Order::STATUS_REJECTED,Order::STATUS_ONPROGRESS,Order::STATUS_COMPLEALED,Order::STATUS_CANCELED,Order::STATUS_WITHDRWAL];

        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Client::findOrFail($id);
        if ($item->delete()) {
            flash(__('clients.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('clients.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
        $data = Client::distinct()
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


    public function update(ClientRequest $request, $id): RedirectResponse
    {
        $item = Client::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('clients.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Client|null
    {
        $item = $id == null ? new Client() : Client::find($id);
        $data = $request->except(['_token', '_method']);
        $item = $item->fill($data);
        if($request->filled('active')) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }
        $item->type = User::TYPE_CLIENT;

        if ($item->save()) {

            if ($request->filled('password')) {
                $item->password = Hash::make($request->password);
                $item->save();
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $item->image->move(public_path('storage/users'), $fileName);
                $item->image = $fileName;
                $item->save();
            }
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Client::whereNotNull('email')->where(function ($query) use ($request) {
            if ($request->filled('name')) {
                $query->where('name', 'like', '%'. $request->name .'%');
            }
            if ($request->filled('email')) {
                $query->where('email', $request->email);
            }
            if ($request->filled('phone')) {
                $query->where('phone', $request->phone);
            }
            if ($request->filled('birthdate')) {
                $query->where('birthdate', $request->birthdate);
            }
            if ($request->filled('city_id')) {
                $query->where('city_id', $request->city_id);
            }
            if ($request->filled('active')) {
                $query->where('active', $request->active);
            }
            if ($request->filled('joining_date')) {
                $query->where('joining_date_from', $request->joining_date);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        })->where('type',User::TYPE_CLIENT)->select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('photo', function ($item) {
                return $item->photo ? '<img src="' . $item->photo . '" height="100px" width="100px">' : '';
            })
            ->addColumn('joining_date', function ($item) {
                return $item->joining_date_from . ' - ' . $item->joining_date_to;
            })
            ->addColumn('order_count', function ($item) {
                return 0;
            })
            ->editColumn('active', function ($item) {
            return $item->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect " ><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect " ><i data-feather="x" ></i></button>';
        })

        ->rawColumns(['photo','active','joining_date'])
        ->make(true);
    }
}
