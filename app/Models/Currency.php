<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Currency extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title_en',
        'title_ar',
        'code_en',
        'code_ar',
        'flag',
        'value',
        'active',
        'created_by',
        'updated_by'
    ];

     protected $table = 'currencies';
    protected $appends = ['text','photo','title','codex'];
    public function getPhotoAttribute()
    {
        return array_key_exists('flag', $this->attributes) ? ($this->attributes['flag'] != null ? asset('storage/currencies/' . $this->attributes['flag']) : null) : null;

    }
    public function getTextAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
    public function getCodexAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['code_en'] ?? $this->attributes['code_ar'];
        } else {
            return $this->attributes['code_ar'] ?? $this->attributes['code_en'];
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

}
