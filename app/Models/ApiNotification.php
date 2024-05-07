<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiNotification extends Model
{
    use HasFactory;
    protected $fillable = ['text','time','day','user_id','model_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
