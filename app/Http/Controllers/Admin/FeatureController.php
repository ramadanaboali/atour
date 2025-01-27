<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeatureRequest;
use App\Models\Feature;
use App\Services\General\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class FeatureController extends Controller
{
    private $viewIndex  = 'admin.pages.features.index';
    private $viewEdit   = 'admin.pages.features.create_edit';
    private $viewShow   = 'admin.pages.features.show';
    private $route      = 'admin.features';
    protected StorageService $storageService;

    public function __construct(StorageService $storageService)
    {

        $this->storageService = $storageService;

    }
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
        $item = Feature::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Feature::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Feature::findOrFail($id);
        if ($item->delete()) {
            flash(__('features.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(FeatureRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('features.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function update(FeatureRequest $request, $id): RedirectResponse
    {
        $item = Feature::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('features.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Feature|null
    {
        $item = $id == null ? new Feature() : Feature::find($id);
        $data = $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if ($item->save()) {
            $folder_path = "features";
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $item->image  = $this->storageService->storeFile($file, $folder_path);
                $item->save();
            }


            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Feature::select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('photo', function ($item) {
                return '<img src="' . $item->photo . '" height="100px" width="100px">';
            })
             ->filterColumn('title', function ($query, $keyword) {
                 if (App::isLocale('en')) {
                     return $query->where('title_en', 'like', '%'.$keyword.'%');
                 } else {
                     return $query->where('title_ar', 'like', '%'.$keyword.'%');
                 }
             })
             ->filterColumn('description', function ($query, $keyword) {
                 if (App::isLocale('en')) {
                     return $query->where('description_en', 'like', '%'.$keyword.'%');
                 } else {
                     return $query->where('description_ar', 'like', '%'.$keyword.'%');
                 }
             })
            ->rawColumns(['photo'])
            ->make(true);
    }
    public function select(Request $request): JsonResponse|string
    {
        $data = Feature::distinct()
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

}
