<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use App\Models\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftResourceCustomer extends JsonResource
{
    public function toArray($request)
    {
        $favourit = Favorite::where('model_type', 'like', 'gift%')->where('model_id', $this->id)->where('user_id', auth()->user()->id ?? 0)->first();

$price = $this->price + $this->calculateAdminFees();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $price,
            'free_cancelation' => $this->free_cancelation,
            'pay_later' => $this->pay_later,
            'rate' => $this->rate,
            'cover' => $this->photo,
            'active' => $this->active,
            'long' => $this->long,
            'lat' => $this->lat,
            'created_by' => $this->createdBy?->name,
            'attachments' => AttachmentResource::collection($this->attachments),
            'sub_categories' => SubCategoryResource::collection($this->subcategory),
            'city' => new CityResource($this->city),
            'vendor' => new UserResource($this->vendor),
            'is_favourit' => $favourit ? 1 : 0,
            'booking_count' => bookingCount($this->id, 'gift'),
            'total_amounts' => totalAmount($this->id, 'gift'),
            'use_coupon' => useCoupon($this->id, 'gift'),
            'use_offers' => useOffers($this->id, 'gift'),
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'location_en' => $this->location_en,
            'location_ar' => $this->location_ar,


        ];
    }


}
