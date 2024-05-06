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
       $data = Supplier::distinct()
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

    public function list(Request $request): JsonResponse
    {
        $data = User::leftJoin('suppliers','suppliers.user_id','users.id')
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
        })->where('users.type',User::TYPE_SUPPLIER)->select(['users.*']);
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item?->photo . '" height="100px" width="100px">';
        })
        ->editColumn('active', function ($item) {
            return $item?->active==1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>':'<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })


        ->rawColumns(['photo','active'])
        ->make(true);
    }
}
