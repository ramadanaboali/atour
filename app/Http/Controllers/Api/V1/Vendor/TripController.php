<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\TripRequest;
use App\Http\Resources\TripResource;
use App\Models\Attachment;
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
        $input = $this->service->inputs($request->all());
        $model = new Trip();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->service->whereOptions($input, $columns);
        }
        $data = $this->service->Paginate($input, $wheres);

        return response()->apiSuccess($data);

    }

    public function show($id)
    {
        $data = new TripResource($this->service->get($id));
        return response()->apiSuccess($data);
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
            'pay_later' => $request->pay_later,
            'city_id' => $request->city_id,
            'created_by' => auth()->user()->id,
        ];
        $item = $this->service->store($data);
        if ($item ) {
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
        return response()->apiSuccess($item);
    }

    public function update(TripRequest $request, Trip $trip)
    {

        $data = $request->except(['cover','_method']);
        if ($request->hasFile('cover')) {
            $folder_path = "images/Trip";
            $storedPath = null;
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
            $data['cover'] = $storedPath;
        }
        return response()->apiSuccess($this->service->update($data, $trip));
    }
    public function delete(Trip $trip)
    {

        return response()->apiSuccess($this->service->delete($trip));
    }

}
