<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function notificationStatus() {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return jsonResponse(true, "Notification Status", ['status' => true]);
        }

        if ($profile->notification_status === null) {
            return jsonResponse(true, "Notification Status", ['status' => true]);
        }

        return jsonResponse(true, "Notification Status", ['status' => (boolean) $profile->notification_status]);
    }

    public function updateNotification(Request $request) {

        if (!$request->has('status') || !in_array($request->status, [0, 1])) {
            return jsonResponse(false, "Status Must Be 0 Or 1", null, 422);
        }

        $user = Auth::user();

        if (!$user->profile) {
            $user->profile()->create(
                [
                    'notification_status' => $request->status
                ]
            );
            return jsonResponse(true, "Notification Status Update", ['status' => (boolean)$request->status]);
        }

        $user->profile()->update(
            [
                'notification_status' => $request->status
            ]
        );

        return jsonResponse(true, "Notification Status Updated", ['status' => (boolean)$request->status]);
    }

    public function privacyStatus() {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return jsonResponse(true, "Privacy Status", ['status' => 'public']);
        }

        if ($profile->privacy === null) {
            return jsonResponse(true, "Privacy Status", ['status' => 'public']);
        }

        return jsonResponse(true, "Privacy Status", ['status' => $profile->privacy]);
    }

    public function updatePrivacy(Request $request) {

        if (!$request->has('status') || !in_array($request->status, ['private', 'public'])) {
            return jsonResponse(false, "Status Must Be public Or private", null, 422);
        }

        $user = Auth::user();

        if (!$user->profile) {
            $user->profile()->create(
                [
                    'privacy' => $request->status
                ]
            );
            return jsonResponse(true, "Privacy Status Update", ['status' => $request->status]);
        }

        $user->profile()->update(
            [
                'privacy' => $request->status
            ]
        );

        return jsonResponse(true, "Privacy Status Updated", ['status' => $request->status]);
    }

    public function deleteAccount() {
        $user = Auth::user();

        $user->delete();

        return jsonResponse(true, "Account Deleted", null);
    }
}
