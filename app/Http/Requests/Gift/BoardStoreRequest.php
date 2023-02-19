<?php

namespace App\Http\Requests\Gift;

use App\Http\Requests\BaseFormRequest;

class BoardStoreRequest extends BaseFormRequest
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
            'main_image' => 'required|image|max:2048',
            'location' => 'string',
            'tags' => 'array',
            'tags.*' => 'string',
            'body' => 'string',
            'images' => 'array',
            'images.*' => 'image',
            'privacy' => 'string|in:public,private',
            'access_list' => 'array',
            'access_list.*' => 'exists:users,id',
            'for_id' => 'required|exists:users,id',
        ];
    }
}
