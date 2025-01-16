<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use App\Models\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'lat' => $this->lat,
            'lang' => $this->long,
            'type' => $this->type,
            

        ];
    }

}
