<?php

namespace App\Services\Customer;

use App\Models\Order;
use App\Repositories\Customer\OrderRepository;
use App\Services\AbstractService;
class OrderService extends AbstractService
{
    protected $repo;
    public function __construct(OrderRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function getOrders(){
        return Order::whereHas('trip', function ($q) {
            $q->where('vendor_id', auth()->user()->id);
        })->get();
    }
    public function getOrder($id){
        return Order::whereHas('trip', function ($q) {
            $q->where('vendor_id', auth()->user()->id);
        })->find($id);
    }

    public function createItem(array $inputs){
        $data = [
            'code'=>$this->repo->getOrderCode(),
            'tourist_name'=>$inputs['tourist_name'] ?? null,
            'tourist_email'=>$inputs['tourist_email'] ?? null,
            'tourist_phone'=>$inputs['tourist_phone'] ?? null,
            'promocode'=>$inputs['promocode'] ?? null,
            'promocode_value'=>$inputs['promocode_value'] ?? null,
            'payment_type'=>$inputs['payment_type'] ?? null,
            'order_date'=>$inputs['order_date'] ?? null,
            'order_time'=>$inputs['order_time'] ?? null,
            'details'=>json_encode($inputs),
            'total'=>$inputs['total'] ?? null,
            'members'=>($inputs['adults'] ?? 0) + ($inputs['childrens'] ?? 0),
            'childrens'=>$inputs['childrens'] ?? null,
            'adults'=>$inputs['adults'] ?? null,
            'program_id'=>$inputs['program_id'] ?? null,
            'trip_id'=>$inputs['trip_id'] ?? null,
            'user_id'=>$inputs['user_id'] ?? null,
        ];
        return $this->repo->create($data);
    }

  }
