<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        $methodName = Route::current()->getActionMethod();

        if ($methodName == 'register') {

            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'username' => 'required|string|max:255|unique:users,username|alpha_dash',
                'password' => 'required|string|max:255',
                'device_name' => 'string|max:255',
            ];

        }

        if ($methodName == 'login') {

            return [
                'username' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'device_name' => 'string|max:255',
            ];
        }
    }
}
