<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeContactUsRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email:rfc,dns',
            'title' => 'required|string|max:255',
            'phone' => ['bail', 'required', 'regex:/(^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$)/u'],
            'message' => 'required|string|max:255',
            'terms_and_privacy' => 'accepted',

        ];
    }
}
