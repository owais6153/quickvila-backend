<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

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
        return [
            'name' => 'required|min:3|max:255',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'short_description' => 'nullable|max:255',
            'price' => 'required|numeric|min:1',
            'sale_price' => 'nullable|numeric|min:1',
            'description' => 'nullable',
            'store' => 'required|exists:stores,id',
            'gallery.*' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'categories.*' => 'nullable|numeric',
            'product_type' => 'required',
            'status' => 'required',
            'p_attributes' =>  'nullable',
            // Variations
            'variations' => 'required_if:product_type,variation',
            'variations.*.name' => 'required_if:product_type,variation|max:255',
            'variations.*.price' => 'required_if:product_type,variation|numeric|min:1',
            'variations.*.sale_price' => 'nullable|numeric|min:1',
            'variations.*.variants' => 'required_if:product_type,variation',
        ];
    }
}
