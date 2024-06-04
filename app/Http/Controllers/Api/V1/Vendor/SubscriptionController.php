<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\PayRequest;
use App\Http\Requests\Vendor\SubscriptionRequest;
use App\Models\Subscription;
use App\Services\General\StorageService;
use App\Services\Vendor\SubscriptionService;
use Illuminate\Support\Facades\Schema;
use function response;

class SubscriptionController extends Controller
{
    protected SubscriptionService $service;

    public function __construct(SubscriptionService $service)
    {
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new Subscription();
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

    public function store(SubscriptionRequest $request)
    {
        $data = $request->all();
        return response()->apiSuccess($this->service->subscribe($data));
    }
    public function update(SubscriptionRequest $request,Subscription $subscription)
    {
        $data = $request->all();
        return response()->apiSuccess($this->service->update($data,$subscription));
    }
    public function pay(PayRequest $request)
    {
        $data = $request->all();
        return response()->apiSuccess($this->service->pay($data));
    }

}
