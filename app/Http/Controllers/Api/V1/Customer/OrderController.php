<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Customer\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\General\StorageService;
use App\Services\Customer\OrderService;
use Illuminate\Support\Facades\Schema;

use function response;

class OrderController extends Controller
{
    protected OrderService $service;
    protected StorageService $storageService;

    public function __construct(OrderService $service, StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new Order();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->service->whereOptions($input, $columns);
        }
        $data = $this->service->Paginate($input, $wheres);
        $data=OrderResource::collection($data);
        return response()->apiSuccess($data);

    }

    public function show($id)
    {
        $data = new OrderResource($this->service->get($id));
        return response()->apiSuccess($data);
    }

    public function store(OrderRequest $request)
    {

        $data = $request->except(['cover']);

        $data['user_id'] = auth()->user()->id;
        $order = $this->service->createItem($data);
        $order = new OrderResource($order);
        return response()->apiSuccess($order);
    }

    public function update(OrderRequest $request, Order $trip)
    {

        $data = $request->except(['cover','_method']);
        if ($request->hasFile('cover')) {
            $folder_path = "images/Order";
            $storedPath = null;
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
            $data['cover'] = $storedPath;
        }
        return response()->apiSuccess($this->service->update($data, $trip));
    }
    public function cancel($id)
    {
        $order = $this->service->get($id);
        $data = ['status'=>Order::STATUS_CANCELED];
        return response()->apiSuccess($this->service->update($data, $order));
    }
    public function delete(Order $trip)
    {

        return response()->apiSuccess($this->service->delete($trip));
    }

}
