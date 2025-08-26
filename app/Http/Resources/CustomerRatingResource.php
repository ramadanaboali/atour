<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'service_type' => $this->service_type,
            'service_id' => $this->service_id,
            'service_name' => $this->service_name,
            'rating' => $this->rating,
            'rating_text' => $this->rating_text,
            'stars' => $this->stars,
            'comment' => $this->comment,
            'is_verified' => $this->is_verified,
            'rated_at' => $this->rated_at->toISOString(),
            'rated_at_human' => $this->rated_at->diffForHumans(),
            'customer' => $this->when($this->relationLoaded('customer'), function () {
                return [
                    'id' => $this->customer?->id,
                    'name' => $this->customer_name ?: $this->customer?->name,
                    'email' => $this->customer_email ?: $this->customer?->email,
                ];
            }),
            'supplier' => $this->when($this->relationLoaded('supplier'), function () {
                return [
                    'id' => $this->supplier->id,
                    'name' => $this->supplier->name,
                    'email' => $this->supplier->email,
                ];
            }),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
