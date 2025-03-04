<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Part;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\File;
use App\Models\LectureNote;
use App\Models\LectureVideo;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller {

    public function index($courseID) {
        $user = auth()->user();

        // Ensure only teachers can access
        if ($user->type !== 'teacher') {
            return redirect()->route('student.home')->with('error', 'You are not authorized to access this page.');
        }

        // Check if the teacher owns the course
        $course = Course::where('courseID', $courseID)->where('teacherID', $user->teacher->teacherID)->first();

        if (!$course) {
            return redirect()->route('teacher.home')->with('error', 'You are not authorized to manage this course.');
        }

        $chapters = Chapter::where('courseID', $courseID)->get();
        return view('ChapterTeacher', compact('course', 'chapters'));
    }

    public function store(Request $request, $courseID) {
        $user = auth()->user();

        // Ensure the teacher owns the course
        $course = Course::where('courseID', $courseID)->where('teacherID', $user->teacher->teacherID)->first();

        if (!$course) {
            return redirect()->route('teacher.home')->with('error', 'You are not authorized to manage this course.');
        }

        // Validate the input
        $request->validate([
            'chapterName' => 'required|string|max:30',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
        ]);

        // Handle image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chapter_images', 'public'); // Store the image in 'public/chapter_images'
        }

        // Create the chapter
        Chapter::create([
            'chapterName' => $request->chapterName,
            'description' => $request->description,
            'courseID' => $courseID,
            'image' => $imagePath, // Save the image path in the database
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id) {
        $user = auth()->user();

        // Fetch the chapter and its course
        $chapter = Chapter::where('chapterID', $id)->first();

        if (!$chapter) {
            return redirect()->back()->with('error', 'Chapter not found.');
        }

        // Ensure the teacher owns the course
        $course = Course::where('courseID', $chapter->courseID)
                ->where('teacherID', $user->teacher->teacherID)
                ->first();

        if (!$course) {
            return redirect()->route('teacher.home')->with('error', 'You are not authorized to manage this course.');
        }

        // Delete the image file if exists
        if ($chapter->image && Storage::exists('public/' . $chapter->image)) {
            Storage::delete('public/' . $chapter->image);
        }

        $chapter->delete();

        return redirect()->back()->with('success', 'Chapter deleted successfully.');
    }

    public function studentChapters($courseID) {
        $user = auth()->user();

        $isEnrolled = \DB::table('student_course')
                ->where('studentID', $user->student->studentID)
                ->where('courseID', $courseID)
                ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.home')->with('error', 'You are not enrolled in this course.');
        }

        // Fetch course and chapters
        $course = Course::findOrFail($courseID);
        $chapters = Chapter::where('courseID', $courseID)->orderBy('position')->get();

        return view('ChapterStudent', compact('course', 'chapters'));
    }

    public function reorder(Request $request) {
        foreach ($request->order as $item) {
            Chapter::where('chapterID', $item['id'])->update(['position' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request) {
        $user = auth()->user();

        // Ensure the teacher owns the course
        $chapter = Chapter::where('chapterID', $request->chapterID)->first();
        if (!$chapter) {
            return response()->json(['success' => false, 'message' => 'Chapter not found.']);
        }

        $course = Course::where('courseID', $chapter->courseID)
                ->where('teacherID', $user->teacher->teacherID)
                ->first();

        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to edit this chapter.']);
        }

        // Update chapter name
        $chapter->update([
            'chapterName' => $request->chapterName
        ]);

        return response()->json(['success' => true]);
    }

    public function show($chapterID) {
        $chapter = Chapter::where('chapterID', $chapterID)->firstOrFail();
        $course = Course::where('courseID', $chapter->courseID)->first();
        $teacher = Teacher::where('teacherID', $course->teacherID)->first();

        // Retrieve the teacher's name through the relationship
        $teacherName = $teacher ? $teacher->user->first_name . ' ' . $teacher->user->last_name : 'Unknown Teacher';

        $parts = Part::where('chapterID', $chapterID)->with(['lectureNotes', 'lectureVideos'])->get();

        // Fetch the course image, or default to chapter image if not set
        $courseImage = $course->image ?? $chapter->image;

        return view('AccessStudyResource', compact('chapter', 'course', 'teacherName', 'parts', 'courseImage'));
    }

    public function showChapter($chapterID) {
        // Fetch the chapter
        $chapter = Chapter::findOrFail($chapterID);

        // Fetch the course associated with the chapter
        $course = $chapter->course;

        // Fetch the teacher associated with the course
        $teacher = Teacher::where('teacherID', $course->teacherID)->first();

        // Fetch the teacher's name from the users table
        $teacherName = $teacher ? $teacher->user->first_name . ' ' . $teacher->user->last_name : 'Unknown Teacher';
        // Fetch all parts under the chapter
        $parts = $chapter->parts()->with(['lectureNotes', 'lectureVideos'])->get();

        // Fetch the course image (if available)
        $courseImage = $course->image; // Assuming the course has an 'image' field
        // Pass all data to the view
        return view('StudentAccessStudyResource', compact('course', 'chapter', 'teacherName', 'parts', 'courseImage'));
    }
}
