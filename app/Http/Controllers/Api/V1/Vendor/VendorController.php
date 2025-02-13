<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{

    public function status()
    {
        $user = Auth::user();
        $status = !$user->active;
        $user->active = $status;
        $user->save();
        return response()->apiSuccess($status);

    }
    public function notifications()
    {
        $data=Notification::where('user_id',auth()->user()->id)->where('is_read',0)->orderBy('id','desc')->get();
        return response()->apiSuccess($data);

    }
    public function readNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = 1;
        $notification->save();
        return response()->apiSuccess($notification);

    }


}
