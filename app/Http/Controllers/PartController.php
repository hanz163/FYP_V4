<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Part;
use App\Models\Teacher;
use App\Models\LectureVideo;
use App\Models\LectureNote;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PartController extends Controller {

    public function store(Request $request) {
        $request->validate([
            'chapterID' => 'required|exists:chapters,chapterID',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lectureNotes.*' => 'nullable|mimes:pdf,doc,docx|max:10240', // 10MB limit
            'lectureVideo.*' => 'nullable|mimes:mp4,mkv,avi|max:51200', // 50MB limit
        ]);

        // Generate custom partID
        $partID = Part::generatePartID();

        // Create the Part with custom partID and default order value of 1
        $part = Part::create([
                    'partID' => $partID,
                    'chapterID' => $request->chapterID,
                    'title' => $request->title,
                    'description' => $request->description,
                    'order' => 1, // Set default order value to 1
        ]);

        // Handle Lecture Notes Upload
        if ($request->hasFile('lectureNotes')) {
            foreach ($request->file('lectureNotes') as $note) {
                $originalName = $note->getClientOriginalName();
                $path = $note->store('lecture_notes', 'public');

                LectureNote::create([
                    'partID' => $part->partID,
                    'title' => $originalName, // Store original filename as title
                    'file_path' => $path,
                ]);
            }
        }

        // Handle Lecture Video Upload
        if ($request->hasFile('lectureVideo')) {
            foreach ($request->file('lectureVideo') as $video) {
                $originalName = $video->getClientOriginalName();
                $path = $video->store('lecture_videos', 'public');

                LectureVideo::create([
                    'partID' => $part->partID,
                    'title' => $originalName, // Store original filename as title
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Part created successfully with study resources.');
    }

    public function upload(Request $request, $partID) {
        $request->validate([
            'partID' => 'required|exists:parts,partID',
            'study_resource' => 'required|file|mimes:pdf,doc,docx,mp4,mkv,avi|max:51200', // 50MB max
        ]);

        $file = $request->file('study_resource');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('study_resources', 'public');

        $extension = $file->getClientOriginalExtension();

        if (in_array($extension, ['pdf', 'doc', 'docx'])) {
            LectureNote::create([
                'partID' => $partID,
                'title' => $originalName, // Store original filename as title
                'file_path' => $path,
            ]);
        } elseif (in_array($extension, ['mp4', 'mkv', 'avi'])) {
            LectureVideo::create([
                'partID' => $partID,
                'title' => $originalName, // Store original filename as title
                'file_path' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Study resource uploaded successfully.');
    }

    public function update(Request $request, $partID) {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $part = Part::findOrFail($partID);
        $part->update([
            'title' => $request->title,
        ]);

        return redirect()->route('access.study.resource', ['chapterID' => $part->chapterID])->with('success', 'Part updated successfully!');
    }

    public function deleteLectureNote($id) {
        $note = LectureNote::findOrFail($id);
        $partID = $note->partID;
        $note->delete();

        return response()->json(['success' => true, 'chapterID' => Part::find($partID)->chapterID]);
    }

    public function deleteLectureVideo($id) {
        $video = LectureVideo::findOrFail($id);
        $partID = $video->partID;
        $video->delete();

        return response()->json(['success' => true, 'chapterID' => Part::find($partID)->chapterID]);
    }

    public function destroy($partID) {
        $part = Part::findOrFail($partID);
        $chapterID = $part->chapterID;

        LectureNote::where('partID', $partID)->delete();
        LectureVideo::where('partID', $partID)->delete();

        $part->delete();

        return response()->json(['success' => true, 'chapterID' => $chapterID]);
    }

    public function uploadStudyResource(Request $request) {
        $request->validate([
            'partID' => 'required|exists:parts,partID',
            'lectureNotes.*' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB limit
            'lectureVideos.*' => 'nullable|file|mimes:mp4,avi,mkv|max:1048576', // 1GB limit
        ]);
        $part = Part::findOrFail($request->partID);
        $chapterID = $part->chapterID ?? null; // Fetch chapterID from part

        if (!$chapterID) {
            return back()->withErrors(['error' => 'Chapter ID is missing.']);
        }

        $chapter = Chapter::with('course')->where('chapterID', $chapterID)->firstOrFail();

        // Handling lecture notes upload
        if ($request->hasFile('lectureNotes')) {
            foreach ($request->file('lectureNotes') as $file) {
                $path = $file->store('lecture_notes', 'public');
                LectureNote::create([
                    'partID' => $part->partID,
                    'title' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        }

        // Handling lecture videos upload
        if ($request->hasFile('lectureVideos')) {
            foreach ($request->file('lectureVideos') as $file) {
                $path = $file->store('lecture_videos', 'public');
                LectureVideo::create([
                    'partID' => $part->partID,
                    'title' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->back()->with('chapter', 'success', 'Study resources uploaded successfully!');
    }

    public function storePart(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'chapterID' => 'required|exists:chapters,id',
            'lecture_notes.*' => 'file|mimes:pdf,docx,txt|max:2048',
            'videos.*' => 'file|mimes:mp4,mkv,avi|max:10240',
        ]);

        // Create the part
        $part = Part::create([
                    'chapter_id' => $request->chapterID,
                    'title' => $request->title,
        ]);

        // Upload and save lecture notes
        if ($request->hasFile('lecture_notes')) {
            foreach ($request->file('lecture_notes') as $file) {
                $originalName = $file->getClientOriginalName();
                $filePath = $file->store('lecture_notes', 'public');

                LectureNote::create([
                    'partID' => $part->id,
                    'title' => $originalName, // Store original filename as title
                    'file_path' => $filePath,
                ]);
            }
        }

        // Upload and save videos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $originalName = $file->getClientOriginalName();
                $filePath = $file->store('lecture_videos', 'public');

                LectureVideo::create([
                    'partID' => $part->id,
                    'title' => $originalName, // Store original filename as title
                    'file_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('part.show', $part->id);
    }

    public function showChapterParts($chapterID) {
        $chapter = Chapter::findOrFail($chapterID);
        $course = $chapter->course;

        $teacher = DB::table('users')
                ->join('teachers', 'users.id', '=', 'teachers.user_id')
                ->select('users.first_name', 'users.last_name')
                ->where('teachers.user_id', $chapter->teacherID)
                ->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher not found for this chapter.');
        }

        // Fetch all parts for the chapter
        $parts = Part::where('chapterID', $chapterID)->with(['lectureNotes', 'lectureVideos'])->get();

        // Set $part to the first part if it exists, otherwise null
        $part = $parts->isEmpty() ? null : $parts->first();

        // Pass $part to the view
        return view('AccessStudyResource', compact('chapter', 'course', 'teacher', 'parts', 'part'));
    }

    public function show($partID) {
        // Retrieve the part along with its lecture notes and videos
        $part = Part::where('partID', $partID)->firstOrFail();

        // Retrieve lecture notes associated with the part
        $lectureNotes = DB::table('lecture_notes')->where('partID', $partID)->get();

        // Retrieve lecture videos associated with the part
        $lectureVideos = DB::table('lecture_videos')->where('partID', $partID)->get();

        return view('parts.show', compact('part', 'lectureNotes', 'lectureVideos'));
    }
}
