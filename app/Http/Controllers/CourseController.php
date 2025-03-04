<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller {

    public function index() {
        return view('JoinCourse');
    }

    public function create() {
        // Retrieve the last courseID from the database
        $lastCourse = Course::orderBy('courseID', 'desc')->first();

        // Generate the new courseID
        if ($lastCourse) {
            $newCourseID = 'C' . str_pad((int) substr($lastCourse->courseID, 1) + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newCourseID = 'C00001'; // Default courseID if no courses exist
        }

        // Pass the dynamically generated courseID to the view
        return view('createCourse', ['courseID' => $newCourseID]);
    }

    // Search for a course
    public function search(Request $request) {
        $courseID = $request->input('courseID');

        // Fetch the course along with the teacher's information
        $course = Course::with('teacher')->where('courseID', $courseID)->first();

        return view('JoinCourse', compact('course'));
    }

    public function join($courseID) {
        $userID = auth()->id();

        // Find the student associated with the authenticated user
        $student = Student::where('user_id', $userID)->first();

        if (!$student) {
            return back()->withErrors('Student not found. Please ensure your account is properly set up.');
        }

        $studentID = $student->studentID;

        // Get the course details
        $course = Course::where('courseID', $courseID)->first();

        if (!$course) {
            return back()->withErrors('Course not found.');
        }

        // **Check if the course is full**
        $capacityOffered = $course->capacityOffered;
        $capacityOccupied = \DB::table('student_course')->where('courseID', $courseID)->count();
        $capacityAvailable = $capacityOffered - $capacityOccupied;

        if ($capacityAvailable <= 0) {
            return back()->withErrors('This course is full. You cannot join at this time.');
        }

        // Check if the student has already joined
        if (\DB::table('student_course')->where('studentID', $studentID)->where('courseID', $courseID)->exists()) {
            return back()->withErrors('You have already joined this course.');
        }

        // Attach the student to the course and update the capacity
        $student->courses()->attach($courseID, ['studentID' => $studentID]);

        // **Update the student count (capacity occupied)**
        $course->studentCount = $capacityOccupied + 1;
        $course->save();

        return redirect()->route('course.search')->with('success', 'You have successfully joined the course.');
    }

    public function store(Request $request) {
        // Retrieve the authenticated user's teacher record
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->back()->withErrors('Only teachers can create courses.');
        }

        // Extract the custom teacherID (e.g., T00001, T00002)
        $teacherID = $teacher->teacherID;

        // Validate the course input
        $validated = $request->validate([
            'courseID' => 'required|string|max:255',
            'courseName' => 'required|string|max:255',
            'capacityOffered' => 'nullable|integer|min:1|max:9999',
            'description' => 'nullable|string|max:255',
            'category' => 'required|string',
        ]);

        // Get a random image from 'storage/app/public/courses/sample'
        $imageDirectory = storage_path('app/public/courses/sample');
        $defaultImage = null;

        if (is_dir($imageDirectory)) {
            $imageFiles = glob($imageDirectory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

            if (!empty($imageFiles)) {
                $randomImage = basename($imageFiles[array_rand($imageFiles)]); // Get filename only
                $defaultImage = 'courses/sample/' . $randomImage; // Correct relative path
            }
        }

        // Create the course with the correct teacherID and default image
        Course::create([
            'courseID' => $validated['courseID'],
            'courseName' => $validated['courseName'],
            'studentCount' => 0, // Default student count
            'capacityOffered' => $validated['capacityOffered'] ?? 0,
            'description' => $validated['description'],
            'category' => $validated['category'],
            'teacherID' => $teacherID, // Store custom teacherID
            'image' => $defaultImage, // Store correct image path
        ]);

        return redirect()->route('course.create')->with('success', 'Course created successfully!');
    }

    public function myCourses() {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $studentID = $student->studentID;

        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found.');
        }

        // Paginated courses for the main list, sorted by the 'order' column
        $enrolledCourses = DB::table('student_course')
                ->join('courses', 'student_course.courseID', '=', 'courses.courseID')
                ->join('teachers', 'courses.teacherID', '=', 'teachers.teacherID')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->where('student_course.studentID', $studentID)
                ->select('courses.*', 'student_course.progress', 'student_course.is_completed', 'users.first_name', 'users.last_name', 'student_course.order')
                ->orderBy('student_course.order', 'asc') // Sort by the 'order' column
                ->paginate(4);

        // All enrolled courses for the arrange modal, sorted by the 'order' column
        $allEnrolledCourses = DB::table('student_course')
                ->join('courses', 'student_course.courseID', '=', 'courses.courseID')
                ->join('teachers', 'courses.teacherID', '=', 'teachers.teacherID')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->where('student_course.studentID', $studentID)
                ->select('courses.*', 'student_course.progress', 'student_course.is_completed', 'users.first_name', 'users.last_name', 'student_course.order')
                ->orderBy('student_course.order', 'asc') // Sort by the 'order' column
                ->get();

        $imageDirectory = public_path('photo/enrolledCourse');
        $imageFiles = is_dir($imageDirectory) ? glob($imageDirectory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE) : [];

        return view('StudentEnrolledCourse', compact('enrolledCourses', 'allEnrolledCourses', 'imageFiles'));
    }

    public function myCreatedCourses() {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user is a teacher and retrieve their teacherID
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'Only teachers can view created courses.');
        }

        // Retrieve the custom teacherID (e.g., T00001)
        $teacherID = $teacher->teacherID;

        $courses = Course::where('teacherID', $teacherID)->orderBy('display_order', 'asc')->paginate(4);

        $createdCourses = Course::where('teacherID', auth()->user()->teacher->teacherID)
                ->orderBy('display_order')
                ->get();

        // Retrieve all courses for the "Arrange Courses" modal (no pagination)
        $allCourses = Course::where('teacherID', $teacherID)
                ->orderBy('order', 'asc')
                ->get();

        // Fetch course images if they exist
        $imageDirectory = public_path('photo/enrolledCourse');
        $imageFiles = is_dir($imageDirectory) ? glob($imageDirectory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE) : [];

        return view('TeacherCreatedCourse', compact('createdCourses', 'allCourses', 'imageFiles', 'courses'));
    }

    public function show(string $id) {
        // Show course details (if needed)
    }

    public function edit(string $id) {
        // Edit course details (if needed)
    }

    public function update(Request $request, $id) {
        $request->validate([
            'courseName' => 'required|string|max:255',
            'courseOverview' => 'nullable|string|max:255',
            'capacityOffered' => 'required|integer|min:1',
            'courseImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Restrict to JPG, JPEG, PNG
        ]);

        $course = Course::findOrFail($id);

        // Check if the new capacity is less than the current number of enrolled students
        if ($request->input('capacityOffered') < $course->studentCount) {
            return redirect()->back()->withErrors(['capacityOffered' => 'Capacity cannot be less than the current number of enrolled students.']);
        }

        $course->courseName = $request->input('courseName');
        $course->description = $request->input('courseOverview');
        $course->capacityOffered = $request->input('capacityOffered');

        // Handle Image Upload
        if ($request->hasFile('courseImage')) {
            $imagePath = $request->file('courseImage')->store('courses', 'public');
            $course->image = $imagePath;
        }

        $course->save();

        return redirect()->back()->with('success', 'Course updated successfully.');
    }

    public function reorder(Request $request) {
        $order = $request->input('order');

        foreach ($order as $item) {
            DB::table('courses')
                    ->where('courseID', $item['courseID'])
                    ->update(['display_order' => $item['newPosition']]);
        }

        return response()->json(['success' => true]);
    }

    public function paginatedCourses() {
        $courses = Course::orderBy('display_order', 'asc')->paginate(4);
        return view('PaginatedCourses', compact('courses'));
    }

    public function getEnrolledCourses() {
        $userID = Auth::id();
        $student = Student::where('user_id', $userID)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $studentID = $student->studentID;

        // Retrieve courses with order
        $enrolledCourses = Course::join('student_course', 'courses.courseID', '=', 'student_course.courseID')
                ->join('teachers', 'courses.teacherID', '=', 'teachers.teacherID')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->where('student_course.studentID', $studentID)
                ->select('courses.courseID', 'courses.courseName', 'users.first_name', 'users.last_name', 'courses.category', 'courses.description', 'student_course.order')
                ->orderBy('student_course.order', 'asc') // Ensure sorting by saved order
                ->get();

        return response()->json($enrolledCourses);
    }

    public function updateCourseOrder(Request $request) {
        // Validate the incoming request
        $request->validate([
            'order' => 'required|array',
            'order.*.courseID' => 'required|string|exists:courses,courseID',
            'order.*.order' => 'required|integer|min:1',
        ]);

        try {
            // Loop through and update the order
            foreach ($request->order as $course) {
                DB::table('student_course')
                        ->where('courseID', $course['courseID'])
                        ->where('studentID', auth()->user()->student->studentID)
                        ->update(['display_order' => $course['order']]);
            }

            return response()->json(['message' => 'Course order updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update course order.'], 500);
        }
    }

    public function saveCourseOrder(Request $request) {
        $userID = Auth::id();
        $student = Student::where('user_id', $userID)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $studentID = $student->studentID;
        $orderedCourses = $request->input('orderedCourses');

        foreach ($orderedCourses as $index => $courseID) {
            DB::table('student_course')
                    ->where('studentID', $studentID)
                    ->where('courseID', $courseID)
                    ->update(['order' => $index + 1]); // Save order starting from 1
        }

        return response()->json(['success' => 'Course order updated successfully']);
    }

    public function studentReorder(Request $request) {
        $order = $request->input('order');
        $studentID = auth()->user()->student->studentID;

        foreach ($order as $item) {
            DB::table('student_course')
                    ->where('courseID', $item['courseID'])
                    ->where('studentID', $studentID)
                    ->update(['order' => $item['newPosition']]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id) {
        $course = Course::where('courseID', $id)->firstOrFail();

        // Ensure the authenticated user is the teacher who created the course
        if ($course->teacherID !== auth()->user()->teacher->teacherID) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to delete this course.'], 403);
        }

        $course->delete();
        return response()->json(['success' => true, 'message' => 'Course deleted successfully.']);
    }
}
