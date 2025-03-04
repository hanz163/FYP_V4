<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/Register.css') }}">
        <script src="{{ asset('js/Register.js') }}"></script>
    </head>
    <body>
        <!-- Navigation Bar -->
        <header>
            @include('navigation')
        </header>

        <!-- Registration Form -->
        <div class="container">
            <div class="form-card">
                <h2>Registration</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
                    </div>
                    <div class="input-group">
                        <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
                    </div>
                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                    <div class="input-group">
                        <select id="type" name="type" required>
                            <option value="" disabled selected>Role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
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
                    <button type="submit" class="btn">Register</button>
                </form>
                <p></p>
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('status'))
                <div class="alert alert-danger">
                    {{ session('status') }}
                </div>
                @endif
                <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
            </div>
        </div>
    </body>
</html>
