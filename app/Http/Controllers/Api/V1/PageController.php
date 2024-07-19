<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\SliderResource;
use App\Models\Add;
use App\Models\Article;
use App\Models\Blog;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Job;
use App\Models\Rate;
use App\Models\Service;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserPreferedSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function sliders()
    {
        $data = Slider::where('active', 1)->get();
        $result = SliderResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function blogs()
    {
        $data = Blog::with('attachments')->where('active', 1)->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function ads()
    {
        $data = Add::where('active', 1)->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function searchByCity(Request $request, $city_id)
    {

        $data = Service::with(['city.trips','offers','vendor','rates'])->where(function ($query) use ($request) {
            if($request->filled('from')) {
                $query->where('services.created_at', '>=', $request->from . ' 00:00:00');
            }
            if($request->filled('to')) {
                $query->where('services.created_at', '<=', $request->to . ' 00:00:00');
            }
        })->where('city_id',$city_id)->orderBy('id', 'desc')->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function getOffers(Request $request)
    {

        $data = Service::with(['city.trips','offers'])->where(function ($query) use ($request) {
            if($request->filled('from')) {
                $query->where('services.created_at', '>=', $request->from . ' 00:00:00');
            }
            if($request->filled('to')) {
                $query->where('services.created_at', '<=', $request->to . ' 00:00:00');
            }
        })->orderBy('id', 'desc')->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function topCities(Request $request)
    {
        $data =
        Service::with(['city.trips','offers'])->leftJoin('users', 'users.id', 'services.vendor_id')->leftJoin('suppliers', 'suppliers.user_id', 'users.id')->leftJoin('cities', 'cities.id', 'suppliers.city_id')->where(function ($query) use ($request) {
            if($request->filled('from')) {
                $query->where('created_at', '>=', $request->from . ' 00:00:00');
            }
            if($request->filled('to')) {
                $query->where('created_at', '<=', $request->to . ' 00:00:00');
            }
        })->groupBy('cities.id')->select([DB::raw('count(services.id) as total_services'),'cities.*'])->orderBy('total_services', 'desc')->get();

        return apiResponse(true, $data, null, null, 200);
    }
    public function cityTrips(Request $request,$id)
    {
        $data = City::with(['country','services','trips'])->leftJoin('trips','trips.city_id','cities.id')->where(function($query)use ($request){

            if($request->filled('price_from')) {
                $query->where('trips.price', '>=', $request->from );
            }
            if($request->filled('price_to')) {
                $query->where('trips.price', '<=', $request->to );
            }
            if($request->filled('price_to')) {
                $query->where('trips.price', '<=', $request->to );
            }

        })->where('cities.id',$id)->select(['cities.*'])->first();
        return apiResponse(true, $data, null, null, 200);
    }
    public function cities()
    {
        $data = City::with(['country','services','trips'])->where('active', 1)->get();
        // $result = CityResource::collection($data);
        return apiResponse(true, $data, null, null, 200);
    }
    public function getCity(Request $request)
    {
        $data = City::with(['country','services'])->where('active', 1)->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function servicecs(Request $request)
    {
        $data = Service::orderBy('id', 'desc')->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function getServicecs($id)
    {
        $data = Service::find($id);
        return apiResponse(true, $data, null, null, 200);
    }
    public function currencies()
    {
        $data = Currency::where('active', 1)->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function countries()
    {
        $data = Country::where('active', 1)->get();
        $result = CountryResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function categories()
    {
        $data = Category::with('subCategory')->where('active', 1)->get();
        $result = CategoryResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function sub_categories(Request $request)
    {
        $data = SubCategory::with(['category', 'subCategory'])
            ->where(function ($query) use ($request) {
                if ($request->filled('category_id')) {
                    $query->where('category_id', $request->category_id);
                }
                if ($request->filled('parent_id')) {
                    $query->where('parent_id', $request->parent_id);
                }
            })->where('active', 1)->get();
        $result = CategoryResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function articles(Request $request)
    {
        $data = Article::with(['attachments'])
            ->where(function ($query) use ($request) {
                if ($request->filled('type')) {
                    $query->where('type', $request->type);
                }
            })->where('active', 1)->get();
        $result = ArticleResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function jobs(Request $request)
    {
        $data = Job::with(['department'])->where('active', 1)->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function home()
    {
        $sliders = Slider::where('active', 1)->get();
        $data['sliders'] = SliderResource::collection($sliders);

        $categories = Category::where('active', 1)->limit(20)->orderBy('id', 'desc')->get();
        $data['categories'] = CategoryResource::collection($categories);


        return apiResponse(true, $data, null, null, 200);
    }

    public function getPreferedSetting()
    {
        $data = UserPreferedSetting::where('user_id', Auth::id())->first();
        return apiResponse(true, $data, null, null, 200);
    }
    public function changePreferedSetting(Request $request)
    {
        $validate = array(
            'lang' => 'required|in:ar,en',
            'currency_id' => 'required|exists:currencies,id',
        );
        $validatedData = Validator::make($request->all(), $validate);
        if ($validatedData->fails()) {
            return apiResponse(false, null, __('api.validation_error'), $validatedData->errors()->all(), 401);
        }

        $user = Auth::user();
        if (!$user) {
            return apiResponse(false, null, __('api.not_found'), null, 404);
        }

        $userPreferedSetting = UserPreferedSetting::updateOrCreate([
            'user_id'   => $user->id,
        ], [
            'lang'   => $request->get('lang'),
            'currency_id'    => $request->get('currency_id')
        ]);
        return apiResponse(true, $userPreferedSetting, __('api.update_success'), null, 200);
    }
    public function getRates($id, $type)
    {
        $data = Rate::where('model_id', $id)->where('model_type', $type)->where('user_id', auth()->user()->id)->get();
        return apiResponse(true, $data, __('api.update_success'), null, 200);
    }
    public function getAllRates()
    {
        $data = Rate::where('user_id', auth()->user()->id)->get();
        return apiResponse(true, $data, __('api.update_success'), null, 200);
    }
    public function saveRates(RateRequest $request)
    {
        $data = [
            'rate' => $request->rate,
            'model_id' => $request->model_id,
            'model_type' => $request->model_type,
            'comment' => $request->comment,
            'user_id' => auth()->user()->id
        ];
        $data = Rate::create($data);
        return apiResponse(true, $data, __('api.update_success'), null, 200);
    }

}
