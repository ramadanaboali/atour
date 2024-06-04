<?php

namespace App\Services\Vendor;

use App\Repositories\Vendor\TripProgramRepository;
use App\Services\AbstractService;
class TripProgramService extends AbstractService
{
    protected $repo;
    public function __construct(TripProgramRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
  }
