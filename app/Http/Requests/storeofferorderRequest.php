<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeofferorderRequest extends FormRequest
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
            'email' => 'required|string|max:255|email:rfc,dns',
            'city_id' => 'required|exists:cities,id',
            'car_id' => "required|exists:cars,id",

            'terms_and_privacy' => 'accepted',

        ];
    }
}
