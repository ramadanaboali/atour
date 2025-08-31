<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'    => $this->id,
            'title'  => $this->title,
            'description'  => $this->description,
            'photo'  => $this->photo,
            'url'  => $this->url,
            'created_at'  => $this->created_at?->toDateTimeString(),
        ];
    }

}
