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
                         if (App::isLocale('en')) {
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

    public function changeStatus($id): RedirectResponse
    {
        $item = Offer::findOrFail($id);
        Offer::where('id', '!=', $id)->update(['active' => 0]);
        $item->active = 1;
        $item->save();
        flash(__('offers.messages.updated'))->success();
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Offer|null
    {
        $item = $id == null ? new Offer() : Offer::find($id);
        $data = $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if ($request->filled('active')) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }
        if ($request->type=="trip") {
            $item->trip_id = $request->trip_id;
            $item->gift_id = null;
            $item->effectivenes_id = null;
        }
        if ($request->type=="gift") {
            $item->gift_id = $request->gift_id;
            $item->trip_id = null;
            $item->effectivenes_id = null;
        }
        if ($request->type=="effectivenes") {
            $item->effectivenes_id = $request->effectivenes_id;
            $item->trip_id = null;
            $item->gift_id = null;
        }

        if ($item->save()) {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $item->image->move(public_path('storage/offers'), $fileName);
                $item->image = $fileName;
                $item->save();
            }
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Offer::with(['trip','effectivenes','gift','vendor'])
        ->leftJoin('users', 'users.id', 'offers.vendor_id')
        ->leftJoin('trips', 'offers.trip_id', 'trips.id')
        ->leftJoin('gifts', 'offers.gift_id', 'gifts.id')
        ->leftJoin('effectivenes', 'offers.effectivenes_id', 'effectivenes.id')
        ->where(function ($query) use ($request) {
            if ($request->filled('name')) {
                $query->where('users.name', 'like', '%'.$request->name.'%');
            }
            if ($request->filled('email')) {
                $query->where('users.email', $request->email);
            }
            if ($request->filled('phone')) {
                $query->where('users.phone', $request->phone);
            }
            if ($request->filled('phone')) {
                $query->where('users.phone', $request->phone);
            }

            if ($request->filled('active')) {
                $query->where('offers.active', $request->active);
            }


        })->groupBy('offers.id')->select('offers.*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('supplier_name', function ($item) {
            return $item->vendor?->name;
        })
        ->addColumn('typeText', function ($item) {
            return __('offers.types.'.$item->type);
        })
        ->addColumn('supplier_phone', function ($item) {
            return $item->vendor?->phone;
        })
        ->addColumn('supplier_email', function ($item) {
            return $item->vendor?->email;
        })
        ->addColumn('model', function ($item) {
            return $item->{$item->type}?->title;
        })


        ->editColumn('active', function ($item) {
            return $item->active == 1 ? '<button type="button" class="btn btn-sm btn-outline-success me-1 waves-effect active_offer" data-url="'.route('admin.offers.changeStatus',['id'=>$item->id]).'"><i data-feather="check" ></i></button>' : '<button type="button" class="btn btn-sm btn-outline-danger me-1 waves-effect active_offer" data-url="'.route('admin.offers.changeStatus',['id'=>$item->id]).'"><i data-feather="x" ></i></button>';
        })
        ->filterColumn('title', function ($query, $keyword) {
            if (App::isLocale('en')) {
                return $query->where('title_en', 'like', '%'.$keyword.'%');
            } else {
                return $query->where('title_ar', 'like', '%'.$keyword.'%');
            }
        })
        ->rawColumns(['typeText','model','active','supplier_name','supplier_phone','supplier_email'])
        ->make(true);
    }
}
