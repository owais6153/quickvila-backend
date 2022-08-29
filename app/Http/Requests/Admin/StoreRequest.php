<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'logo' => 'nullable|mimes:png,jpg,jpeg,gif',
            'cover' => 'nullable|mimes:png,jpg,jpeg,gif',
            'description' => 'nullable',
            'url' => 'nullable|min:5',
            'address' => 'nullable|mi',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'category' => 'nullable|numeric',
        ];
    }
}
