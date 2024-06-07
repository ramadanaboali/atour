<?php

namespace App\Repositories\Vendor;

use App\Models\Service;
use App\Repositories\AbstractRepository;
class ServiceRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Service::class);
    }

}
