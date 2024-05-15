<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'tags'=>$this->tags,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'active'=>$this->active,
            'title'=>$this->title,
            'description'=>$this->description,
            'attachments'=>$this->attachments,
        ];
    }

}
