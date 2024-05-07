<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class JobController extends Controller
{
    private $viewIndex  = 'admin.pages.jobs.index';
    private $viewEdit   = 'admin.pages.jobs.create_edit';
    private $viewShow   = 'admin.pages.jobs.show';
    private $route      = 'admin.jobs';

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
        $item = Job::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Job::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Job::findOrFail($id);
        if ($item->delete()) {
            flash(__('jobs.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(JobRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('jobs.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
       $data = Job::distinct()
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


    public function update(JobRequest $request, $id): RedirectResponse
    {
        $item = Job::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('jobs.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Job|null
    {
        $item = $id == null ? new Job() : Job::find($id);
        $data= $request->except(['_token', '_method']);

        $item = $item->fill($data);
            if($request->filled('active')){
                $item->active = 1;
            }else{
                $item->active = 0;
            }
        if ($item->save()) {

            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Job::select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('department', function ($item) {
            return  $item->department?->title;

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
        ->rawColumns(['department','active'])
        ->make(true);
    }
}
