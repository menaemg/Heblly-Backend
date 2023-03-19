<?php
namespace App\Http\Requests\Dashboard\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'username' => 'string|alpha_dash|max:255|unique:users,username,' . $this->user->username . ',username',
            'email' => 'string|email|max:255|unique:users,email,' . $this->user->email . ',email',
            'name' => 'string|max:255',
            'type' => 'in:user,admin',
            'bio' => 'string|max:255',
            'gender' => 'boolean',
            'password' => 'nullable|min:6|confirmed',
            'phone' => 'string|max:255',
            'website' => 'string|max:255',
            'birthday' => 'date',
            'avatar_file' => 'nullable|image|max:2000',
            'cover_file' => 'nullable|image|max:2000',
            'address' => 'string|max:255',
            'city' => 'string|max:255',
            'state' => 'string|max:255',
            'country' => 'string|max:30',
            'zip' => 'string|max:255',
            'local' => 'string|max:3',
            'privacy' => 'in:public,private',
        ];
    }
}
