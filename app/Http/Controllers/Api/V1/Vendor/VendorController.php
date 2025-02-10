<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{

    public function status()
    {
        $user = Auth::user();
        $user->active = !$user->active;

        return response()->apiSuccess($user->save());

    }


}
