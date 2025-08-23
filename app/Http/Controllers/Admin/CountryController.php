<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class CountryController extends Controller
{
    private $viewIndex  = 'admin.pages.countries.index';
    private $viewEdit   = 'admin.pages.countries.create_edit';
    private $viewShow   = 'admin.pages.countries.show';
    private $route      = 'admin.countries';

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
        $item = Country::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Country::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Country::findOrFail($id);
        if ($item->delete()) {
            flash(__('countries.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(CountryRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('countries.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
        $countries = Country::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('countries.id')->get();
        $data = $countries->map(function ($country) {
            return [
                'id' => $country->id,
                'text' => $country->translations->first()->title ?? '',
            ];
        });
        return response()->json($data);
    }


    public function update(CountryRequest $request, $id): RedirectResponse
    {
        $item = Country::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('countries.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Country|null
    {
        $item = $id == null ? new Country() : Country::find($id);
        $data = $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if ($request->filled('active')) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }
        if ($item->save()) {

            $id != null ? $item->translations()->delete() : null; // مسح القديم

            foreach ($request->translations as $tr) {
                $item->translations()->create($tr);
            }

            return $item;
        }
        return null;
    }


    public function list(Request $request): JsonResponse
    {
        $data = Country::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('countries.*');

        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('title', function ($item) {
                return $item->translations->first()->title ?? '';
            })
            ->editColumn('active', function ($item) {
                return $item->active == 1
                    ? '<button class="btn btn-sm btn-outline-primary me-1 waves-effect"><i data-feather="check" ></i></button>'
                    : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
            })
            ->rawColumns(['active'])
            ->make(true);
    }
}
