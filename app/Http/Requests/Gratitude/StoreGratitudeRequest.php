<?php

namespace App\Http\Requests\Gratitude;

use App\Http\Requests\BaseFormRequest;

class StoreGratitudeRequest extends BaseFormRequest
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
            'title' => 'required|string',
            'body' => 'required|string',
            'main_image' => 'required|image',
            'images' => 'array',
            'images.*' => 'image',
            'tags' => 'array',
            'tags.*' => 'string|max:255',
            'location' => 'string',
            'privacy' => 'in:public,private',
            'access_list' => 'array',
            'access_list.*' => 'integer|exists:users,id',
            'gift_id' => 'nullable|integer|exists:gifts,id',
        ];
    }
}
