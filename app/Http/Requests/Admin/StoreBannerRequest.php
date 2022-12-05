<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
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
        $rules =  [
            'title' => 'required|min:3|max:255',
            'subtitle' => 'nullable|max:255',
            'action' => 'required|min:5|max:255',
            'store_id' => 'required|exists:stores,id',
        ];
        if($this->_method != 'PUT')
        $rules['image'] = 'required|file|mimes:png,jpg,jpeg,gif';

        return $rules;
    }
}
