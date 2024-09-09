<?php

namespace App\Services\Vendor;

use App\Repositories\Vendor\GiftRepository;
use App\Services\AbstractService;

class GiftService extends AbstractService
{
    protected $repo;
    public function __construct(GiftRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
