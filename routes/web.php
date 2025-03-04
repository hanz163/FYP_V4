<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\DB;

// Home and navigation routes
Route::get('/', function () {
    return view('welcome');
});
Route::get('/navigation', function () {
    return view('navigation');
});

// Authentication routes
Route::get('/register', function () {
    return view('register');
})->name('register.form');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Password Reset routes
Route::get('/forgot-password', [UserController::class, 'show'])->name('forgotPassword');
Route::post('/forgot-password', [UserController::class, 'store'])->name('forgot.password.store');
Route::get('/reset-password', [UserController::class, 'showResetForm'])->name('reset.password.show');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('reset.password.store');
Route::post('/password/reset', [UserController::class, 'resetPassword'])->name('password.update');

//Manage Account routes
Route::get('/personal-info', [PersonalInfoController::class, 'showPersonalInfo'])->name('personalInfo')->middleware('auth');
Route::put('/personal-info/update', [PersonalInfoController::class, 'updatePersonalInfo'])->name('personalInfo.update')->middleware('auth');
Route::put('/personal-info/update-password', [PersonalInfoController::class, 'updatePassword'])->name('personalInfo.updatePassword')->middleware('auth');
Route::delete('/personal-info/delete-account', [PersonalInfoController::class, 'deleteAccount'])->name('personalInfo.deleteAccount')->middleware('auth');

// Course management routes
Route::post('/courses/reorder', [CourseController::class, 'reorder'])->name('courses.reorder');
Route::get('/courses/paginated', [CourseController::class, 'paginatedCourses'])->name('courses.paginated');
Route::get('/course/create', [CourseController::class, 'create'])->name('course.create');
Route::post('/course', [CourseController::class, 'store'])->name('course.store');
Route::get('/course/index', [CourseController::class, 'index'])->name('course.index');
Route::get('/course/search', [CourseController::class, 'search'])->name('course.search');
Route::post('/course/join/{courseID}', [CourseController::class, 'join'])->name('course.join');
Route::patch('/course/update/{courseID}', [CourseController::class, 'update'])->name('course.update');
Route::get('/course/{id}/edit', [CourseController::class, 'edit'])->name('course.edit');
Route::delete('/course/{courseID}', [CourseController::class, 'destroy'])->name('course.destroy');

// Course order management
Route::post('/update-course-order', [CourseController::class, 'updateCourseOrder']);
Route::post('/save-course-order', [CourseController::class, 'saveCourseOrder'])->name('save.course.order');
Route::post('/student/courses/reorder', [CourseController::class, 'studentReorder'])->name('student.courses.reorder');

// Enrolled courses routes
Route::get('/get-all-enrolled-courses', [CourseController::class, 'getEnrolledCourses']);
Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my-courses');
Route::get('/teacher/courses', [CourseController::class, 'myCreatedCourses'])->name('teacher.courses');
Route::delete('/course/myCreatedCourses', [CourseController::class, 'deleteCourse'])->name('course.delete');
Route::get('/course/myCreatedCourses', [CourseController::class, 'myCreatedCourses'])->name('course.myCreatedCourses');
// Chapter management routes
Route::get('/course/{id}/chapters', [ChapterController::class, 'index'])->name('course.chapters');
Route::get('/course/{courseID}/chapter/create', [ChapterController::class, 'create'])->name('chapter.create');
Route::post('/course/{courseID}/chapter/store', [ChapterController::class, 'store'])->name('chapter.store');
Route::get('/chapter/{chapterID}', [ChapterController::class, 'show'])->name('chapter.view');
Route::get('/chapter/{chapterID}/details', [ChapterController::class, 'showChapter'])->name('chapter.show');
Route::post('/chapter/update', [ChapterController::class, 'update'])->name('chapter.update');
Route::delete('/chapter/{id}', [ChapterController::class, 'destroy'])->name('chapter.destroy')->where('id', 'CH[0-9]{4,}');
Route::post('/chapter/reorder', [ChapterController::class, 'reorder'])->name('chapter.reorder');

// Student-specific chapter view
Route::get('/course/{courseID}/chapters/student', [ChapterController::class, 'studentChapters'])->name('student.chapters')->middleware('auth');

// Part management routes
Route::get('/chapter/{chapterID}/parts', [PartController::class, 'showChapterParts'])->name('chapter.parts');
Route::post('/part/store', [PartController::class, 'store'])->name('part.store');
Route::post('/part/create', [PartController::class, 'store'])->name('part.create');
Route::get('/part/{partID}', [PartController::class, 'show'])->name('part.view');

// Study resource upload routes
Route::post('part/{partID}/upload', [PartController::class, 'uploadStudyResource'])->name('part.uploadStudyResource');
Route::post('/studyresource/upload', [PartController::class, 'uploadStudyResource'])->name('studyresource.upload');

// Student and teacher home pages
Route::get('/student/home', function () {
    return view('StudentHomePage');
})->name('student.home');

Route::get('/teacher/home', function () {
    return view('TeacherHomePage');
})->name('teacher.home');

// Lecture videos and notes routes
Route::get('/lecture-videos/{id}', [LectureVideoController::class, 'show']);
Route::get('/lecture-videos/{id}', function ($id) {
    $lectureVideo = DB::table('lecture_videos')->where('id', $id)->first();
    if (!$lectureVideo || !file_exists(storage_path("app/public/{$lectureVideo->file_path}"))) {
        abort(404);
    }
    return response()->file(storage_path("app/public/{$lectureVideo->file_path}"));
})->where('id', '[0-9]+');

Route::get('/lecture-notes/{id}', function ($id) {
    $lectureNote = DB::table('lecture_notes')->where('id', $id)->first();
    if (!$lectureNote || !file_exists(storage_path("app/public/{$lectureNote->file_path}"))) {
        abort(404);
    }
    return response()->file(storage_path("app/public/{$lectureNote->file_path}"));
})->where('id', '[0-9]+');

//Testing Question Use
Route::middleware(['auth'])->group(function () {

    Route::get('/upload-question/{courseID?}/{chapterID?}/{partID?}', [QuestionController::class, 'showUploadForm'])
            ->name('upload.question');
    Route::post('/questions/store', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/edit/{id}', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::post('/questions/update/{id}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/delete/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::post('/categorize-question', [QuestionController::class, 'categorizeQuestion']);
    Route::post('/process-questions', [QuestionController::class, 'processQuestions']);
    Route::post('/save-questions', [QuestionController::class, 'saveQuestion'])->name('questions.save');
    Route::post('/submit-question', [QuestionController::class, 'storeQuestion'])->name('submit.question');
});

// Update Part
Route::put('/parts/{partID}', [PartController::class, 'update'])->name('part.update');
Route::delete('/parts/{partID}', [PartController::class, 'destroy'])->name('part.destroy');
Route::delete('/lecture-notes/{id}', [PartController::class, 'deleteLectureNote'])->name('lecture-note.destroy');
Route::delete('/lecture-videos/{id}', [PartController::class, 'deleteLectureVideo'])->name('lecture-video.destroy');
Route::get('/access-study-resource/{chapterID}', [PartController::class, 'showChapterParts'])->name('access.study.resource');

Route::get('/answer-questions/{partID}', [QuestionController::class, 'showQuestions'])->name('answer.questions');
Route::post('/store-temporary-question', [QuestionController::class, 'storeTemporaryQuestion'])->name('store.temporary.question');
Route::post('/save-temporary-questions', [QuestionController::class, 'saveTemporaryQuestions'])->name('save.temporary.questions');
