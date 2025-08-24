<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class DepartmentController extends Controller
{
    private $viewIndex  = 'admin.pages.departments.index';
    private $viewEdit   = 'admin.pages.departments.create_edit';
    private $viewShow   = 'admin.pages.departments.show';
    private $route      = 'admin.departments';

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
        $item = Department::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Department::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Department::findOrFail($id);
        if ($item->delete()) {
            flash(__('departments.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(DepartmentRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('departments.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
     public function select(Request $request): JsonResponse|string
    {
        $departments = Department::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('departments.id')->get();
        $data = $departments->map(function ($department) {
            return [
                'id' => $department->id,
                'text' => $department->translations->first()->title ?? '',
            ];
        });
        return response()->json($data);
    }


    public function update(DepartmentRequest $request, $id): RedirectResponse
    {
        $item = Department::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('departments.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Department|null
    {
        $item = $id == null ? new Department() : Department::find($id);
        $data = $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if ($request->filled('active')) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }
        if ($id == null) {
            $item->created_by = auth()->user()->id;
        } else {
            $item->updated_by = auth()->user()->id;
        }
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
        $data = Department::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('departments.*');

        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('title', function ($item) {
                return $item->translations->first()->title ?? '';
            })
        ->editColumn('active', function ($item) {
            return $item->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })
        
        ->rawColumns(['active'])
        ->make(true);
    }
}
