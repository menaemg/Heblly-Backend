<?php

namespace App\Http\Requests\Gift;

use App\Http\Requests\BaseFormRequest;

class UpdateGiftRequest extends BaseFormRequest
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
            'title' => 'string',
            'body' => 'nullable|string',
            'gift_for' => 'exists:users,id',
            'main_image' => 'image',
            'images' => 'nullable|array',
            'images.*' => 'image',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'location' => 'nullable|string',
            'privacy' => 'in:public,private',
            'access_list' => 'nullable|array',
            'access_list.*' => 'integer|exists:users,id',
            'post_id' => 'nullable|integer|exists:posts,id',
        ];
    }
}
