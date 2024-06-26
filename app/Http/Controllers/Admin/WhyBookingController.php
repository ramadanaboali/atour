<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhyBookingRequest;
use App\Models\WhyBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class WhyBookingController extends Controller
{
    private $viewIndex  = 'admin.pages.why_bookings.index';
    private $viewEdit   = 'admin.pages.why_bookings.create_edit';
    private $viewShow   = 'admin.pages.why_bookings.show';
    private $route      = 'admin.why_bookings';

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
        $item = WhyBooking::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = WhyBooking::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = WhyBooking::findOrFail($id);
        if ($item->delete()) {
            flash(__('why_bookings.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(WhyBookingRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('why_bookings.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
       $data = WhyBooking::distinct()
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


    public function update(WhyBookingRequest $request, $id): RedirectResponse
    {
        $item = WhyBooking::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('why_bookings.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): WhyBooking|null
    {
        $item = $id == null ? new WhyBooking() : WhyBooking::find($id);
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
                $item->image->move(public_path('storage/why_bookings'), $fileName);
                $item->image = $fileName;
                $item->save();
            }
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = WhyBooking::select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item->photo . '" height="100px" width="100px">';
        })
        ->editColumn('active', function ($item) {
            return $item->active==1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>':'<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })
        ->editColumn('created_at', function ($item) {
            return $item->created_at?->format('Y-m-d H:i');
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
        ->rawColumns(['photo','active'])
        ->make(true);
    }
}
