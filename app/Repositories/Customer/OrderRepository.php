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

}
