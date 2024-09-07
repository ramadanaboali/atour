<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierService extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_category_id',
        'supplier_id'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
}
