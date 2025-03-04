<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/ResetPassword.css') }}">
    </head>
    <body>
        <!-- Navigation Bar -->
        <header>
            @include('navigation')
        </header>

        <!-- Reset Password Form -->
        <div class="container">
            <div class="form-card">
                <h2>Reset Password</h2>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="New Password" required>
                    </div>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                    <div class="notes">
                        <p><strong>IMPORTANT NOTES</strong></p>
                        <p>The password must fulfill the condition of:</p>
                        <ul>
                            <li>At least 8 characters</li>
                            <li>At least one uppercase letter (A-Z)</li>
                            <li>At least one lowercase letter (a-z)</li>
                            <li>At least one numeric digit (0-9)</li>
                            <li>At least one special character (e.g., @#$%^&*)</li>
                        </ul>
                    </div>
                    <button type="submit" class="btn">Reset Password</button>
                </form>
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
            </div>
        </div>
    </body>
</html>
