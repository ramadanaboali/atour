<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\GiftRequest;
use App\Http\Resources\GiftResource;
use App\Models\Attachment;
use App\Models\Gift;
use App\Models\GiftSubCategory;
use App\Services\General\StorageService;
use App\Services\Vendor\GiftService;

use function response;

class GiftController extends Controller
{
    protected GiftService $service;
    protected StorageService $storageService;

    public function __construct(GiftService $service, StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $data = Gift::where('vendor_id', auth()->user()->id)->paginate($request->per_page ?? 30);
        return response()->apiSuccess(GiftResource::collection($data));

    }

    public function show($id)
    {
        $data = new GiftResource($this->service->get($id));
        return response()->apiSuccess($data);
    }

    public function store(GiftRequest $request)
    {

        $folder_path = "images/gifts";
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
            'free_cancelation' => $request->free_cancelation,
            'pay_later' => $request->pay_later,
            'city_id' => $request->city_id,
            'created_by' => auth()->user()->id,
        ];
        $item = $this->service->store($data);
        if ($item) {
            foreach ($request->sub_category_ids as $sub_category_id) {
                $feature = [
                    'gift_id' => $item->id,
                    'sub_category_id' => $sub_category_id,
                ];
                GiftSubCategory::create($feature);
            }

            $images = $request->file('images');
            foreach ($images as $image) {
                $storedPath = $this->storageService->storeFile($image, $folder_path);
                $attachment = [
                    'model_id' => $item->id,
                    'model_type' => 'gift',
                    'attachment' => $storedPath,
                    'title' => "gift",
                ];
                Attachment::create($attachment);
            }
        }
        return response()->apiSuccess(new GiftResource($item));
    }

    public function update(GiftRequest $request, Gift $gift)
    {


        $folder_path = "images/gifts";
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
            'free_cancelation' => $request->free_cancelation,
            'pay_later' => $request->pay_later,
            'city_id' => $request->city_id,
            'created_by' => auth()->user()->id,
        ];
        $item = $this->service->update($data, $gift);
        if ($item) {
            if ($request->filled('sub_category_ids')) {
                GiftSubCategory::where('gift_id', $gift->id)->delete();
            }
            foreach ($request->sub_category_ids as $sub_category_id) {
                $feature = [
                    'gift_id' => $gift->id,
                    'sub_category_id' => $sub_category_id,
                ];
                GiftSubCategory::create($feature);
            }

            $images = $request->file('images');
            if ($request->filled('images')) {
                Attachment::where('model_id', $gift->id)->where('model_type', 'gift')->delete();
            }
            foreach ($images as $image) {
                $storedPath = $this->storageService->storeFile($image, $folder_path);
                $attachment = [
                    'model_id' => $gift->id,
                    'model_type' => 'gift',
                    'attachment' => $storedPath,
                    'title' => "gift",
                ];
                Attachment::create($attachment);
            }
        }

        return response()->apiSuccess($item);
    }
    public function delete(Gift $gift)
    {

        return response()->apiSuccess($this->service->delete($gift));
    }

}
