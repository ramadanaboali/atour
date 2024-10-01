<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\EffectivenesResource;
use App\Http\Resources\GiftResource;
use App\Http\Resources\TripResource;
use App\Models\City;
use App\Models\Effectivenes;
use App\Models\Favorite;
use App\Models\Gift;
use App\Models\Trip;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $most_visited = City::where('active', true)->get();
        $data['most_visited'] = CityResource::collection($most_visited);
        $old_experiences = Trip::where('active', true)->get();
        $data['old_experiences'] = TripResource::collection($old_experiences);
        $effectivenes = Effectivenes::where('active', true)->where('date', '>', date('Y-m-d'))->get();
        $data['effectivenes'] = EffectivenesResource::collection($effectivenes);

        return apiResponse(true, $data, null, null, 200);
    }
    public function trips()
    {
        $trips = Trip::where('active', true)->get();
        $data = TripResource::collection($trips);
        return apiResponse(true, $data, null, null, 200);
    }
    public function gifts()
    {
        $gifts = Gift::where('active', true)->get();
        $data = GiftResource::collection($gifts);
        return apiResponse(true, $data, null, null, 200);
    }
    public function effectivenes()
    {
        $effectivenes = Effectivenes::where('active', true)->get();
        $data = EffectivenesResource::collection($effectivenes);
        return apiResponse(true, $data, null, null, 200);
    }
    public function favourite()
    {
        $data = Favorite::with('trip')->where('user_id', auth()->user()->id)->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function searchByCity(Request $request, $city_id)
    {

        $data = City::with(['trips', 'gifts', 'effectivenes'])->find($city_id);

        return apiResponse(true, $data, null, null, 200);
    }
}
