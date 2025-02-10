<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerId extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'player_id'];
}
