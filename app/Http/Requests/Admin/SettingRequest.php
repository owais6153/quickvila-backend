<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'key' => 'required|max:100',
            // Store
            'setting.default_price' => 'exclude_unless:key,store|required',
            'setting.default_price_condition' => 'exclude_unless:key,store|required',
            'setting.tax' => 'exclude_unless:key,store|required',
            'setting.default_radius' => 'exclude_unless:key,store|required',
        ];
    }
}
