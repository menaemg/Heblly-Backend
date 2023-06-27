<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\ResetCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Api\UserResource;
use App\Notifications\ResetPasswordCode;
use App\Http\Requests\Api\Auth\AuthRequest;
use App\Notifications\SendCodeResetPassword;

class AuthController extends Controller
{
    public function getAuthUSer(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        return jsonResponse(true, "User data", $user);
    }

    public function register(AuthRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::create($request->validated());

        if ($request->has('first_name', 'last_name')) {
            $user->profile()->create($request->only('first_name', 'last_name'));
        }

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
        $user = User::where('email', $request->post('email'))
                    ->where('status', 'active')
                    ->orWhere('username', $request->post('email'))
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

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $token = $request->bearerToken();

        $user->currentAccessToken()->delete(); // delete current token

        $message = 'User Logout Successfully';

        return jsonResponse(true, $message, [
            'token' => $token
        ]);
    }

    public function forgetPassword(Request $request)
    {
        $user = User::where('email', $request->post('email'))->first();

        if (!$user) {
            $message = 'The provided credentials are incorrect.';

            return jsonResponse(false, $message);
        }

        // Delete all old code that user send before.
        $user->reset_code()->delete();

        // Generate random code
        $code = mt_rand(100000, 999999);

        // Create a new code
        $user->reset_code()->create([
            'code' => $code,
        ]);

        // Send email to user
        // $user->sendPasswordResetNotification($code);

        Mail::to($user->email)->send(new ResetPasswordCode($code));


        $message = 'Reset Password Link Sent Successfully';
        // Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return jsonResponse(true, $message);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        // find user's email
        $user = User::where('email', $request->post('email'))->first();

        if (!$user) {
            $message = 'The provided credentials are incorrect.';

            return jsonResponse(false, $message);
        }

        // find the code
        $passwordReset = $user->reset_code()->first();

        if (!$passwordReset || !Hash::check($request->post('code'), $passwordReset->code)) {
            $message = 'The provided credentials are incorrect.';

            return jsonResponse(false, $message);
        }

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return jsonResponse(false,'passwords code is expire', null ,422);
        }

        // update user password
        $user->update($request->only('password'));

        // delete current code
        $passwordReset->delete();

        return response(['message' =>'password has been successfully reset'], 200);
    }
}
