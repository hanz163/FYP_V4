<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Account</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Poppins', sans-serif;
            }

            body {
                background-color: #f3f4f6;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }

            .container {
                max-width: 500px; /* Match first code */
                width: 100%;
                background-color: #ffffff;
                border-radius: 16px;
                padding: 2rem;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                margin-bottom: 20px; /* Adjust the value as needed */
            }

            .card {
                background-color: #ffffff;
                padding: 2rem; /* Match first code */
                border-radius: 16px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            }

            h4 {
                font-size: 22px; /* Match first code */
                font-weight: bold;
                color: #0097a7; /* Deep cyan */
                text-align: center;
                margin-bottom: 15px;
            }

            label {
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
                color: #007c91; /* Slightly darker cyan */
                font-size: 14px;

            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            select {
                width: 100%; /* Match first code */
                max-width: 600px;
                padding: 10px 12px;
                border: 1px solid #80deea; /* Lighter cyan */
                border-radius: 8px;
                font-size: 13px; /* Match first code */
                color: #333;
                transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
                margin-bottom: 15px;
            }

            input:focus,
            select:focus {
                border-color: #0097a7;
                box-shadow: 0 0 5px rgba(0, 151, 167, 0.3);
                outline: none;
            }

            button {
                width: 100%;
                padding: 12px;
                background-color: #0097a7;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px; /* Match first code */
                font-weight: 500;
                cursor: pointer;
                margin-top: 15px;
                transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
            }

            button:hover {
                background-color: #007c91;
                transform: translateY(-2px);
            }

            .btn-danger {
                background-color: #ff5252;
            }

            .btn-danger:hover {
                background-color: #d32f2f;
            }

            .alert {
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 5px;
            }

            .alert-success {
                background-color: #d4edda;
                color: #155724;
            }

            .alert-danger {
                background-color: #f8d7da;
                color: #721c24;
            }

            h4.manage-account-title {
                font-size: 30px; /* Match first code */
            }

            .manage-account-form {
                padding: 2.5rem; /* Match first code */
            }

            p{
                font-size: 14px;
            }

            .notes {
                font-size: 14px;
                color: #007c91; /* Slightly darker cyan */
                background-color: #e0f7fa; /* Light cyan */
                padding: 1.25rem;
                border-radius: 8px;
                margin-top: 1.5rem;
                margin-bottom: 1.5rem;
                border-left: 4px solid #0097a7; /* Deep cyan */
                font-family: 'Poppins', sans-serif;
            }

            .notes p {
                font-weight: bold;
                margin-bottom: 0.5rem;
                text-align: center;
            }

            .notes ul {
                margin-left: 1.5rem;
            }

            .notes li {
                font-size: 13px;
                line-height: 1.5;
            }

            /* Make alert messages smaller */
            .alert {
                padding: 8px; /* Reduce padding */
                margin-bottom: 10px; /* Reduce margin */
                border-radius: 5px;
                font-size: 14px; /* Smaller font size */
            }

            /* Grey background for disabled email field */
            input[type="email"]:disabled {
                background-color: #f0f0f0; /* Light grey background */
                color: #666; /* Darker grey text for better contrast */
                cursor: not-allowed; /* Show not-allowed cursor */
            }
        </style>
    </head>
    <body>

        @include('navigation')

        <div class="container">
            <!-- Status Messages -->

            <!-- Personal Information -->
            <h4 class="manage-account-title">Manage Account</h4>
            <form action="{{ route('personalInfo.update') }}" method="POST">
                @csrf
                @method('PUT')
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ $user->first_name }}" required>

                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ $user->last_name }}" required>
                @if(session()->has('personalInfoStatus'))
                <div class="alert alert-success">{{ session('personalInfoStatus') }}</div>
                @endif
                <button type="submit">Save</button>
            </form>

            <hr>

            <!-- Email -->
            <h4>Email</h4>
            <input type="email" value="{{ $user->email }}" disabled>

            <hr>

            <!-- Password Update -->
            <h4>Change Password</h4>
            <form action="{{ route('personalInfo.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" name="currentPassword" required>
                @error('currentPassword')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <!-- New Password -->
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="newPassword" required>
                @error('newPassword')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <!-- Confirm New Password -->
                <label for="newPassword_confirmation">Confirm New Password</label>
                <input type="password" id="newPassword_confirmation" name="newPassword_confirmation" required>
                @error('newPassword_confirmation')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @if(session()->has('passwordStatus'))
                <div class="alert alert-success">{{ session('passwordStatus') }}</div>
                @endif
                @if(session()->has('passwordError'))
                <div class="alert alert-danger">{{ session('passwordError') }}</div>
                @endif
                <button type="submit">Save</button>
            </form>

            <!-- Password Requirements Notes -->
            <div class="notes">
                <p><strong>IMPORTANT NOTES</strong></p>
                <p>The password must fulfill the following conditions:</p>
                <ul>
                    <li>At least 8 characters</li>
                    <li>At least one uppercase letter (A-Z)</li>
                    <li>At least one lowercase letter (a-z)</li>
                    <li>At least one numeric digit (0-9)</li>
                    <li>At least one special character (e.g., @#$%^&*)</li>
                    <li>Previous passwords cannot be reused.</li>
                </ul>
            </div>
            <hr>

            <!-- Account Deletion -->
            <h4 style="font-size: 20px;">Delete Account</h4>
            <p>Delete your account and all personal data associated with it.</p>
            <form action="{{ route('personalInfo.deleteAccount') }}" method="POST" onsubmit="return confirm('Are you sure? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete Account</button>
                @if(session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
            </form>
        </div>
    </body>
</html>
