<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
        $item = Supplier::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }


     public function status($id)
    {
        $item=User::findOrFail($id);
        if($item->active == 0){
            $item->active = 1;
            $item->status = "accepted";

        }else{
            $item->active = 0;
        }
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

                $query->where('users.created_at','>=', $request->created_at.' 00:00:00');
            }else{
                $query->where('users.created_at','<', $request->created_at.' 00:00:00');

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
}
