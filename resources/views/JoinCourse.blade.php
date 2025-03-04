<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Join Course</title>
        <link rel="stylesheet" href="{{ asset('css/JoinCourse.css') }}">
    </head>
    <header>
        @include('navigation')
    </header>
    <body>
        <div class="join-course-container">
            <h1 class="title">Join Course</h1>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('course.search') }}" class="search-bar">
                <input type="text" name="courseID" class="search-input" placeholder="Enter Course Code" value="{{ request('courseID') }}">
                <button type="submit" class="search-button">Search</button>
            </form>
            @if(isset($course))
            <div class="course-card">
                <h2 class="course-title">{{ $course->courseID }} {{ $course->courseName }}</h2>
                <div class="course-details">
                    <div class="teacher-info">
                        <p><strong>Instructor: {{ $course->teacher->user->first_name }} {{ $course->teacher->user->last_name }}</strong></p>
                        @if(!empty($course->teacher->experienced_years))
                        <p>Experienced Years: {{ $course->teacher->experienced_years }}</p>
                        @endif
                        @if(!empty($course->teacher->bio))
                        <p>Biography: {{ $course->teacher->bio }}</p>
                        @endif
                    </div>
                    <div class="course-description">
                        <p><strong>Course Description:</strong></p>
                        <p>{{ $course->description }}</p>
                    </div>
                    <div class="student-count">
                        <p><span class="label">Capacity Offered:</span> <span class="value">{{ $course->capacityOffered }}</span></p>
                        <p><span class="label">Capacity Occupied:</span> <span class="value">{{ $course->studentCount }}</span></p>
                        <p><span class="label">Capacity Available:</span> <span class="value">{{ $course->capacityOffered - $course->studentCount }}</span></p>
                    </div>
                </div>
                <div class="course-footer">
                    <form method="POST" action="{{ route('course.join', $course->courseID) }}">
                        @csrf
                        <button type="submit" class="join-button">Join</button>
                    </form>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                </ul>
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @elseif(request('courseID'))
            <p class="no-course">No course found with the provided code.</p>
            @endif
        </div>
    </body>
</html>
