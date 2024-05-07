<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\OfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class OfferController extends Controller
{
    private $viewIndex  = 'admin.pages.offers.index';
    private $viewEdit   = 'admin.pages.offers.create_edit';
    private $viewShow   = 'admin.pages.offers.show';
    private $route      = 'admin.offers';

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
        $item = Offer::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Offer::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Offer::findOrFail($id);
        if ($item->delete()) {
            flash(__('offers.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(OfferRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('offers.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
       $data = Offer::distinct()
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


    public function update(OfferRequest $request, $id): RedirectResponse
    {
        $item = Offer::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('offers.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Offer|null
    {
        $item = $id == null ? new Offer() : Offer::find($id);
        $data= $request->except(['_token', '_method']);

        $item = $item->fill($data);
            if($request->filled('active')){
                $item->active = 1;
            }else{
                $item->active = 0;
            }
        if ($item->save()) {

            if ($request->hasFile('image')) {
                $image= $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $item->image->move(public_path('storage/offers'), $fileName);
                $item->image = $fileName;
                $item->save();
            }

            $item->categories()->detach();
            $item->categories()->attach($request->services);


                $item->categories()->sync($request->services);
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Offer::with(['supplier','categories'])
        ->leftJoin('users','users.id','offers.user_id')
        ->leftJoin('suppliers','users.id','suppliers.user_id')
        ->leftJoin('offer_services','offers.id','offer_services.offer_id')
        ->where(function($query)use($request){
            if($request->filled('name')){
                $query->where('users.name', 'like','%'.$request->name.'%');
            }
            if($request->filled('email')){
                $query->where('users.email',$request->email);
            }
            if($request->filled('phone')){
                $query->where('users.phone',$request->phone);
            }
            if($request->filled('phone')){
                $query->where('users.phone',$request->phone);
            }
            if($request->filled('start_date')){
                $query->where('offers.start_date',$request->start_date);
            }
            if($request->filled('end_date')){
                $query->where('offers.end_date',$request->end_date);
            }
            if($request->filled('discount')){
                $query->where('offers.discount',$request->discount);
            }

            if($request->filled('active')){
                $query->where('users.active',$request->active);
            }
            if($request->filled('city_id')){
                $query->where('suppliers.city_id',$request->city_id);
            }
            if($request->filled('services')){
                $query->whereIn('offer_services.category_id',$request->services);
            }
        })->groupBy('offers.id')->select('offers.*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('supplier_name', function ($item) {
            return $item->supplier?->name;
        })
        ->addColumn('supplier_phone', function ($item) {
            return $item->supplier?->phone;
        })
        ->addColumn('supplier_email', function ($item) {
            return $item->supplier?->email;
        })
        ->addColumn('benfits_numbers', function ($item) {
            return $item->benfits_numbers();
        })
        ->addColumn('services', function ($item) {
            return $item->categories()->pluck('title_'.app()->getLocale())->toArray();
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
        ->rawColumns(['photo','active','supplier_name','supplier_phone','supplier_email','benfits_numbers','services'])
        ->make(true);
    }
}
