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
