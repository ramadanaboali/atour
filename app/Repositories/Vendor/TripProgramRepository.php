<?php

namespace App\Repositories\Vendor;

use App\Models\TripProgram;
use App\Repositories\AbstractRepository;
class TripProgramRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(TripProgram::class);
    }

}
