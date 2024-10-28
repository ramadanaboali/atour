<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\EffectivenesRequest;
use App\Http\Resources\EffectivenesResource;
use App\Models\Attachment;
use App\Models\Effectivenes;
use App\Services\General\StorageService;
use App\Services\Vendor\EffectivenesService;

use function response;

class EffectivenesController extends Controller
{
    protected EffectivenesService $service;
    protected StorageService $storageService;

    public function __construct(EffectivenesService $service, StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $data = Effectivenes::where('vendor_id', auth()->user()->id)->paginate($request->per_page ?? 30);
        return response()->apiSuccess(EffectivenesResource::collection($data));

    }

    public function show($id)
    {
        $data = new EffectivenesResource($this->service->get($id));
        return response()->apiSuccess($data);
    }

    public function store(EffectivenesRequest $request)
    {

        $folder_path = "images/effectiveness";
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
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'lat' => $request->lat,
            'long' => $request->long,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'price' => $request->price,
            'people' => $request->people,
            'free_cancelation' => $request->free_cancelation,
            'pay_later' => $request->pay_later,
            'city_id' => $request->city_id,
            'created_by' => auth()->user()->id,
        ];
        $item = $this->service->store($data);

        $images = $request->file('images');
        foreach ($images as $image) {
            $storedPath = $this->storageService->storeFile($image, $folder_path);
            $attachment = [
                'model_id' => $item->id,
                'model_type' => 'effectivenes',
                'attachment' => $storedPath,
                'title' => "effectivenes",
            ];
            Attachment::create($attachment);
        }

        return response()->apiSuccess(new EffectivenesResource($item));
    }

    public function update(EffectivenesRequest $request, Effectivenes $effectivenes)
    {
        $folder_path = "images/effectiveness";
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
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'lat' => $request->lat,
            'long' => $request->long,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'price' => $request->price,
            'people' => $request->people,
            'free_cancelation' => $request->free_cancelation,
            'pay_later' => $request->pay_later,
            'city_id' => $request->city_id,
            'created_by' => auth()->user()->id,
        ];
        $item = $this->service->update($data, $effectivenes);
        if ($item) {
            $images = $request->file('images');
            Attachment::where('model_id', $effectivenes->id)->where('model_type', 'effectivenes')->delete();
            foreach ($images as $image) {
                $storedPath = $this->storageService->storeFile($image, $folder_path);
                $attachment = [
                    'model_id' => $effectivenes->id,
                    'model_type' => 'effectivenes',
                    'attachment' => $storedPath,
                    'title' => "effectivenes",
                ];
                Attachment::create($attachment);
            }
        }

        return response()->apiSuccess($item);
    }
    public function delete(Effectivenes $effectivenes)
    {

        return response()->apiSuccess($this->service->delete($effectivenes));
    }

}
