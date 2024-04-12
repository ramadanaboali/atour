<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\SliderResource;
use App\Models\Category;
use App\Models\Country;
use App\Models\Slider;

class PageController extends Controller
{

    public function sliders()
    {
        $data = Slider::where('active', 1)->get();
        $result=SliderResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function countries()
    {
        $data = Country::where('active', 1)->get();
        $result=CountryResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function categories()
    {
        $data = Category::where('active', 1)->get();
        $result=CategoryResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function home()
    {
        $sliders = Slider::where('active', 1)->get();
        $data['sliders']=SliderResource::collection($sliders);

        $categories = Category::where('active', 1)->limit(20)->orderBy('id','desc')->get();
        $data['categories']=CategoryResource::collection($categories);


        return apiResponse(true, $data, null, null, 200);
    }

}
