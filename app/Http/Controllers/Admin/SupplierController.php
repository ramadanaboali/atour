<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class SupplierController extends Controller
{
    private $viewIndex  = 'admin.pages.suppliers.index';
    private $viewEdit   = 'admin.pages.suppliers.create_edit';
    private $viewShow   = 'admin.pages.suppliers.show';
    private $route      = 'admin.suppliers';

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
        $item = User::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Supplier::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Supplier::findOrFail($id);
        if ($item->delete()) {
            flash(__('suppliers.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('suppliers.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
       $data = Supplier::distinct()
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


    public function update(SupplierRequest $request, $id): RedirectResponse
    {
        $item = User::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('suppliers.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Supplier|null
    {
        $item = $id == null ? new User() : User::find($id);
        $data= $request->except(['_token', '_method']);

        $supplierData = [
            'profission_guide'=> $request->profission_guide,
                'url'=> $request->url,
                'job'=> $request->job,
                'language'=> $request->language,
                'banck_name'=> $request->banck_name,
                'banck_number'=> $request->banck_number,
                'experience_info'=> $request->experience_info,
                'bio'=> $request->bio,
                'description'=> $request->description,
                'streat'=> $request->streat,
                'postal_code'=> $request->postal_code,
                'active'=> $request->active,
                'city_id'=> $request->city_id,
                'country_id'=> $request->country_id,
                'user_id'=> $request->user_id
            ];
            if($request->filled('active')){
                $active = 1;
            }else{
                $active = 0;
            }
            $userData = [
                'name'=>$request->name,
                'phone'=>$request->phone,
                'email'=>$request->email,
                'type'=>User::TYPE_SUPPLIER,
                'active'=>$active,
                'address'=>$request->address,
                'city_id'=>$request->city_id,
            ];
            $item = $item->fill($userData);

        if ($item->save()) {
            if ($request->hasFile('image')) {
                $image= $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $request->image->move(public_path('storage/suppliers'), $fileName);
                $item->image = $fileName;
                $item->save();

            }

            if ($request->filled('password')) {
                $item->password = Hash::make($request->password);
                $item->save();
            }

            $supplierData['user_id'] = $item->id;
           $supplier= $id == null ? new Supplier() : Supplier::where('user_id',$id)->first();
           $supplier->fill($supplierData);
           $supplier->save();
            return $supplier;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = User::with(['supplier'])->select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item->user?->photo . '" height="100px" width="100px">';
        })
        ->editColumn('active', function ($item) {
            return $item->user?->active==1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>':'<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })


        ->rawColumns(['photo','active'])
        ->make(true);
    }
}
