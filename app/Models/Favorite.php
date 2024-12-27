<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'model_id',
        'model_type',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function gift()
    {
        return $this->belongsTo(Gift::class,'model_id')->where('model_type','gitft');
    }
    public function trip()
    {
        return $this->belongsTo(Trip::class,'model_id')->where('model_type','trip');
    }
    public function effectivene()
    {
        return $this->belongsTo(Effectivenes::class,'model_id')->where('model_type','effectiveness');
    }

}
