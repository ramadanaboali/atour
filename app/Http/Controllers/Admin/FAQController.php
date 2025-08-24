<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FAQRequest;
use App\Models\FAQ;
use App\Services\General\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class FAQController extends Controller
{
    private $viewIndex  = 'admin.pages.faqs.index';
    private $viewEdit   = 'admin.pages.faqs.create_edit';
    private $viewShow   = 'admin.pages.faqs.show';
    private $route      = 'admin.faqs';
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
        $item = FAQ::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = FAQ::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = FAQ::findOrFail($id);
        if ($item->delete()) {
            flash(__('faqs.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(FAQRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('faqs.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function update(FAQRequest $request, $id): RedirectResponse
    {
        $item = FAQ::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('faqs.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): FAQ|null
    {
        $item = $id == null ? new FAQ() : FAQ::find($id);
        $data = $request->except(['_token', '_method']);
        // dd($data);
        $item = $item->fill([]);
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
        $data = FAQ::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('f_a_q_s.*');

        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('question', function ($item) {
                return $item->translations->first()->question ?? '';
            })

            ->make(true);
    }
}
