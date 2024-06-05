<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            'price'=>$this->price,
            'phone'=>$this->phone,
            'start_point'=>$this->start_point,
            'end_point'=>$this->end_point,
            'photo'=>$this->photo,
            'free_cancelation'=>$this->free_cancelation,
            'cancelation_policy'=>$this->cancelation_policy,
            'start_point_descriprion'=>$this->start_point_descriprion,
            'end_point_descriprion'=>$this->end_point_descriprion_en,
            'active'=>$this->active,
            'pay_later'=>$this->pay_later,
            'vendor_id'=>$this->vendor_id,
            'vendor_name'=>$this->vendor?->name,
            'created_by_id'=>$this->created_id,
            'created_by_name'=>$this->createdBy?->name,
            'updated_by_id'=>$this->updated_by,
            'updated_by_name'=>$this->updatedBy?->name,
            'programs' => ProgramResource::collection($this->programs)
        ];
    }

}
