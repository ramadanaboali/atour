<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddRequest;
use App\Models\Add;
use App\Models\User;
use App\Services\OneSignalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class AddController extends Controller
{
    private $viewIndex  = 'admin.pages.adds.index';
    private $viewEdit   = 'admin.pages.adds.create_edit';
    private $viewShow   = 'admin.pages.adds.show';
    private $route      = 'admin.adds';

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
        $item = Add::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Add::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Add::findOrFail($id);
        if ($item->delete()) {
            flash(__('adds.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(AddRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('adds.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
       $data = Add::distinct()
                ->where('active',true)
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


    public function update(AddRequest $request, $id): RedirectResponse
    {
        if ($this->processForm($request, $id)) {
            flash(__('adds.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Add|null
    {
        $item = $id == null ? new Add() : Add::find($id);
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

            if ($request->hasFile('image')) {
                $image= $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $item->image->move(public_path('storage/adds'), $fileName);
                $item->image = $fileName;
                $item->save();
            }
            if($item->send_notification==1){
                $users=User::where('type',User::TYPE_CLIENT)->get();
                foreach($users as $user){
                   OneSignalService::sendToUser($user->id,$item->title,$item->description);
                }
            }

            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Add::select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item->photo . '" height="100px" width="100px">';
        })
        ->editColumn('active', function ($item) {
            return $item->active==1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>':'<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })
        ->editColumn('date', function ($item) {
            return $item->start_date.' - '.$item->end_date;
        })
        ->filterColumn('title', function ($query, $keyword) {
                 if(App::isLocale('en')) {
                     return $query->where('title_en', 'like', '%'.$keyword.'%');
                 } else {
                     return $query->where('title_ar', 'like', '%'.$keyword.'%');
                 }
             })
        ->filterColumn('description', function ($query, $keyword) {
                 if(App::isLocale('en')) {
                     return $query->where('description_en', 'like', '%'.$keyword.'%');
                 } else {
                     return $query->where('description_ar', 'like', '%'.$keyword.'%');
                 }
             })
        ->rawColumns(['photo','active','date'])
        ->make(true);
    }
}
