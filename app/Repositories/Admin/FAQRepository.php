<?php

namespace App\Repositories\Admin;

use App\Models\FAQ;
use App\Repositories\AbstractRepository;

class FAQRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(FAQ::class);
    }


}
