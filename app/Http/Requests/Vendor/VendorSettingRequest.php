<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class VendorSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'stock_alert' => 'required|integer|min:0',
                        'calender_differance' => 'required|integer|min:10:max:30',
                        'calender_differance_type' => 'required|in:fixed,vary',
                        'booking_differance' => 'required|integer|min:0',
                        'booking_range' => 'required|in:one_month,two_month,three_month,four_month,five_month,six_month,seven_month,eight_month,nine_month,ten_month,eleven_month,twelve_month',
                        'cancel_booking' => 'required|integer|min:0',
                        'reschedule_booking' => 'required|integer|min:0',
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    $rules = [
                        'stock_alert' => 'required|integer|min:0',
                        'calender_differance' => 'required|integer|min:10:max:30',
                        'calender_differance_type' => 'required|in:fixed,vary',
                        'booking_differance' => 'required|integer|min:0',
                        'booking_range' => 'required|in:one_month,two_month,three_month,four_month,five_month,six_month,seven_month,eight_month,nine_month,ten_month,eleven_month,twelve_month',
                        'cancel_booking' => 'required|integer|min:0',
                        'reschedule_booking' => 'required|integer|min:0',
                    ];
                    return $rules;
                }
            default:
                return [];
        }

    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
