<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class RoomUpdateRequest extends FormRequest
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
            'price'       => 'integer|between:0, 18446744073709551615', // 18446744073709551615  - max UNSIGNED INT in MySQL
            'description' => 'string|min:25|max:8191',
            'active'      => 'boolean',
        ];
    }
}
