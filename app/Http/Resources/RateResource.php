<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'comment'    => $this->comment,
            'rate'    => $this->rate,
            'model_id'    => $this->model_id,
            'model_type'    => $this->model_type,
            'trip_id'    => $this->trip_id,
            'user_id'    => $this->user_id,
            'created_by'    => $this->created_by,
            'updated_by'    => $this->updated_by,
            'images'  => $this->attachments,
        ];
    }

}
