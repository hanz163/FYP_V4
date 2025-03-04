<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\PasswordResetMail;
use App\Models\User;

class UserController extends Controller {

    public function register(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8', // At least 8 characters
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[0-9]/', // At least one numeric digit
                'regex:/[@$!%*?&]/', // At least one special character
            ],
            'type' => 'required|in:student,teacher,admin',
                ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one numeric digit, and one special character.',
        ]);

        // Create a new user
        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->type = $request->input('type');
        $user->save();

        if ($user->type === 'student') {
            // Generate student ID (S00001, S00002, ...)
            $lastStudent = \App\Models\Student::orderBy('studentID', 'desc')->first();
            $newStudentID = 'S' . str_pad((int) substr($lastStudent->studentID ?? 'S00000', 1) + 1, 5, '0', STR_PAD_LEFT);

            // Create a new student record
            $student = new \App\Models\Student();
            $student->studentID = $newStudentID;
            $student->user_id = $user->id;
            $student->progress_percentage = 0;
            $student->achievement = '';
            $student->save();
        } elseif ($user->type === 'teacher') {
            // Generate teacher ID (T00001, T00002, ...)
            $lastTeacher = \App\Models\Teacher::orderBy('teacherID', 'desc')->first();
            $newTeacherID = 'T' . str_pad((int) substr($lastTeacher->teacherID ?? 'T00000', 1) + 1, 5, '0', STR_PAD_LEFT);

            // Create a new teacher record
            $teacher = new \App\Models\Teacher();
            $teacher->teacherID = $newTeacherID;  // Assign generated teacher ID
            $teacher->user_id = $user->id;
            $teacher->created_course = '';  // Set initial course info
            $teacher->experienced_years = 0;  // Set initial experience years
            $teacher->bio = '';  // Set initial bio
            $teacher->save();
        }

        session()->flash('success', 'Registration completed successfully!');
        return redirect()->route('login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect based on user type
            if ($user->type === 'admin') {
                return redirect()->route('admin.home');
            } elseif ($user->type === 'student') {
                return redirect()->route('student.home');
            } elseif ($user->type === 'teacher') {
                return redirect()->route('teacher.home');
            } else {
                return redirect('/index');
            }
        }

        return redirect()->back()
                        ->with('status', 'Invalid email or password, please try again.')
                        ->with('status_class', 'alert-danger');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out.')->with('status_class', 'alert-success');
    }

    public function show() {
        return view('forgotPassword');
    }

    // Web method for handling forgot password (returns views and redirects)
    public function store(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);

        // Set token expiry time (30 minutes)
        $expiry = now()->addMinutes(30)->toDateTimeString();

        DB::table('users')
                ->where('email', $email)
                ->update([
                    'reset_token_hash' => $token_hash,
                    'reset_token_expires_at' => $expiry,
        ]);

        // Send password reset email
        $this->sendPasswordResetEmail($email, $token);

        return redirect()->back()->with('status', 'Password reset link has been sent to your email.');
    }

    // Web method for showing the reset password form
    public function showResetForm(Request $request) {
        $token = $request->query('token');
        return view('resetPassword', ['token' => $token]);
    }

    // Web method for resetting the password (returns views and redirects)
    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $token = $request->input('token');
        $token_hash = hash("sha256", $token);

        $user = DB::table('users')
                ->where('reset_token_hash', $token_hash)
                ->where('reset_token_expires_at', '>', now())
                ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['token' => 'Invalid or expired token'])
                            ->with('status_class', 'alert-danger');
        }

        $password_hash = Hash::make($request->input('password'));

        DB::table('users')
                ->where('id', $user->id)  // Changed from userID to id
                ->update([
                    'password' => $password_hash,
                    'reset_token_hash' => null,
                    'reset_token_expires_at' => null,
        ]);

        return redirect()->route('login')
                        ->with('status', 'Password updated successfully.')->with('status_class', 'alert-success');
    }

    // API-specific method for forgot password (returns JSON)
    public function apiStore(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);

        // Set token expiry time (30 minutes)
        $expiry = now()->addMinutes(30)->toDateTimeString();

        DB::table('users')
                ->where('email', $email)
                ->update([
                    'reset_token_hash' => $token_hash,
                    'reset_token_expires_at' => $expiry,
        ]);

        // Send password reset email
        $this->sendPasswordResetEmail($email, $token);

        return response()->json(['message' => 'Password reset link has been sent to your email.']);
    }

    // API-specific method for resetting password (returns JSON)
    public function apiResetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $token = $request->input('token');
        $token_hash = hash("sha256", $token);

        $user = DB::table('users')
                ->where('reset_token_hash', $token_hash)
                ->where('reset_token_expires_at', '>', now())
                ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid or expired token'], 400);
        }

        $password_hash = Hash::make($request->input('password'));

        DB::table('users')
                ->where('id', $user->id)  // Changed from userID to id
                ->update([
                    'password' => $password_hash,
                    'reset_token_hash' => null,
                    'reset_token_expires_at' => null,
        ]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    // Reused method for sending email
    protected function sendPasswordResetEmail($email, $token) {
        $resetLink = url("/reset-password?token=$token");
        Mail::to($email)->send(new PasswordResetMail($resetLink));
    }
}
