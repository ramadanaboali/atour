<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\OrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\General\StorageService;
use App\Services\Customer\OrderService;

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
        $orders = $this->service->getOrders();
        $data = OrderResource::collection($orders);
        return response()->apiSuccess($data);

    }

    public function show($id)
    {
        $data = new OrderResource($this->service->getOrder($id));
        return response()->apiSuccess($data);
    }

    public function updateStatus(OrderStatusRequest $request)
    {
        $data['status'] = $request->status;
        $item = $this->service->getOrder($request->order_id);
        if (empty($item)) {
            return response()->apiFail(__('api.order_not_exist'));
        }
        $order = $this->service->update($data,$item);
        return response()->apiSuccess($order);
    }


    public function delete(Order $trip)
    {

        return response()->apiSuccess($this->service->delete($trip));
    }

}
