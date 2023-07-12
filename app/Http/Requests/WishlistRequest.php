<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class WishlistRequest extends BaseFormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'privacy' => 'in:private,public,friends',
            'access_list' => 'nullable|array',
            'access_list.*' => 'exists:users,id',
        ];
    }
}
