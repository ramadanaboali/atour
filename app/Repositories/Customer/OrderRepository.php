<?php

namespace App\Repositories\Customer;

use App\Models\Order;
use App\Repositories\AbstractRepository;
class OrderRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Order::class);
    }

    public function getOrderCode(){
        $last_code=Order::orderBy('id','desc')->first();
        return $last_code ? (int)$last_code->code+1 :1;
    }
}
