<?php

namespace App\Repositories\Vendor;

use App\Models\Gift;
use App\Repositories\AbstractRepository;

class GiftRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Gift::class);
    }

}
