<?php

/* Author: Ooi Wei Han */

namespace App\Http\Controllers;

use App\Models\PasswordHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PersonalInfoController extends Controller {

    public function showPersonalInfo() {
        $userID = Auth::id();

        if (!$userID) {
            return redirect()->route('login')->with('error', 'You must be logged in to view this page.');
        }

        $userDetails = DB::table('users')
                ->where('id', $userID)
                ->first(['first_name', 'last_name', 'email']);

        return view('personalInfo', ['user' => $userDetails]);
    }

    public function updatePersonalInfo(Request $request) {
        $userID = Auth::id();

        if (!$userID) {
            return redirect()->route('login')->with('error', 'You must be logged in to update your information.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        DB::table('users')
                ->where('id', $userID)
                ->update([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
        ]);

        return redirect()->route('personalInfo')->with('personalInfoStatus', 'Personal information updated successfully.');
    }

    public function updatePassword(Request $request) {
        // Validate the request
        $validator = Validator::make($request->all(), [
                    'currentPassword' => 'required|string',
                    'newPassword' => [
                        'required',
                        'string',
                        'confirmed',
                        'min:8',
                        'regex:/[A-Z]/', // At least one uppercase letter
                        'regex:/[a-z]/', // At least one lowercase letter
                        'regex:/[0-9]/', // At least one numeric digit
                        'regex:/[@$!%*?&]/', // At least one special character
                    ],
                        ], [
                    'newPassword.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one numeric digit, and one special character.',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return redirect()->route('personalInfo')
                            ->withErrors($validator)
                            ->withInput();
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()->route('personalInfo')
                            ->with('passwordError', 'Current password is incorrect.');
        }

        // Check if the new password is the same as the current password
        if (Hash::check($request->newPassword, $user->password)) {
            return redirect()->route('personalInfo')
                            ->with('passwordError', 'New password cannot be the same as the current password.');
        }

        // Check if the new password has been used before
        $previousPasswords = PasswordHistory::where('user_id', $user->id)
                ->pluck('password')
                ->toArray();

        foreach ($previousPasswords as $password) {
            if (Hash::check($request->newPassword, $password)) {
                return redirect()->route('personalInfo')
                                ->with('passwordError', 'You cannot use a previous password.');
            }
        }

        // Update the password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        // Save the new password to the password history
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        return redirect()->route('personalInfo')
                        ->with('passwordStatus', 'Password updated successfully.');
    }

    public function deleteAccount(Request $request) {
        $userID = Auth::id();

        if (!$userID) {
            return redirect()->route('login')->with('error', 'You must be logged in to delete your account.');
        }

        DB::table('users')->where('id', $userID)->delete();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Your account has been deleted.');
    }
}
