<?php
namespace App\Http\Requests\Dashboard\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            'username' => 'required|string|alpha_dash|max:255|unique:users,username,',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'name' => 'string|max:255',
            'type' => 'in:user,admin',
            'bio' => 'nullable|string|max:255',
            'gender' => 'nullable|boolean',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'avatar_file' => 'nullable|image|max:2000',
            'cover_file' => 'nullable|image|max:2000',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:30',
            'zip' => 'nullable|string|max:255',
            'local' => 'nullable|string|max:3',
            'privacy' => 'nullable|in:public,private',
        ];
    }
}
