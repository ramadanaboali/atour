<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EffectivenesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
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
            'created_by' => $this->createdBy?->name,
            'vendor' => new UserResource($this->vendor),
            'attachments' => AttachmentResource::collection($this->attachments),

        ];
    }

}
