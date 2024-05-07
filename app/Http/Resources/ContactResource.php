<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'reason' => $this->reason,
            'problem' => $this->problem,
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status'  => 200
        ];
    }
}
