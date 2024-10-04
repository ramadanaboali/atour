<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Customer\BookingTripRequest;
use App\Http\Resources\OrderResource;
use App\Models\BookingTrip;
use App\Models\Order;
use App\Models\Trip;
use App\Services\General\StorageService;
use App\Services\Customer\OrderService;
use App\Services\TapService;
use Illuminate\Http\Request;
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
        $data = OrderResource::collection($data);
        return response()->apiSuccess($data);

    }

    public function show($id)
    {
        $data = new OrderResource($this->service->get($id));
        return response()->apiSuccess($data);
    }

    public function bookingTrip(BookingTripRequest $request)
    {
        $trip = Trip::findOrFail($request->trip_id);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['payment_status'] = 'pendding';
        $data['status'] = 0;
        $data['total'] = $trip->price;
        $data['vendor_id'] = $trip->vendor_id;
        $order = BookingTrip::create($data);
        if ($request->payment_way == 'online') {
            $payment = new TapService();
            $tap = $payment->pay($trip->price);
            if ($tap['success']) {
                $order->payment_id = $tap['data']['id'];
                $order->save();
            }
            return response()->apiSuccess($tap);
        }
        $order = new OrderResource($order);
        return response()->apiSuccess($order);
    }

    public function callBack(Request $request)
    {

        $payment = new TapService();
        $result = $payment->callBack($request->tap_id);
        return response()->apiSuccess($result);

    }
    public function cancel($id)
    {
        $order = $this->service->get($id);
        $data = ['status' => Order::STATUS_CANCELED];
        return response()->apiSuccess($this->service->update($data, $order));
    }
    public function delete(Order $trip)
    {

        return response()->apiSuccess($this->service->delete($trip));
    }

}
