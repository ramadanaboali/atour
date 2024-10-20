<?php

namespace App\Services\Admin;

use App\Repositories\Admin\FAQRepository;
use App\Services\AbstractService;

class FAQService extends AbstractService
{
    protected $repo;
    public function __construct(FAQRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
