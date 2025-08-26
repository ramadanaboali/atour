<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'transaction_id' => 'required|string|max:255',
            'service_type' => 'required|in:tour,event,gift',
            'service_id' => 'required|integer|min:1',
            'supplier_id' => 'required|integer|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'transaction_id.required' => __('ratings.transaction_required'),
            'service_type.required' => __('ratings.service_required'),
            'service_type.in' => __('ratings.invalid_service_type'),
            'service_id.required' => __('ratings.service_required'),
            'service_id.integer' => __('ratings.service_id_invalid'),
            'supplier_id.required' => __('ratings.supplier_required'),
            'supplier_id.exists' => __('ratings.supplier_not_found'),
            'rating.required' => __('ratings.rating_required'),
            'rating.between' => __('ratings.rating_between'),
            'comment.max' => __('ratings.comment_max'),
            'customer_name.max' => __('ratings.name_max'),
            'customer_email.email' => __('ratings.email_valid'),
            'customer_email.max' => __('ratings.email_max'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => __('ratings.validation_failed'),
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
