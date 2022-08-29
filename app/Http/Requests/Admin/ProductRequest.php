<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'short_description' => 'nullable|max:255',
            'description' => 'nullable',
            'store' => 'required|exists:stores,id',
            'gallery.*' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'category.*' => 'nullable|numeric',
        ];
    }
}
