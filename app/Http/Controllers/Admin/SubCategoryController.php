<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class SubCategoryController extends Controller
{
    private $viewIndex  = 'admin.pages.sub_categories.index';
    private $viewEdit   = 'admin.pages.sub_categories.create_edit';
    private $viewShow   = 'admin.pages.sub_categories.show';
    private $route      = 'admin.sub_categories';

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
        $item = SubCategory::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = SubCategory::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = SubCategory::findOrFail($id);
        if ($item->delete()) {
            flash(__('sub_categories.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(SubCategoryRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('sub_categories.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
       $data = SubCategory::distinct()
                ->where('active',true)
                ->where(function ($query) use ($request) {
                if ($request->filled('q')) {
                    if(App::isLocale('en')) {
                        return $query->where('title_en', 'like', '%'.$request->q.'%');
                    } else {
                        return $query->where('title_ar', 'like', '%'.$request->q.'%');
                    }
                }
                if ($request->filled('item_id') && !empty ($request->item_id)) {
                    $query->where('id', '!=', $request->item_id);
                }
                if ($request->filled('category') && !empty ($request->category)) {
                    $query->where('category', $request->category);
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


    public function update(SubCategoryRequest $request, $id): RedirectResponse
    {
        $item = SubCategory::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('sub_categories.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): SubCategory|null
    {
        $item = $id == null ? new SubCategory() : SubCategory::find($id);
        $data= $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if($request->filled('active')){
            $item->active = 1;
        }else{
            $item->active = 0;
        }
        if ($id == null) {
            $item->created_by = auth()->user()->id;
        }else{
            $item->updated_by = auth()->user()->id;
        }
        if ($item->save()) {


            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = SubCategory::select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('categoryText', function ($item) {
            return __('sub_categories.categories.'.$item->category);
        })

        ->editColumn('active', function ($item) {
            return $item->active==1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>':'<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })
        ->filterColumn('title', function ($query, $keyword) {
                 if(App::isLocale('en')) {
                     return $query->where('title_en', 'like', '%'.$keyword.'%');
                 } else {
                     return $query->where('title_ar', 'like', '%'.$keyword.'%');
                 }
             })
        ->rawColumns(['category','active','subCategory'])
        ->make(true);
    }
}
