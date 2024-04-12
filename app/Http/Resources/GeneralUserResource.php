<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralUserResource extends JsonResource
{
    public function toArray($request)
    {
        $data =[];
        $data['id']         = $this->id;
        $data['name']       = $this->name;
        $data['email']      = $this->email;
        $data['wallet']     = $this->wallet;
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        $data['image'] = !empty($this->attachments) ? uploaded_asset($this->attachments) : '';
        return $data;
    }

    public function with($request)
    {
        return [
            'version' => '1.0',
            'success' => true,
            'status'  => 200
        ];
    }
}
