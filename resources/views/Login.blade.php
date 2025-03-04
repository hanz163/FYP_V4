<!DOCTYPE html>
<html lang="en">
    <header>
        @include('navigation')
    </header>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign In</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/Login.css') }}">
    </head>
    <body>
        <div class="container">
            <div class="form-card">
                <h2>Sign In</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn">Login</button>
                    </div>
                </form>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('status'))
                <div class="alert {{ session('status_class', 'alert-danger') }}">
                    {{ session('status') }}
                </div>
                @endif

                <p>Don't have an account? <a href="{{ route('register') }}">Register Here</a></p>
                <p><a href="{{ route('forgotPassword') }}">Forgot your password?</a></p>
            </div>
        </div>
    </body>
</html>
