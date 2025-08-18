<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'user_id','closed_at','status','notes'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
