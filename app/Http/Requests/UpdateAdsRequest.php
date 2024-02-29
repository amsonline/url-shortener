<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ad_publisher_id' => 'nullable|string|max:20',
            'ad_top_unit_id' => 'nullable|string|max:20',
            'ad_left_unit_id' => 'nullable|string|max:20',
            'ad_right_unit_id' => 'nullable|string|max:20',
            'ad_bottom_unit_id' => 'nullable|string|max:20',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
