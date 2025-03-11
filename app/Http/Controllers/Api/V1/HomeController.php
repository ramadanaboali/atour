<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\EffectivenesResourceCustomer;
use App\Http\Resources\GiftResourceCustomer;
use App\Http\Resources\TripResourceCustomer;
use App\Models\City;
use App\Models\Effectivenes;
use App\Models\FAQ;
use App\Models\Favorite;
use App\Models\Gift;
use App\Models\Offer;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home(Request $request)
    {

        $token = $request->header('Authorization');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $request = request()->merge(['Authorization' => 'Bearer ' . $token]);
            $user = Auth::guard('api')->user();
            if ($user) {
                Auth::setUser($user);
            }
        }

        $offer = Offer::whereHas('vendor')->join('users', function ($query) {
            $query->on('users.id', '=', 'offers.vendor_id')->where('users.active', 1);

        })->where('offers.active', 1)->select('offers.*')->first();

        $data['offer'] = $offer;
        if ($offer) {
            if ($offer->type == 'gift') {

                $gift = Gift::where('id',$offer->gift_id)->first();
                if ($gift==null) {
                    $data['offer'] = null;
                }
            }
            if ($offer->type == 'trip') {
                $trip = Trip::where('id',$offer->trip_id)->first();
                if ($trip==null) {
                    $data['offer'] = null;
                }
            }
            if ($offer->type == 'effectivenes') {
                $effectivenes = Effectivenes::where('id',$offer->effectivenes_id)->first();
                if ($effectivenes==null) {
                    $data['offer'] = null;
                }
            }
        }



        $data['most_visited'] = City::where('active', true)->get();
        $old_experiences = Trip::whereHas('vendor')->join('users', function ($query) {
            $query->on('users.id', '=', 'trips.vendor_id')->where('users.active', 1);
        })->where('trips.active', true)->select('trips.*')->get();

        $data['old_experiences'] = TripResourceCustomer::collection($old_experiences);
        $effectivenes = Effectivenes::whereHas('vendor')->join('users', function ($query) {
            $query->on('users.id', '=', 'effectivenes.vendor_id')->where('users.active', 1);
        })->where('effectivenes.active', true)->where('from_date', '>=', date('Y-m-d'))->where('to_date', '<=', date('Y-m-d'))->select('effectivenes.*')->get();
        $data['effectivenes'] = EffectivenesResourceCustomer::collection($effectivenes);

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
        $data = TripResourceCustomer::collection($trips);
        return apiResponse(true, $data, null, null, 200);
    }
    public function similler_trips($id)
    {
        $trip = Trip::findOrFail($id);
        $trips = Trip::where('vendor_id', $trip->vendor_id)->where('active', true)->get();
        $data = TripResourceCustomer::collection($trips);
        return apiResponse(true, $data, null, null, 200);
    }
    public function trip($id)
    {
        $trip = Trip::findOrFail($id);
        $data = new TripResourceCustomer($trip);
        return apiResponse(true, $data, null, null, 200);
    }
    public function gifts()
    {
        $gifts = Gift::where('active', true)->get();
        $data = GiftResourceCustomer::collection($gifts);
        return apiResponse(true, $data, null, null, 200);
    }
    public function gift($id)
    {
        $gift = Gift::findOrFail($id);
        $data = new GiftResourceCustomer($gift);
        return apiResponse(true, $data, null, null, 200);
    }
    public function effectivenes()
    {
        $effectivenes = Effectivenes::where('active', true)->get();
        $data = EffectivenesResourceCustomer::collection($effectivenes);
        return apiResponse(true, $data, null, null, 200);
    }
    public function effectivene($id)
    {
        $effectivene = Effectivenes::findOrFail($id);
        $data = new EffectivenesResourceCustomer($effectivene);
        return apiResponse(true, $data, null, null, 200);
    }
    public function saveFavourite($type, $id)
    {
        $data = [
            'model_type' => $type,
            'model_id' => $id,
            'user_id' => auth()->user()->id
        ];
        $favourit = Favorite::where('model_id', $id)->where('model_type', $type)->where('user_id', auth()->user()->id)->first();
        if ($favourit) {
            $favourit->delete();
            $favourit->forceDelete();
            $favourit->status = 0;
        } else {
            
            $favourit = Favorite::create($data);
            $favourit->status = 1;
        }
        return response()->apiSuccess($favourit);

    }
    public function deleteFavourite($type, $id)
    {

        $favourit = Favorite::where('model_type', $type)->where('model_id', $id)->where('user_id', auth()->user()->id)->delete();
        return response()->apiSuccess($favourit);

    }
    public function favourite()
    {
        $trips = Trip::leftJoin('favorites', 'favorites.model_id', 'trips.id')->where('favorites.model_type', 'like', 'trip%')->where('favorites.user_id', auth()->user()->id)->select('trips.*')->get();
        $data['trips'] = TripResourceCustomer::collection($trips);

        $effectivenes = Effectivenes::leftJoin('favorites', 'favorites.model_id', 'effectivenes.id')->where('favorites.model_type', 'like', 'effectivene%')->where('favorites.user_id', auth()->user()->id)->select('effectivenes.*')->get();
        $data['effectivenes'] = EffectivenesResourceCustomer::collection($effectivenes);

        $gifts = Gift::leftJoin('favorites', 'favorites.model_id', 'gifts.id')->where('favorites.model_type', 'like', 'gift%')->where('favorites.user_id', auth()->user()->id)->select('gifts.*')->get();
        $data['gifts'] = GiftResourceCustomer::collection($gifts);



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
