<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnboardingRequest;
use App\Models\Onboarding;
use App\Services\General\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class OnboardingController extends Controller
{
    private $viewIndex  = 'admin.pages.onboardings.index';
    private $viewEdit   = 'admin.pages.onboardings.create_edit';
    private $viewShow   = 'admin.pages.onboardings.show';
    private $route      = 'admin.onboardings';
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
        $item = Onboarding::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Onboarding::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Onboarding::findOrFail($id);
        if ($item->delete()) {
            flash(__('onboardings.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(OnboardingRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('onboardings.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function update(OnboardingRequest $request, $id): RedirectResponse
    {
        $item = Onboarding::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('onboardings.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Onboarding|null
    {
        $item = $id == null ? new Onboarding() : Onboarding::find($id);
        $data = $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if ($item->save()) {
            $folder_path = "onboardings";
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
        $data = Onboarding::select('*');
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
}
