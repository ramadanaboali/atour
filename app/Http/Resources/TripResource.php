<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use App\Models\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray($request)
    {
        $favourit = Favorite::where('model_type', 'trip')->where('model_id', $this->id)->where('user_id', auth()->user()->id)->first();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'start_point' => $this->start_point,
            'program_time' => $this->program_time,
            'people' => $this->people,
            'free_cancelation' => $this->free_cancelation,
            'available_days' => $this->available_days,
            'available_times' => $this->available_times,
            'pay_later' => $this->pay_later,
            'rates' => RateResource::collection($this->rates),
            'cover' => $this->photo,
            'active' => $this->active,
            'created_by' => $this->createdBy?->name,
            'attachments' => AttachmentResource::collection($this->attachments),
            'sub_categories' => SubCategoryResource::collection($this->subcategory),
            'features' => FeatureResource::collection($this->features),
            'city' => new CityResource($this->city),
            'vendor' => new UserResource($this->vendor),
            'offers' => $this->offers,
            'is_favourit' => $favourit ? 1 : 0,
            'booking_count' => 0,
            'total_amounts' => 0,
            'use_coupon' => 0,
            'use_offers' => 0,
        ];
    }

}
