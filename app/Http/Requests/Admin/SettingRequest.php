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
            // SMS
            'setting.sid' => 'exclude_unless:key,sms|required',
            'setting.token' => 'exclude_unless:key,sms|required',
            'setting.number' => 'exclude_unless:key,sms|required',
            'setting.whatsapp' => 'exclude_unless:key,sms|required',
            'setting.messaging_service' => 'exclude_unless:key,sms|required',
            // EMAIL
            'setting.should_send' => 'exclude_unless:key,email|required',
            'setting.host' => 'exclude_unless:key,email|required',
            'setting.port' => 'exclude_unless:key,email|required',
            'setting.username' => 'exclude_unless:key,email|required',
            'setting.password' => 'exclude_unless:key,email|required',
            'setting.encryption' => 'exclude_unless:key,email|required',
            // Tax
            'setting.platform_fees' => 'exclude_unless:key,tax|required',
            'setting.tax' => 'exclude_unless:key,tax|required',
        ];
    }
}
