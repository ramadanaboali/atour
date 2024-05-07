<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute field must be accepted',
    'accepted_if' => 'The :attribute field is accepted if :other is equal to :value.',
    'active_url' => 'The :attribute field is not a valid link',
    'after' => 'The :attribute field must be a date after :date.',
    'after_or_equal' => 'The :attribute field must be a date after or the same as :date.',
    'alpha' => 'The :attribute field must contain only letters',
    'alpha_dash' => 'The :attribute field must not contain letters, numbers and accents.',
    'alpha_num' => 'The :attribute must contain only letters and numbers',
    'array' => 'The :attribute field must be an array',
    'before' => 'The :attribute field must be a date prior to :date.',
    'before_or_equal' => 'The :attribute field must be a date prior to or identical to :date',
    'between' => [
        'array' => 'The :attribute must contain a number of elements between :min and :max',
        'file' => 'The :attribute file size must be between :min and :max KB.',
        'numeric' => 'The value of :attribute must be between :min and :max.',
        'string' => 'The number of characters in the :attribute text must be between :min and :max',
    ],
    'boolean' => 'The value of the :attribute field must be either true or false',
    'confirmed' => 'The confirmation field does not match the :attribute field',
    'current_password' => 'The password is incorrect',
    'date' => 'The :attribute field is not a valid date',
    'date_equals' => 'The :attribute field is not equal to :date.',
    'date_format' => 'The :attribute field does not match the :format.',
    'declined' => 'The :attribute field must be rejected',
    'declined_if' => 'The :attribute field is rejected if :other equals :value.',
    'different' => 'The :attribute and :other fields must be different',
    'digits' => 'The :attribute field must contain :digits',
    'digits_between' => 'The :attribute field between :min and :max must contain a number(s).',
    'dimensions' => 'The :attribute contains invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => ':attribute must be a properly structured email address',
    'ends_with' => 'The :attribute must end with one of the following values: value.',
    'enum' => 'The :attribute field is invalid',
    'exists' => 'The :attribute field is null',
    'file' => 'The :attribute must be from a file.',
    'filled' => 'The :attribute field is mandatory',
    'gt' => [
        'array' => 'The :attribute must contain more than one :value element.',
        'file' => 'The :attribute must be larger than the :value in kilobytes.',
        'numeric' => 'The :attribute must be greater than :value.',
        'string' => 'The :attribute must be larger than the :value in characters.',
    ],
    'gte' => [
        'array' => 'The :attribute must contain one or more :value elements/items.',
        'file' => 'The :attribute must be greater than or equal to the :value in kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'string' => 'The :attribute must be greater than or equal to the :value in letters/characters.',
    ],
    'image' => 'The :attribute field must be an image',
    'in' => 'The :attribute field is null',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute field must be an integer',
    'ip' => 'The :attribute field must be a properly structured IP address',
    'ipv4' => 'The :attribute field must be a valid IPv4 address.',
    'ipv6' => 'The :attribute field must be a valid IPv6 address.',
    'json' => 'The :attribute field must be JSON text.',
    'lt' => [
        'array' => 'The :attribute must contain less than :value elements/element.',
        'file' => 'The :attribute must be less than the :value in kilobytes.',
        'numeric' => 'The :attribute must be less than :value.',
        'string' => 'The :attribute must be fewer than :value in characters.',
    ],
    'lte' => [
        'array' => 'The :attribute must contain more than one :value element.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'string' => 'The :attribute must be less than or equal to :value (characters).',
    ],
    'mac_address' => 'The MAC address :attribute field must be of the correct structure.',
    'max' => [
        'array' => 'The :attribute field must not contain more than :max elements/element.',
        'file' => 'The file size of :attribute :max must not exceed KB',
        'numeric' => 'The value of the :attribute field must be equal to or smaller than :max.',
        'string' => 'The :attribute :max text must be no more than 10 characters long',
    ],
    'mimes' => 'The field must be a file of type :values.',
    'mimetypes' => 'The field must be a file of type :values.',
    'min' => [
        'array' => 'The :attribute field must contain at least :min element(s).',
        'file' => 'The :attribute file must be at least :min KB in size',
        'numeric' => 'The value of the :attribute field must be equal to or greater than :min.',
        'string' => 'The :attribute text must be at least :min characters long',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The :attribute field is null',
    'not_regex' => 'The :attribute field type is null',
    'numeric' => 'The :attribute field must be a number',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be provided',
    'prohibited' => 'The :attribute field is blocked',
    'prohibited_if' => 'The :attribute field is blocked if :other equals :value.',
    'prohibited_unless' => 'The :attribute field is blocked unless :other equals :value.',
    'prohibits' => 'The :attribute field prohibits :other from being present',
    'regex' => 'The format of the field:attribute is incorrect',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for the following values: values.',
    'required_if' => 'The :attribute field is required if :other equals :value.',
    'required_unless' => 'The :attribute field is required unless :other equals :values.',
    'required_with' => 'The :attribute field if :values is provided.',
    'required_with_all' => 'The :attribute field if :values is provided.',
    'required_without' => 'The :attribute field if :values is not available.',
    'required_without_all' => 'The :attribute field if :values is not available.',
    'same' => 'The :attribute field must match :other',
    'size' => [
        'array' => 'The :attribute field must contain the exact :size of the item(s).',
        'file' => 'The :attribute :size file must be KB',
        'numeric' => 'The value of the :attribute field must be equal to :size',
        'string' => 'The :attribute text must contain exactly :size characters',
    ],
    'starts_with' => 'The :attribute field must begin with one of the following values: :values.',
    'string' => 'The :attribute field must be text.',
    'timezone' => ':attribute must be a valid date range',
    'unique' => 'The :attribute field value is already in use',
    'uploaded' => 'Failed to load :attribute',
    'url' => 'The :attribute link format is invalid',
    'uuid' => 'The :attribute field must be a valid UUID number.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name'                  => 'the name',
        'username'              => 'user name',
        'email'                 => 'E-mail',
        'first_name'            => 'the name',
        'last_name'             => 'family name',
        'password'              => 'password',
        'password_confirmation' => 'confirm password',
        'city'                  => 'City',
        'country'               => 'Country',
        'address'               => 'the address',
        'phone'                 => 'the phone',
        'mobile'                => 'cell phone',
        'age'                   => 'the age',
        'sex'                   => 'sex',
        'gender'                => 'Type',
        'day'                   => 'today',
        'month'                 => 'the month',
        'year'                  => 'the year',
        'hour'                  => 'hour',
        'minute'                => 'minute',
        'second'                => 'second',
        'content'               => 'Content',
        'description'           => 'the description',
        'excerpt'               => 'Summary',
        'date'                  => 'the date',
        'time'                  => 'the time',
        'available'             => 'available',
        'size'                  => 'the size',
        'price'                 => 'the price',
        'desc'                  => 'Brief',
        'title'                 => 'the address',
        'q'                     => 'search',
        'link'                  => 'Link',
        'slug'                  => 'slug',
    ],

];
