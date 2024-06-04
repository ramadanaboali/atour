<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\TaxInvoiceRequest;
use App\Models\TaxInvoice;
use App\Services\Vendor\TaxInvoiceService;
use Illuminate\Support\Facades\Schema;
use function response;

class TaxInvoiceController extends Controller
{
    protected TaxInvoiceService $service;

    public function __construct(TaxInvoiceService $service)
    {
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new TaxInvoice();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->service->whereOptions($input, $columns);
        }
        $data = $this->service->Paginate($input, $wheres);
        return response()->apiSuccess($data);
    }

    public function show($id){
        return response()->apiSuccess($this->service->get($id));
    }

    public function store(TaxInvoiceRequest $request)
    {
        $data = $request->all();
        return response()->apiSuccess($this->service->store($data));
    }

    public function update(TaxInvoiceRequest $request, TaxInvoice $tax_invoice)
    {
        $data = $request->all();
        return response()->apiSuccess($this->service->update($data,$tax_invoice));
    }
    public function delete(TaxInvoice $tax_invoice)
    {
        return response()->apiSuccess($this->service->delete($tax_invoice));
    }

}
