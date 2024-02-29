<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQRSettingsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'qr_size' => 'required|integer|between:100,300|in:100,125,150,175,200,225,250,275,300',
            'qr_style' => 'required|string|in:square,dot,round',
            'qr_eye' => 'required|string|in:square,circle',
            'qr_quality' => 'required|string|in:L,M,Q,H',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
