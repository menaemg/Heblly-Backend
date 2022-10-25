<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\BaseFormRequest;

class UpdatePostRequest extends BaseFormRequest
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
            'body' => 'string',
            'main_image' => 'image',
            'images' => 'array',
            'images.*' => 'image',
            'location' => 'string',
            'tags' => 'array',
            'tags.*' => 'string|max:255',
            'privacy' => 'in:public,private',
        ];
    }
}
