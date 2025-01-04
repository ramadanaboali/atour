<?php

namespace App\Http\Resources;

use App\Models\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $this->price,
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
            'booking_count' => 0,
            'total_amounts' => 0,
            'use_coupon' => 0,
            'use_offers' => 0,
        ];
    }

}
