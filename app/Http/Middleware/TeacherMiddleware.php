<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherMiddleware {
public function handle(Request $request, Closure $next) {
// Check if the authenticated user is a teacher
if (Auth::check() && Auth::user()->type === 'teacher') {
return $next($request);
}

// Redirect students to the student home page
return redirect()->route('student.home')->with('error', 'You are not authorized to access this page.');
}
}

