<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\EffectivenesResource;
use App\Http\Resources\GiftResource;
use App\Http\Resources\TripResource;
use App\Models\City;
use App\Models\Effectivenes;
use App\Models\FAQ;
use App\Models\Favorite;
use App\Models\Gift;
use App\Models\Offer;
use App\Models\Trip;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $data['offer'] = Offer::orderByDesc('id')->first();
        $data['most_visited'] = City::where('active', true)->get();
        $old_experiences = Trip::where('active', true)->get();
        $data['old_experiences'] = TripResource::collection($old_experiences);
        $effectivenes = Effectivenes::where('active', true)->where('date', '>', date('Y-m-d'))->get();
        $data['effectivenes'] = EffectivenesResource::collection($effectivenes);

        return apiResponse(true, $data, null, null, 200);
    }
    public function cities()
    {
        $most_visited = City::where('active', true)->get();
        $data = CityResource::collection($most_visited);

        return apiResponse(true, $data, null, null, 200);
    }
    public function trips()
    {
        $trips = Trip::where('active', true)->get();
        $data = TripResource::collection($trips);
        return apiResponse(true, $data, null, null, 200);
    }
    public function similler_trips($id)
    {
        $trip = Trip::findOrFail($id);
        $trips=Trip::where('vendor_id',$trip->vendor_id)->where('active', true)->get();
        $data = TripResource::collection($trips);
        return apiResponse(true, $data, null, null, 200);
    }
    public function trip($id)
    {
        $trip = Trip::findOrFail($id);
        $data = new TripResource($trip);
        return apiResponse(true, $data, null, null, 200);
    }
    public function gifts()
    {
        $gifts = Gift::where('active', true)->get();
        $data = GiftResource::collection($gifts);
        return apiResponse(true, $data, null, null, 200);
    }
    public function gift($id)
    {
        $gift = Gift::findOrFail($id);
        $data = new GiftResource($gift);
        return apiResponse(true, $data, null, null, 200);
    }
    public function effectivenes()
    {
        $effectivenes = Effectivenes::where('active', true)->get();
        $data = EffectivenesResource::collection($effectivenes);
        return apiResponse(true, $data, null, null, 200);
    }
    public function effectivene($id)
    {
        $effectivene = Effectivenes::findOrFail($id);
        $data = new EffectivenesResource($effectivene);
        return apiResponse(true, $data, null, null, 200);
    }
    public function saveFavourite($type, $id)
    {
        $data = [
            'model_type' => $type,
            'model_id' => $id,
            'user_id' => auth()->user()->id
        ];
        $favourit = Favorite::create($data);
        return response()->apiSuccess($favourit);

    }
    public function deleteFavourite($type, $id)
    {

        $favourit = Favorite::where('model_type',$type)->where('model_id',$id)->where('user_id', auth()->user()->id)->delete();
        return response()->apiSuccess($favourit);

    }
    public function favourite()
    {
        $data['trips'] = Favorite::with('trip')->where('model_type','like','trip%')->where('user_id', auth()->user()->id)->get();
        $data['effectivenes'] = Favorite::with('effectivene')->where('model_type','like','effectivene%')->where('user_id', auth()->user()->id)->get();
        $data['gifts'] = Favorite::with('gift')->where('model_type','like','gift%')->where('user_id', auth()->user()->id)->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function searchByCity(Request $request, $city_id)
    {

        $data = City::with(['trips', 'gifts', 'effectivenes'])->find($city_id);

        return apiResponse(true, $data, null, null, 200);
    }
    public function faqs(Request $request)
    {

        $data = FAQ::get();

        return apiResponse(true, $data, null, null, 200);
    }
}
