<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class SubCategory extends Model
{
     use HasFactory,SoftDeletes;
     protected $table = 'sub_categories';
    protected $fillable = ['title_en','title_ar', 'category_id','created_by','updated_by','active','parent_id'];
    protected $appends = ['title','text'];

    public function getTextAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }

    public function getTitleAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
    public function category(): ?BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function subCategory(): ?BelongsTo
    {
        return $this->belongsTo(SubCategory::class,'parent_id');
    }
    public function parent(): ?BelongsTo
    {
        return $this->belongsTo(SubCategory::class,'parent_id');
    }
    public function createdBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by');
    }

}
