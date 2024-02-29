<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'url_id' => 'required|numeric|exists:urls,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
