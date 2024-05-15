<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Term;
use Illuminate\Support\Facades\App;

class SettingController extends Controller
{
    public function contact()
    {
        $items = Setting::whereIn('key', ['general_email','general_phone','general_whatsapp','general_company_address'])->get();

        $data = [
            'email' => $items->where('key', 'general_email')->first()->value ?? '',
            'phone' => $items->where('key', 'general_phone')->first()->value ?? '',
            'whatsapp' => $items->where('key', 'general_whatsapp')->first()->value ?? '',
            'address' => $items->where('key', 'general_company_address')->first()->value ?? '',
        ];

        return apiResponse(true, $data, null, null, 200);
    }
    public function header()
    {
        $items = Setting::where('key','like', 'header_%')->get();
        $header_logo = $items->where('key', 'header_logo')->first()->value ?? '';
        $data = [
            'logo' => asset('storage/settings/' .$header_logo),
        ];

        return apiResponse(true, $data, null, null, 200);
    }
    public function footer()
    {
        $items = Setting::whereIn('key',['footer_facebook','footer_twitter','footer_instagram','footer_snapchat','footer_tiktok','footer_google_play','footer_app_store'])->get();
        $data = [
            'footer_facebook' => $items->where('key', 'footer_facebook')->first()->value ?? '',
            'footer_twitter' => $items->where('key', 'footer_twitter')->first()->value ?? '',
            'footer_instagram' => $items->where('key', 'footer_instagram')->first()->value ?? '',
            'footer_snapchat' => $items->where('key', 'footer_snapchat')->first()->value ?? '',
            'footer_tiktok' => $items->where('key', 'footer_tiktok')->first()->value ?? '',
            'footer_google_play' => $items->where('key', 'footer_google_play')->first()->value ?? '',
            'footer_app_store' => $items->where('key', 'footer_app_store')->first()->value ?? '',
        ];
        return apiResponse(true, $data, null, null, 200);
    }

    public function about()
    {
        $lang=request()->header('language')??'ar';
        $data = [
            'content' => Setting::where('key', 'LIKE', 'about_content_'.$lang)->value('value')
        ];
        return apiResponse(true, $data, null, null, 200);
   }

       public function terms()
    {
        if(App::isLocale('en')) {
            $data = Setting::where('key', 'LIKE', 'terms_content_en')->value('value');
        } else {
            $data = Setting::where('key', 'LIKE', 'terms_content_ar')->value('value');
        }
        return apiResponse(true, $data, null, null, 200);
    }

    public function privacy()
    {

        if(App::isLocale('en')) {
            $data = Setting::where('key', 'LIKE', 'privacy_content_en')->value('value');
        } else {
            $data = Setting::where('key', 'LIKE', 'privacy_content_ar')->value('value');
        }

        return apiResponse(true, $data, null, null, 200);
    }
}
