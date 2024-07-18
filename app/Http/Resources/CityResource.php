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
            'country_id'  => $this->country_id,
            'description'  => $this->description,
            'image'  => $this->photo,
            'services'  => $this->services,
            'country_name'  => $this->country?->title,
        ];
    }

}
