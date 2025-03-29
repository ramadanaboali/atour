<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderCustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'tourist_name' => $this->tourist_name,
            'tourist_email' => $this->tourist_email,
            'tourist_phone' => $this->tourist_phone,
            'promocode' => $this->promocode,
            'promocode_value' => $this->promocode_value,
            'payment_type' => $this->payment_type,
            'payment_status' => $this->payment_status,
            'booking_day' => $this->booking_day,
            'order_date' => $this->order_date,
            'order_time' => $this->order_time,
            'address' => $this->address,
            'type' => $this->type,
            'status' => $this->status,
            'total' => $this->customer_total,
            'members' => $this->members,
            'childrens' => $this->childrens,
            'adults' => $this->adults,
            'program_id' => $this->program_id,
            'city' => new CityResource($this->trip?->city),
            'trip_id' => $this->trip_id,
            'user_id' => $this->user_id,
            'program_name' => $this->program?->title,
            'trip_name' => $this->trip?->title,
            'client_name' => $this->client?->name,
        ];
    }

}
