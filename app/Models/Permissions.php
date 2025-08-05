<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissions extends Model
{
    public static function getPermission($permission)
    {
        return self::getPermissions()[$permission] ?? '';
    }

    public static function getPermissions()
    {
        return [

        ];
    }
}
