<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'site_name' => 'required|string|min:4|max:30',
            'url_length' => 'required|integer|min:4|max:8',
            'social.*' => 'in:facebook,instagram,twitter,pinterest,linkedin,whatsapp,telegram,snapchat',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
