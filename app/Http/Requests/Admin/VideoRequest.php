<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
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
            'video' => ($this->method() == 'PUT') ? 'nullable|file|mimes:mp4' : 'required|file|mimes:mp4',
            'thumbnail' => ($this->method() == 'PUT') ? 'nullable|file|mimes:png,jpg,jpeg,gif' : 'required|file|mimes:png,jpg,jpeg,gif',
            'sort' => 'nullable|numeric',
        ];
    }
}
