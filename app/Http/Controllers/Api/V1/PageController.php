<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\RateRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\OnboardingResource;
use App\Http\Resources\FeatureResource;
use App\Http\Resources\RequirementResource;
use App\Http\Resources\SubCategoryResource;
use App\Http\Resources\CityResource;
use App\Mail\ContactUsMail;
use App\Models\Add;
use App\Models\Article;
use App\Models\Attachment;
use App\Models\Blog;
use App\Models\City;
use App\Models\DeliveryCost;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Effectivenes;
use App\Models\Gift;
use App\Models\Job;
use App\Models\Rate;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\Trip;
use App\Models\UserPreferedSetting;
use App\Models\WhyBooking;
use App\Models\Feature;
use App\Models\Onboarding;
use App\Models\Requirement;
use App\Models\User;
use App\Services\General\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    protected StorageService $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }
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
    public function blog($id)
    {
        $data = Blog::with('attachments')->where('id', $id)->first();
        return apiResponse(true, $data, null, null, 200);
    }
    public function ads()
    {
        $data = Add::where('active', 1)->get();
        return apiResponse(true, $data, null, null, 200);
    }

    public function getOffers(Request $request)
    {

        $data = Trip::with(['category','subcategory','city','vendor','programs','attachments','offers'])->where(function ($query) use ($request) {
            if ($request->filled('from')) {
                $query->where('services.created_at', '>=', $request->from . ' 00:00:00');
            }
            if ($request->filled('to')) {
                $query->where('services.created_at', '<=', $request->to . ' 00:00:00');
            }
        })->orderBy('id', 'desc')->get();
        return apiResponse(true, $data, null, null, 200);
    }
    public function topCities(Request $request)
    {
        $data =
        Trip::with(['programs','offers','city','category','subcategory'])->where(function ($query) use ($request) {
            if ($request->filled('from')) {
                $query->where('created_at', '>=', $request->from . ' 00:00:00');
            }
            if ($request->filled('to')) {
                $query->where('created_at', '<=', $request->to . ' 00:00:00');
            }
        })->orderBy('id', 'desc')->get();

        return apiResponse(true, $data, null, null, 200);
    }
    public function cityTrips(Request $request, $id)
    {
        $data = City::with(['country','trips.vendor','trips.offers','trips.rates','trips.programs','trips.category','trips.subcategory'])->leftJoin('trips', 'trips.city_id', 'cities.id')->where(function ($query) use ($request) {

            if ($request->filled('price_from')) {
                $query->where('trips.price', '>=', $request->from);
            }
            if ($request->filled('price_to')) {
                $query->where('trips.price', '<=', $request->to);
            }
            if ($request->filled('price_to')) {
                $query->where('trips.price', '<=', $request->to);
            }

        })->where('cities.id', $id)->select(['cities.*'])->first();
        return apiResponse(true, $data, null, null, 200);
    }
    public function cities()
    {
        $data = City::where('active', 1)->get();
        $result = CityResource::collection($data);
        return apiResponse(true, $data, null, null, 200);
    }
    public function getCity(Request $request, $id)
    {
        $data = City::find($id);
        return apiResponse(true, $data, null, null, 200);
    }
    public function whyBookings(Request $request)
    {
        $data = WhyBooking::get();
        return apiResponse(true, $data, null, null, 200);
    }

    public function lastTrips(Request $request)
    {
        $data = Trip::with(['city.country','vendor','offers','rates','programs','category','subcategory','attachments'])->orderBy('id', 'desc')->limit(10)->get();
        return apiResponse(true, $data, null, null, 200);
    }

    public function trips(Request $request)
    {
        $data = Trip::with(['city.country','vendor','offers','rates','programs','category','subcategory','attachments'])->orderBy('id', 'desc')->get();
        return apiResponse(true, $data, null, null, 200);
    }

    public function getTrips($id)
    {
        $data = Trip::with(['city.country','vendor','offers','rates','programs','category','subcategory','attachments'])->find($id);
        return apiResponse(true, $data, null, null, 200);
    }

    public function currencies()
    {
        $data = Currency::where('active', 1)->get();
        return apiResponse(true, $data, null, null, 200);
    }

    public function countries()
    {
        $data = Country::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->where('active', 1)->get();
        $result = CountryResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function getCountry($id)
    {
        $data = City::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->where('country_id', $id)->where('active', 1)->get();
        $result = CityResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }

    public function categories()
    {

    }

    public function sub_categories(Request $request)
    {
        $data1 = SubCategory::where('category', 'gift')->get();
        $data2 = SubCategory::where('category', 'trip')->get();
        $data3 = SubCategory::where('category', 'effectiveness')->get();
        $result['gifts'] = SubCategoryResource::collection($data1);
        $result['trips'] = SubCategoryResource::collection($data2);
        $result['effectiveness'] = SubCategoryResource::collection($data3);
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

        return apiResponse(true, $data, null, null, 200);
    }

    public function changeLanguage(Request $request)
    {
        $data = User::find(Auth::id());
        $data->lang = $request->lang;
        $data->save();
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
        $trip_id = null;
        $effectivenes_id = null;
        $gift_id = null;
        if ($request->model_type == "trip") {
            $trip_id = $request->model_id;
        }
        if ($request->model_type == "effectivenes") {
            $effectivenes_id = $request->model_id;
        }
        if ($request->model_type == "gift") {
            $gift_id = $request->model_id;
        }
        $data = [
            'rate' => $request->rate,
            'model_id' => $request->model_id,
            'model_type' => $request->model_type,
            'comment' => $request->comment,
            'trip_id' => $trip_id,
            'effectivenes_id' => $effectivenes_id,
            'gift_id' => $gift_id,
            'user_id' => auth()->user()->id
        ];
        $data = Rate::create($data);
        if ($request->filled('images')) {
            foreach ($request->images as $file) {
                $folder_path = "images/rate";
                $image = $this->storageService->storeFile($file, $folder_path);

                $attachment = [
                       'model_id' => $data->id,
                       'model_type' => 'trip',
                       'attachment' => $image,
                       'title' => "trip",
                   ];
                Attachment::create($attachment);
            }
        }
        return apiResponse(true, $data, __('api.update_success'), null, 200);
    }
    public function contactUs(ContactRequest $request)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id
        ];
        $data = ContactUs::create($data);
        //send email to admin
        $adminEmail = config('mail.admin_email');
        $data['user_email'] = auth()->user()->email;
        $data['user_name'] = auth()->user()->name;
        Mail::to($adminEmail)->send(new ContactUsMail($data));
        return apiResponse(true, $data, __('api.update_success'), null, 200);
    }
    public function onboardings()
    {
        $data = Onboarding::get();
        return apiResponse(true, OnboardingResource::collection($data), null, null, 200);
    }
    public function features()
    {
        $data = Feature::get();
        return apiResponse(true, FeatureResource::collection($data), null, null, 200);
    }
    public function requirements()
    {
        $data = Requirement::get();
        return apiResponse(true, RequirementResource::collection($data), null, null, 200);
    }
    public function allLocations()
    {
        $trips = Trip::select('id', 'start_lat as lat', 'start_long as long')
                    ->whereNotNull('start_lat')
                    ->whereNotNull('start_long')
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'trip';
                        return $item;
                    });

        $effectivenes = Effectivenes::select('id', 'lat', 'long')
                    ->whereNotNull('lat')
                    ->whereNotNull('long')
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'effectivenes';
                        return $item;
                    });

        $gifts = Gift::select('id', 'lat', 'long')
                    ->whereNotNull('lat')
                    ->whereNotNull('long')
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'gift';
                        return $item;
                    });

        $data = $trips->merge($effectivenes)->merge($gifts);
        return apiResponse(true, LocationResource::collection($data), null, null, 200);

    }

    public function DeliveryCost($city_id, $vendor_id)
    {
        $deliveryCost = DeliveryCost::where('city_id', $city_id)->where('user_id', $vendor_id)->first();
        
        return apiResponse(true, ["cost" => $deliveryCost->cost ?? 0], null, null, 200);
    }

}
