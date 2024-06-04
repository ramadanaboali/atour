<?php

namespace App\Repositories\Vendor;

use App\Models\Trip;
use App\Repositories\AbstractRepository;
class TripRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Trip::class);
    }

}
