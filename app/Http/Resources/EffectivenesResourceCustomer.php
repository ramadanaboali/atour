<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

class EffectivenesResourceCustomer extends JsonResource
{
    public function toArray($request)
    {
        $favourit = Favorite::where('model_type', 'like', 'effectivene%')->where('model_id', $this->id)->where('user_id', auth()->user()->id ?? 0)->first();
        $price = $this->price + $this->calculateAdminFees();

        return [
            'id' => $this->id,
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $price,
            'people' => $this->people,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'location' => $this->location,
            'lat' => $this->lat,
            'long' => $this->long,
            'rate' => $this->rate,
            'cover' => $this->photo,
            'city_id' => $this->city_id,
            'free_cancelation' => $this->free_cancelation,
            'pay_later' => $this->pay_later,
            'active' => $this->active,
            'created_by' => $this->createdBy?->name,
            'vendor' => new UserResource($this->vendor),
            'city' => new CityResource($this->city),
            'attachments' => AttachmentResource::collection($this->attachments),
            'is_favourit' => $favourit ? 1 : 0,
             'booking_count' => bookingCount($this->id, 'effectivenes'),
            'total_amounts' => totalAmount($this->id, 'effectivenes'),
            'use_coupon' => useCoupon($this->id, 'effectivenes'),
            'use_offers' => useOffers($this->id, 'effectivenes'),

        ];
    }

}
