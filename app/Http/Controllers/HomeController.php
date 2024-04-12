<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function language()
    {

        if(Session::get('lang')=='en'){
            Session::put('lang', 'ar');
        }else{
            Session::put('lang', 'en');
        }
        return back();

    }
    public function privacy()
    {

        if(App::isLocale('en')) {
            return view('privacy_en');
        }else{
            return view('privacy_ar');
        }

    }

}
