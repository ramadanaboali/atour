<?php

namespace App\Services\Vendor;

use App\Repositories\Vendor\ServiceRepository;
use App\Services\AbstractService;
class ServiceService extends AbstractService
{
    protected $repo;
    public function __construct(ServiceRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
  }
