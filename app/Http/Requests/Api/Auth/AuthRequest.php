<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Requests\BaseFormRequest;

class AuthRequest extends BaseFormRequest
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
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'username' => 'required|string|alpha_dash|max:255|unique:users,username|alpha_dash',
                'password' => 'required|string|max:255',
                'device_name' => 'string|max:255',
            ];

        }

        if ($methodName == 'login') {

            return [
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'device_name' => 'string|max:255',
            ];
        }
    }
}
