<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\BinaryOp\Equal;
use App\Http\Resources\Api\UserResource;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use App\Http\Requests\Api\Auth\AuthRequest;

class AuthController extends Controller
{
    public function getAuthUSer(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        return jsonResponse(true, "User data", $user);
    }

    public function register(AuthRequest $request)
    {
        $user = User::create($request->validated());

        $user->profile()->create($request->only('first_name', 'last_name'));

        $userResource = new UserResource($user);

        $token = $user->createToken($request->post('device_name', $request->userAgent()))->plainTextToken;

        $message = 'User Created Successfully';

        $data = [
            'user' => $userResource,
            'token' => $token
        ];

        return jsonResponse(true, $message, $data);
    }

    public function login(AuthRequest $request)
    {
        $user = User::where('email', $request->post('username'))
                    ->orWhere('username', $request->post('username'))
                    ->first();

        if (!$user || !Hash::check($request->post('password'), $user->password)) {
            $message = 'The provided credentials are incorrect.';

            return jsonResponse(false, $message);
        }

        $userResource = new UserResource($user);

        $token = $user->createToken($request->post('device_name', $request->userAgent()))->plainTextToken;

        $message = 'User Login Successfully';

        $data = [
            'user' => $userResource,
            'token' => $token
        ];

        return jsonResponse(true, $message, $data);
    }

    public function logout()
    {
        $user = Auth::guard('sanctum')->user();

        $user->currentAccessToken()->delete(); // delete current token

        $message = 'User Logout Successfully';

        return jsonResponse(true, $message);
    }

    public function forgetPassword(Request $request)
    {
        $user = User::where('email', $request->post('email'))->first();

        if (!$user) {
            $message = 'The provided credentials are incorrect.';

            return jsonResponse(false, $message);
        }

        $user->tokens()->where('name', 'password-reset')->delete();

        $user->sendPasswordResetNotification($user->createToken('password-reset')->plainTextToken);

        $message = 'Reset Password Link Sent Successfully';

        return jsonResponse(true, $message);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('email', $request->post('email'))->first();

        if (!$user) {
            $message = 'The provided credentials are incorrect.';

            return jsonResponse(false, $message);
        }

        if (!$request->post('token') ===
            $user->tokens()->where('name', 'password-reset')->latest()->first()->token) {

            $message = 'The provided credentials are incorrect.';

            return jsonResponse(false, $message);
        }

        $user->update([
            'password' => $request->post('password')
        ]);

        // $user->tokens()->where('name', 'password-reset')->delete();

        $message = 'Password Reset Successfully';

        return jsonResponse(true, $message);
    }
}
