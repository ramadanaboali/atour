<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripRequirement extends Model
{
    use HasFactory;
    protected $fillable = [
    "requirement_id",
    "trip_id",
    ];


    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }

}
