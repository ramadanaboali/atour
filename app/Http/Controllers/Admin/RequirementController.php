<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequirementRequest;
use App\Models\Requirement;
use App\Services\General\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class RequirementController extends Controller
{
    private $viewIndex  = 'admin.pages.requirements.index';
    private $viewEdit   = 'admin.pages.requirements.create_edit';
    private $viewShow   = 'admin.pages.requirements.show';
    private $route      = 'admin.requirements';
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
        $item = Requirement::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Requirement::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Requirement::findOrFail($id);
        if ($item->delete()) {
            flash(__('requirements.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(RequirementRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('requirements.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function update(RequirementRequest $request, $id): RedirectResponse
    {
        $item = Requirement::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('requirements.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Requirement|null
    {
        $item = $id == null ? new Requirement() : Requirement::find($id);
        $data = $request->except(['_token', '_method','translations']);
        $item = $item->fill($data);
        if ($item->save()) {

            if ($request->has('translations') && is_array($request->translations)) {
                $item->translations()->delete();
                $item->translations()->createMany($request->translations);
            }

            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Requirement::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('requirements.*');

        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('title', function ($item) {
                return $item->translations->first()->title ?? '';
            })
             
            ->make(true);
    }
    public function select(Request $request): JsonResponse|string
    {
        $items = Requirement::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('requirements.id')->get();
        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->translations->first()->title ?? '',
            ];
        });
        return response()->json($data);
    }

}
