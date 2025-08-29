<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\TripOfferRequest;
use App\Http\Requests\Vendor\TripRequest;
use App\Http\Resources\TripResource;
use App\Models\Attachment;
use App\Models\Offer;
use App\Models\Trip;
use App\Models\TripFeature;
use App\Models\TripSubCategory;
use App\Models\TripTranslation;
use App\Services\General\StorageService;
use App\Services\Vendor\TripService;
use Illuminate\Support\Facades\Schema;

use function response;

class TripController extends Controller
{
    protected TripService $service;
    protected StorageService $storageService;

    public function __construct(TripService $service, StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $data = Trip::where('vendor_id', auth()->user()->id)->paginate($request->per_page ?? 30);
        return response()->apiSuccess(TripResource::collection($data));

    }

    public function show($id)
    {
        $data = new TripResource($this->service->get($id));
        return response()->apiSuccess($data);
    }

    public function storeOffer(TripOfferRequest $request)
    {
        $locale = $request->header('lang', 'en');
        
        $folder_path = "images/offers";
        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = $this->storageService->storeFile($file, $folder_path);
        }
        $offer_data = [
            'vendor_id' => auth()->user()->id,
            'trip_id' => $request->trip_id,
            'image' => $image,
        ];
        $offer = Offer::create($offer_data);
        
        // Create translation for the offer
        $offer->translations()->create([
            'locale' => $locale,
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        return response()->apiSuccess($offer);
    }
    public function store(TripRequest $request)
    {
        $locale = $request->header('lang', 'en');
        
        $folder_path = "images/trips";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data = [
            'cover' => $storedPath,
            'vendor_id' => auth()->user()->id,
            'price' => $request->price,
            'min_people' => $request->min_people,
            'max_people' => $request->max_people,
            'start_long' => $request->start_long,
            'start_lat' => $request->start_lat,
            'end_long' => $request->end_long,
            'end_lat' => $request->end_lat,
            'free_cancelation' => $request->free_cancelation,
            'available_days' => $request->available_days,
            'available_times' => $request->available_times,
            'pay_later' => $request->pay_later,
            'city_id' => $request->city_id,
            'created_by' => auth()->user()->id,
        ];
      
        $item = $this->service->store($data);
        $steps_list = [];
        if ($item) {
            // Create translation
          foreach($request->steps_list??[] as $step){
            $steps_list[$locale][] = $step;
          }
        
            TripTranslation::create([
                'trip_id' => $item->id,
                'locale' => $locale,
                'title' => $request->title,
                'description' => $request->description,
                'start_point' => $request->start_point,
                'end_point' => $request->end_point,
                'program_time' => $request->program_time,
                'steps_list' => $steps_list,
            ]);
            
            // Handle subcategories
            if ($request->sub_category_ids) {
                foreach ($request->sub_category_ids as $sub_category_id) {
                    TripSubCategory::create([
                        'trip_id' => $item->id,
                        'sub_category_id' => $sub_category_id,
                    ]);
                }
            }
            
            // Handle features and requirements
            if($request->featur_ids){
                $item->features()->sync($request->featur_ids);
            }
            if($request->requirement_ids){
                $item->requirements()->sync($request->requirement_ids);
            }

            // Handle images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $i = 0;
                foreach ($images as $image) {
                    $storedPath = $this->storageService->storeFile($image, $folder_path, $i);
                    Attachment::create([
                        'model_id' => $item->id,
                        'model_type' => 'trip',
                        'attachment' => $storedPath,
                        'title' => "trip",
                    ]);
                    $i++;
                }
            }
        }
        
        return response()->apiSuccess(new TripResource($item));
    }

    public function update(TripRequest $request, Trip $trip)
    {
        $locale = $request->header('lang', 'en');
        
        $folder_path = "images/trips";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        
        $data = [
            'cover' => $storedPath ?? $trip->cover,
            'price' => $request->price ?? $trip->price,
            'people' => $request->people ?? $trip->people,
            'start_long' => $request->start_long ?? $trip->start_long,
            'start_lat' => $request->start_lat ?? $trip->start_lat,
            'end_long' => $request->end_long ?? $trip->end_long,
            'end_lat' => $request->end_lat ?? $trip->end_lat,
            'free_cancelation' => $request->free_cancelation ?? $trip->free_cancelation,
            'available_days' => $request->available_days ?? $trip->available_days,
            'available_times' => $request->available_times ?? $trip->available_times,
            'pay_later' => $request->pay_later ?? $trip->pay_later,
            'city_id' => $request->city_id ?? $trip->city_id,
            'updated_by' => auth()->user()->id,
        ];
        
        $item = $this->service->update($data, $trip);
        
        if ($item) {
            // Update or create translation
            $translation = TripTranslation::where('trip_id', $trip->id)
                ->where('locale', $locale)
                ->first();
        $steps_list = [];
        foreach($request->steps_list??[] as $step){
            $steps_list[$locale][] = $step;
          }     
            $translationData = [
                'title' => $request->title,
                'description' => $request->description,
                'start_point' => $request->start_point,
                'end_point' => $request->end_point,
                'program_time' => $request->program_time,
                'steps_list' => $steps_list,
            ];
            
            if ($translation) {
                $translation->update(array_filter($translationData, fn($value) => $value !== null));
            } else {
                TripTranslation::create(array_merge([
                    'trip_id' => $trip->id,
                    'locale' => $locale,
                ], array_filter($translationData, fn($value) => $value !== null)));
            }
            
            // Handle subcategories
            if ($request->filled('sub_category_ids')) {
                TripSubCategory::where('trip_id', $trip->id)->delete();
                foreach ($request->sub_category_ids as $sub_category_id) {
                    TripSubCategory::create([
                        'trip_id' => $trip->id,
                        'sub_category_id' => $sub_category_id,
                    ]);
                }
            }
            
            // Handle features and requirements
            if($request->featur_ids){
                $item->features()->sync($request->featur_ids);
            }
            if($request->requirement_ids){
                $item->requirements()->sync($request->requirement_ids);
            }
            
            // Handle images
            if ($request->hasFile('images')) {
                Attachment::where('model_id', $trip->id)->where('model_type', 'trip')->delete();
                $images = $request->file('images');
                $i = 0;
                foreach ($images as $image) {
                    $storedPath = $this->storageService->storeFile($image, $folder_path, $i);
                    Attachment::create([
                        'model_id' => $trip->id,
                        'model_type' => 'trip',
                        'attachment' => $storedPath,
                        'title' => "trip",
                    ]);
                    $i++;
                }
            }
        }

        return response()->apiSuccess($item);
    }
    public function delete($id)
    {
        $trip=$this->service->get($id);
        if(count($trip->bookings)){
            return response()->apiFail(__('api.trip_has_bookings'));
        }
        $this->storageService->deleteFile($trip->cover);
        Offer::where('trip_id', $id)->delete();
        return response()->apiSuccess($this->service->delete($trip));
    }
   
}
