<?php
namespace App\Rules;

use App\Models\Service;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class RateRule implements Rule
{
    protected $type;
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->type == "vendor") {
                return User::find($value)?true:false;
        }
        if ($this->type == "service") {
                return Service::find($value)?true:false;
        }
        if ($this->type == "trip") {
                return Trip::find($value)?true:false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute not exist.';
    }
}
