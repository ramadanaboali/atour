<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OnboardingResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'    => $this->id,
            'title'  => $this->title,
            'description'  => $this->description,
            'image'  => $this->photo,
        ];
    }

}
