<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    private $viewIndex  = 'admin.pages.dashboard.index';

    public function index(Request $request)
    {
        
        return view($this->viewIndex, get_defined_vars());
    }
    public function updateToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token' => $request->fcm_token]);
            return response()->json([
                'success' => true
            ]);
        } catch(\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }
    public function notification(Request $request)
    {
    }
}
