<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use App\Models\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray($request)
    {

        $favourit = Favorite::where('model_type', 'trip')->where('model_id', $this->id)->where('user_id', auth()->user()->id ?? 0)->first();
        $total_rates = 0;
        if(count($this->rates)){
            $total_rates=$this->rates->sum('rate')/count($this->rates);
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'customer_price' => $this->customer_price,

            'start_point' => $this->start_point,
            'end_point' => $this->end_point,
            'trip_requirements' => RequirementResource::collection($this->requirements),
            'program_time' => $this->program_time,
            'start_long' => $this->start_long,
            'start_lat' => $this->start_lat,
            'end_long' => $this->end_long,
            'end_lat' => $this->end_lat,
            'steps_list' => $this->steps_list??[],
            'people' => $this->people,
            'free_cancelation' => $this->free_cancelation,
            'available_days' => $this->available_days,
            'available_times' => $this->available_times,
            'pay_later' => $this->pay_later,
            'rates' => RateResource::collection($this->rates),
            'total_rates' => $total_rates,
            'cover' => $this->photo,
            'active' => $this->active,
            'created_by' => $this->createdBy?->name,
            'attachments' => AttachmentResource::collection($this->attachments),
            'sub_categories' => SubCategoryResource::collection($this->subcategory),
            'features' => FeatureResource::collection($this->features),
            'city' => new CityResource($this->city),
            'vendor' => new UserResource($this->vendor),
            'offers' => $this->offers,
            'is_favourit' => $favourit ? 1 : 0,
            'booking_count' => bookingCount($this->id,'trip'),
            'total_amounts' => totalAmount($this->id,'trip'),
            'use_coupon' => useCoupon($this->id,'trip'),
            'use_offers' => useOffers($this->id,'trip'),
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'start_point_en' => $this->start_point_en,
            'start_point_ar' => $this->start_point_ar,
            'end_point_en' => $this->end_point_en,
            'end_point_ar' => $this->end_point_ar,
            'program_time_en' => $this->program_time_en,
            'program_time_ar' => $this->program_time_ar,
        ];
    }

}
