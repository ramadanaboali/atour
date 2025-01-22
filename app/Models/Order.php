<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    public const STATUS_PENDING = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_REJECTED = 2;
    public const STATUS_ONPROGRESS = 3;
    public const STATUS_COMPLEALED = 4;
    public const STATUS_CANCELED = 5;
    public const STATUS_WITHDRWAL = 6;
 

}
