<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'title' => 'nullable|string',
            'from_id' => 'required|exists:users,id',
            'main_image' => 'required|image',
            'location' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'body' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image',
            'privacy' => 'nullable|string|in:public,private',
            'access_list' => 'nullable|array',
            'access_list.*' => 'exists:users,id',
        ];
    }
}
