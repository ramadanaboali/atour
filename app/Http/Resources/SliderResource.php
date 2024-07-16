<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'    => $this->id,
            'title'  => $this->title,
            'url'  => $this->url,
            'description'  => $this->description,
            'image'  => $this->photo,
        ];
    }

}
