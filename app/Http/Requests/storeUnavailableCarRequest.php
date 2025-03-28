<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeUnavailableCarRequest extends FormRequest
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
        // dd(request()->toArray());
        return [
            'name' => "required|max:255",
            'phone' => "required|numeric",
            'car_name' => "required|string",
            'city_name' => "required|string",
            'terms_and_privacy' => 'accepted',

        ];
    }
}
