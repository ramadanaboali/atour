<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EffectivenesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'people' => $this->people,
            'date'=>$this->date,
            'time'=>$this->time,
            'location'=>$this->location,
            'lat'=>$this->lat,
            'long'=>$this->long,
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
            'booking_count' => 0,
            'total_amounts' => 0,
            'use_coupon' => 0,
            'use_offers' => 0,

        ];
    }

}
