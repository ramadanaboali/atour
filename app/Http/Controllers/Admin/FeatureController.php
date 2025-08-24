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

            if ($request->has('translations') && is_array($request->translations)) {
                $item->translations()->delete();
                $item->translations()->createMany($request->translations);
            }

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
        $data = Feature::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('features.*');

        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('title', function ($item) {
                return $item->translations->first()->title ?? '';
            })
            ->addIndexColumn()
            ->addColumn('photo', function ($item) {
                return '<img src="' . $item->photo . '" height="100px" width="100px">';
            })
             
            ->rawColumns(['photo'])
            ->make(true);
    }
    public function select(Request $request): JsonResponse|string
    {
        $items = Feature::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('features.id')->get();
        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->translations->first()->title ?? '',
            ];
        });
        return response()->json($data);
    }

}
