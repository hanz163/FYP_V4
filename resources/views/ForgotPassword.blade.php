<!DOCTYPE html>
<html lang="en">
    <header>
        @include('navigation')
    </header>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/ForgotPassword.css') }}">
    </head>
    <body>
        <div class="container">
            <div class="form-card">
                <h2>Forgot Password</h2>
                <p>Please enter your email address to receive password reset instructions with the reset link.</p>
                <p></p>
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                <form method="POST" action="{{ route('forgot.password.store') }}">
                    @csrf
                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn">Send Reset Link</button>
                    </div>
                </form>
                <p>Remembered your password? <a href="{{ route('login') }}">Sign In</a></p>
            </div>
        </div>
    </body>
</html>
