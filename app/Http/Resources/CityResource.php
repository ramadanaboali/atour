<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'    => $this->id,
            'title'  => $this->title,
            'description'  => $this->description,
            'image'  => $this->photo,
            'country_name'  => $this->country?->title,
            'country_id'  => $this->country_id,
        ];
    }

}
