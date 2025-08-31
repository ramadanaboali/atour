<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $data =[];
        $data['id']       = $this->id;
        $data['code']     = $this->code;
        $data['name']     = $this->name;
        $data['phone']    = $this->phone;
        $data['image']    = $this->photo;
        return $data;
    }

}
