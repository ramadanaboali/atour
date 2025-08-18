<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'    => $this->id,
            'title' => $this->translations->first()->title ?? null,

        ];
    }

}
