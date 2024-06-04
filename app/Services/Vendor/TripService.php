<?php

namespace App\Services\Vendor;

use App\Repositories\Vendor\TripRepository;
use App\Services\AbstractService;
class TripService extends AbstractService
{
    protected $repo;
    public function __construct(TripRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
  }
