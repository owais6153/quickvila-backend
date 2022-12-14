<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonailRequest extends FormRequest
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
            'title' => 'required|min:3|max:255',
            'subtitle' => 'required|min:3|max:255',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'description' => 'nullable|max:255',
            'sort' => 'nullable|numeric',
        ];
    }
}
