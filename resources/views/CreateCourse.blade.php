<!DOCTYPE html>
<html lang="en">
    <header>
        @include('navigation')
    </header>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Course</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/CreateCourse.css') }}">
    </head>
    <body>
        <div class="create-course-container">
            <h2 style="color: black;">Create Course</h2>
            <form action="{{ route('course.store') }}" method="POST">
                @csrf
                <input type="text" id="courseID" name="courseID" value="{{ $courseID }}" readonly class="readonly-input">
                <input type="text" id="courseName" name="courseName" placeholder="Course Name" required>
                <input type="number" id="capacityOffered" name="capacityOffered" placeholder="Maximum Student" min="1" max="9999" required>
                <textarea id="description" name="description" placeholder="Description"></textarea>
                <select id="category" name="category" required>
                    <option value="" disabled selected>Category</option>
                    <option value="science">Science</option>
                    <option value="math">Math</option>
                    <option value="history">History</option>
                    <option value="technology">Technology</option>
                    <option value="engineering">Engineering</option>
                    <option value="literature">Literature</option>
                    <option value="language">Language</option>
                    <option value="business">Business</option>
                    <option value="psychology">Psychology</option>
                    <option value="art">Art</option>
                    <option value="music">Music</option>
                    <option value="health">Health</option>
                    <option value="law">Law</option>
                    <option value="economics">Economics</option>
                </select>
                </select>
                <button type="submit" class="btn-create" style="background-color: #3b82f6; color: #fff; border: none; padding: 10px; width: 100%; font-size: 16px; border-radius: 8px; cursor: pointer; transition: background-color 0.3s ease; margin-top: 10px;" 
                        onmouseover="this.style.backgroundColor = '#2563eb'" 
                        onmouseout="this.style.backgroundColor = '#3b82f6'">
                    Create
                </button>
                <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
            </form>
            @error('studentCount')
            <div class="error-message">{{ $message }}</div>
            @enderror
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

        </div>
    </body>
</html>
