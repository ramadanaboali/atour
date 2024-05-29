<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING=0;
    const STATUS_COMPLEALED=4;
    const STATUS_CANCELED=5;
}
