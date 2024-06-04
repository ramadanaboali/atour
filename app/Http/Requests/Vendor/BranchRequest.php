<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BranchRequest extends FormRequest
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
                        'name' => 'required|string|min:2',
                        'manager_id' => 'required|exists:users,id',
                        'country_id' => 'required|exists:countries,id',
                        'region_id' => 'required|exists:regions,id',
                        'city_id' => 'required|exists:cities,id',
                        'address' => 'required|string|min:2',
                        'lat' => 'required|string|min:2',
                        'long' => 'required|string|min:2',
                        'image' => 'required|image|mimes:png,jpg,jpeg',
                        'images' => 'nullable|array',
                        'images.*' =>'image|mimes:png,jpg,jpeg',
                        'officialHours' => 'required|array',
                        'officialHours.*.day' =>'required|in:sat,sun,mon,tue,wed,thu,fri',
                        'officialHours.*.start_time' =>'required|date_format:H:i',
                        'officialHours.*.end_time' =>'required|date_format:H:i',
                        'show_rates' =>'required|in:0,1',
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    $rules = [

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
