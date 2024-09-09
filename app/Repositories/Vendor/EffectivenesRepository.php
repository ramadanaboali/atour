<?php

namespace App\Repositories\Vendor;

use App\Models\Effectivenes;
use App\Repositories\AbstractRepository;

class EffectivenesRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Effectivenes::class);
    }

}
