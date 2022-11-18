<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

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
    public function validationData()
    {
        $this->merge([
            'user_id' => Auth::user()->id,
        ]);

        return $this->all();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:255',
            'logo' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'cover' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'description' => 'nullable',
            'url' => 'nullable|min:5',
            'status' => 'required|min:3|max:255',
            'type' => 'required|min:3|max:255',
            'address' => 'nullable|min:3',
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-180,180',
            'category.*' => 'nullable|numeric',
        ];

        if($this->method() != 'PUT'){
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required';
            $rules['confirm_password'] = 'required|same:password';
        }
        return $rules;
    }
    public function messages()
    {
        return [
            'latitude.between' => 'The latitude must be in range between -90 and 90',
            'longitude.between' => 'The longitude must be in range between -180 and 180'
        ];
    }
}
