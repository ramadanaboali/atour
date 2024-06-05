<?php

namespace App\Services\Customer;

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

    public function createItem(array $inputs){
        $data = [
            
        ];
        return $this->repo->create($data);
    }
  }
