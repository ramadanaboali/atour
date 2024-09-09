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
            'free_cancelation' => $this->free_cancelation,
            'pay_later' => $this->pay_later,
            'rate' => $this->rate,
            'cover' => $this->photo,
            'active' => $this->active,
            'created_by' => $this->createdBy?->name,
            'city' => new CityResource($this->city),
            'vendor' => new UserResource($this->vendor)
        ];
    }

}
