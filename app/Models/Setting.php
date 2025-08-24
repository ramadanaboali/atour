<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [];
    public function termsTranslations()
    {
        return $this->hasMany(SettingTranslation::class)->where('type', 'terms');
    }
    public function privacyTranslations()
    {
        return $this->hasMany(SettingTranslation::class)->where('type', 'privacy');
    }
    public function aboutTranslations()
    {
        return $this->hasMany(SettingTranslation::class)->where('type', 'about');
    }
    public function cancelTermTranslations()
    {
        return $this->hasMany(SettingTranslation::class)->where('type', 'cancel_terms');
    }
    public function helpingTranslations()
    {
        return $this->hasMany(SettingTranslation::class)->where('type', 'helping');
    }

 

}
