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
        $data = $request->except(['_token', '_method']);
        $item = $item->fill($data);
        if ($item->save()) {
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Requirement::select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()

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
            ->make(true);
    }
}
