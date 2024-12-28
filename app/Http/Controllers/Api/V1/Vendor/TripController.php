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

        $folder_path = "images/offers";
        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = $this->storageService->storeFile($file, $folder_path);
        }
        $offer_data = [
            'vendor_id' => auth()->user()->id,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'trip_id' => $request->trip_id,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'image' => $image,
        ];
        $offer = Offer::create($offer_data);
        return response()->apiSuccess($offer);

    }
    public function store(TripRequest $request)
    {

        $folder_path = "images/trips";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data = [
            'cover' => $storedPath,
            'vendor_id' => auth()->user()->id,
            'title_ar' => $request->title_ar,
            'title_en' => $request->title_en,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'price' => $request->price,
            'start_point' => $request->start_point,
            'program_time' => $request->program_time,
            'people' => $request->people,
            'free_cancelation' => $request->free_cancelation,
            'available_days' => $request->available_days,
            'available_times' => $request->available_times,
            'pay_later' => $request->pay_later,
            'city_id' => $request->city_id,
            'created_by' => auth()->user()->id,
        ];
        $item = $this->service->store($data);
        if ($item) {
            foreach ($request->sub_category_ids as $sub_category_id) {
                $feature = [
                    'trip_id' => $item->id,
                    'sub_category_id' => $sub_category_id,
                ];
                TripSubCategory::create($feature);
            }
            foreach ($request->featurs as $feature_data) {
                $feature = [
                    'trip_id' => $item->id,
                    'title_ar' => $feature_data['title_ar'] ?? null,
                    'title_en' => $feature_data['title_en'] ?? null,
                    'description_en' => $feature_data['description_en'] ?? null,
                    'description_ar' => $feature_data['description_ar'] ?? null,
                ];
                TripFeature::create($feature);
            }
            $images = $request->file('images');
            foreach ($images as $image) {
                $storedPath = $this->storageService->storeFile($image, $folder_path);
                $attachment = [
                    'model_id' => $item->id,
                    'model_type' => 'trip',
                    'attachment' => $storedPath,
                    'title' => "trip",
                ];
                Attachment::create($attachment);
            }
        }
        return response()->apiSuccess(new TripResource($item));
    }

    public function update(TripRequest $request, Trip $trip)
    {


        $folder_path = "images/trips";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data = [
            'cover' => $storedPath ?? $trip->cover,
            'title_ar' => $request->title_ar ?? $trip->title_ar,
            'title_en' => $request->title_en ?? $trip->title_en,
            'description_en' => $request->description_en ?? $trip->description_en,
            'description_ar' => $request->description_ar ?? $trip->description_ar,
            'price' => $request->price ?? $trip->price,
            'start_point' => $request->start_point ?? $trip->start_point,
            'program_time' => $request->program_time ?? $trip->program_time,
            'people' => $request->people ?? $trip->people,
            'free_cancelation' => $request->free_cancelation ?? $trip->free_cancelation,
            'available_days' => $request->available_days ?? $trip->available_days,
            'available_times' => $request->available_times ?? $trip->available_times,
            'pay_later' => $request->pay_later ?? $trip->pay_later,
            'city_id' => $request->city_id ?? $trip->city_id,
            'updated_by' => auth()->user()->id,
        ];
        $item = $this->service->update($data, $trip);
        if ($item) {
            if ($request->filled('sub_category_ids')) {
                TripSubCategory::where('trip_id', $trip->id)->delete();
            }
            foreach ($request->sub_category_ids as $sub_category_id) {
                $feature = [
                    'trip_id' => $trip->id,
                    'sub_category_id' => $sub_category_id,
                ];
                TripSubCategory::create($feature);
            }
            if ($request->filled('featurs')) {
                TripFeature::where('trip_id', $trip->id)->delete();
            }
            foreach ($request->featurs as $feature_data) {
                $feature = [
                    'trip_id' => $trip->id,
                    'title_ar' => $feature_data['title_ar'] ?? null,
                    'title_en' => $feature_data['title_en'] ?? null,
                    'description_en' => $feature_data['description_en'] ?? null,
                    'description_ar' => $feature_data['description_ar'] ?? null,
                ];
                TripFeature::create($feature);
            }
            $images = $request->file('images');
            if ($request->filled('images')) {
                Attachment::where('model_id', $trip->id)->where('model_type', 'trip')->delete();
            }
            foreach ($images as $image) {
                $storedPath = $this->storageService->storeFile($image, $folder_path);
                $attachment = [
                    'model_id' => $trip->id,
                    'model_type' => 'trip',
                    'attachment' => $storedPath,
                    'title' => "trip",
                ];
                Attachment::create($attachment);
            }
        }

        return response()->apiSuccess($item);
    }
    public function delete(Trip $trip)
    {

        return response()->apiSuccess($this->service->delete($trip));
    }

}
