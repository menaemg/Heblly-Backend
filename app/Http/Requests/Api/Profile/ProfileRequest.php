<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\BaseFormRequest;

class ProfileRequest extends BaseFormRequest
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
            'username' => 'string|alpha_dash|max:255|unique:users,username,' . auth()->user()->username . ',username',
            'email' => 'string|email|max:255|unique:users,email,' . auth()->user()->email . ',email',
            'name' => 'string|max:255',
            'bio' => 'string|max:255',
            'gender' => 'boolean',
            'phone' => 'string|max:255',
            'website' => 'string|max:255',
            'birthday' => 'date',
            'avatar' => 'image|max:2000',
            'cover' => 'image|max:2000',
            'address' => 'string|max:255',
            'city' => 'string|max:255',
            'state' => 'string|max:255',
            'country' => 'string|max:3',
            'zip' => 'string|max:255',
            'local' => 'string|max:3',
            'privacy' => 'in:public,private',
        ];
    }
}
