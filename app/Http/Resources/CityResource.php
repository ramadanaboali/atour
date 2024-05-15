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
            'country_name'  => $this->country?->title,
        ];
    }

}
