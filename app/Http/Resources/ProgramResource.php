<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            'price'=>$this->price,
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_point,
            'photo'=>$this->photo,
            'trip_id'=>$this->trip_id,
            'trip_name'=>$this->trip->title,
            'created_by_id'=>$this->created_id,
            'created_by_name'=>$this->createdBy?->name,
            'updated_by_id'=>$this->updated_by,
            'updated_by_name'=>$this->updatedBy?->name,
        ];
    }

}
