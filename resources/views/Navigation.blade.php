<link rel="stylesheet" type="text/css" href="{{ asset('css/navigation.css') }}">
<script src="{{ asset('js/userNavigationBar.js') }}"></script>

<nav class="nav-css">
    <ul>
        <!-- Logo -->
        <li><img src="{{ asset('icon/logo.png') }}" alt="Logo" class="logo-css" /></li>

        <!-- Navigation Links -->
        @auth
        @if(Auth::user()->type === 'teacher')
        <li><a href="{{ route('teacher.home') }}">Home</a></li>
        <li><a href="{{ route('teacher.courses') }}">My Created Courses</a></li>
        <li><a href="{{ route('course.create') }}">Create Course</a></li>
        @elseif(Auth::user()->type === 'student')
        <li><a href="{{ route('student.home') }}">Home</a></li>
        <li><a href="{{ route('courses.my-courses') }}">My Enrolled Courses</a></li>
        <li><a href="{{ route('course.search') }}">Join Course</a></li>

        @endif
        @else
        <li><a href="{{ route('courses.my-courses') }}">Study</a></li>
        @endauth

        <li><a href="#">FunctionA</a></li>
        <li><a href="#">FunctionB</a></li>
        <li><a href="#">About Us</a></li>

        <!-- User Icon and Dropdown -->
        <li class="user-icon">
            <img src="{{ asset('icon/userIcon.png') }}" alt="User Icon" class="userIcon-img" />
            <ul class="dropdown">
                @auth
                <li>
                    <span>Hi, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                </li>
                <li><a href="{{ route('personalInfo') }}">Manage Account</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-button">Sign Out</button>
                    </form>
                </li>
                @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </li>
    </ul>
</nav>
