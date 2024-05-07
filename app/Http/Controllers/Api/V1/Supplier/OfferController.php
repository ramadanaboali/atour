<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\OfferRequest;
use App\Models\Offer;
use App\Services\General\StorageService;
use App\Services\Vendor\OfferService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use function response;

class OfferController extends Controller
{
    protected OfferService $service;
    protected StorageService $storageService;
    public function __construct(OfferService $service,StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;

    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new Offer();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->service->whereOptions($input, $columns);

        }
        $data = $this->service->Paginate($input, $wheres);
        //$meta = $this->service->Meta($data,$input);
        return response()->apiSuccess($data);
    }

    public function show($id){
        return response()->apiSuccess($this->service->get($id));
    }

    public function store(OfferRequest $request)
    {
        $data = $request->except(['service_id']);
        $data['vendor_id'] = auth()->user()->model_id;
        DB::beginTransaction();
        $offer=$this->service->store($data);
        if ($offer && $request->service_id) {
            $offer->services()->attach($request->service_id);
        }
        DB::commit();
        return response()->apiSuccess($offer);
    }

    public function update(OfferRequest $request, Offer $offer)
    {
        $data = $request->except(['service_id']);
        if ($offer && $request->service_id) {
            $offer->services()->detach();
            $offer->services()->attach($request->service_id);
        }
        return response()->apiSuccess($this->service->update($data,$offer));
    }
    public function delete(Offer $offer)
    {
        return response()->apiSuccess($this->service->delete($offer));
    }

}
