<?php

namespace App\Services\Vendor;

use App\Repositories\Vendor\EffectivenesRepository;
use App\Services\AbstractService;

class EffectivenesService extends AbstractService
{
    protected $repo;
    public function __construct(EffectivenesRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
