<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\GiftRequest;
use App\Http\Resources\GiftResource;
use App\Models\Attachment;
use App\Models\Gift;
use App\Models\GiftSubCategory;
use App\Models\GiftTranslation;
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
        $locale = $request->header('lang', 'en');

        $folder_path = "images/gifts";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }

        $data = [
            'cover' => $storedPath,
            'vendor_id' => auth()->user()->id,
            'long' => $request->long,
            'lat' => $request->lat,
            'price' => $request->price,
            'free_cancelation' => $request->free_cancelation,
            'pay_later' => $request->pay_later,
            'city_id' => $request->city_id,
            'quantity' => $request->quantity ?? 0,
            'created_by' => auth()->user()->id,
        ];

        $item = $this->service->store($data);

        if ($item) {
            // Create translation
            GiftTranslation::create([
                'gift_id' => $item->id,
                'locale' => $locale,
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
            ]);

            // Handle subcategories
            if ($request->sub_category_ids) {
                foreach ($request->sub_category_ids as $sub_category_id) {
                    GiftSubCategory::create([
                        'gift_id' => $item->id,
                        'sub_category_id' => $sub_category_id,
                    ]);
                }
            }

            // Handle images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $i = 0;
                foreach ($images as $image) {
                    $storedPath = $this->storageService->storeFile($image, $folder_path, $i);
                    Attachment::create([
                        'model_id' => $item->id,
                        'model_type' => 'gift',
                        'attachment' => $storedPath,
                        'title' => "gift",
                    ]);
                    $i++;
                }
            }
        }

        return response()->apiSuccess(new GiftResource($item));
    }

    public function update(GiftRequest $request, Gift $gift)
    {
        $locale = $request->header('lang', 'en');

        $folder_path = "images/gifts";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }

        $data = [
            'cover' => $storedPath ?? $gift->cover,
            'long' => $request->long ?? $gift->long,
            'lat' => $request->lat ?? $gift->lat,
                        'quantity' => $request->quantity ?? $gift->quantity,

            'price' => $request->price ?? $gift->price,
            'free_cancelation' => $request->free_cancelation ?? $gift->free_cancelation,
            'pay_later' => $request->pay_later ?? $gift->pay_later,
            'city_id' => $request->city_id ?? $gift->city_id,
            'quantity' => $request->quantity ?? $gift->quantity,
            'updated_by' => auth()->user()->id,
        ];

        $item = $this->service->update($data, $gift);

        if ($item) {
            // Update or create translation
            $translation = GiftTranslation::where('gift_id', $gift->id)
                ->where('locale', $locale)
                ->first();

            $translationData = [
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
            ];

            if ($translation) {
                $translation->update(array_filter($translationData, fn ($value) => $value !== null));
            } else {
                GiftTranslation::create(array_merge([
                    'gift_id' => $gift->id,
                    'locale' => $locale,
                ], array_filter($translationData, fn ($value) => $value !== null)));
            }

            // Handle subcategories
            if ($request->filled('sub_category_ids')) {
                GiftSubCategory::where('gift_id', $gift->id)->delete();
                foreach ($request->sub_category_ids as $sub_category_id) {
                    GiftSubCategory::create([
                        'gift_id' => $gift->id,
                        'sub_category_id' => $sub_category_id,
                    ]);
                }
            }

            // Handle images
            if ($request->hasFile('images')) {
                Attachment::where('model_id', $gift->id)->where('model_type', 'gift')->delete();
                $images = $request->file('images');
                $i = 0;
                foreach ($images as $image) {
                    $storedPath = $this->storageService->storeFile($image, $folder_path, $i);
                    Attachment::create([
                        'model_id' => $gift->id,
                        'model_type' => 'gift',
                        'attachment' => $storedPath,
                        'title' => "gift",
                    ]);
                    $i++;
                }
            }
        }

        return response()->apiSuccess($item);
    }
    public function delete($id)
    {
        $gift = $this->service->get($id);
        return response()->apiSuccess($this->service->delete($gift));
    }

}
