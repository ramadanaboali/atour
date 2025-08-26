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
            'description'  => $this->description,
            'url'  => $this->url,
            'image'  => $this->photo,
        ];
    }

}
