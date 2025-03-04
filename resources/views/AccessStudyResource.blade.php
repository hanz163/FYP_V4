<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Ensure this exists -->
        <title>{{ $chapter->chapterName }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f4f4f4;
            }
            .card {
                background-color: white;
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            .button-green {
                background-color: #22c55e;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                text-align: center;
                font-weight: bold;
                display: inline-block;
                transition: 0.3s;
            }
            .button-green:hover {
                background-color: #16a34a;
            }
            .button-outline {
                border: 2px solid #22c55e;
                color: #22c55e;
                padding: 12px 20px;
                border-radius: 8px;
                text-align: center;
                font-weight: bold;
                display: inline-block;
                transition: 0.3s;
            }
            .button-outline:hover {
                background-color: #22c55e;
                color: white;
            }
            .icon-button {
                background-color: #22c55e;
                color: white;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                font-size: 18px;
            }
            .hidden {
                display: none;
            }

            .part-card {
                background-color: #f9f9f9; /* Light gray */
                border-radius: 12px;
                padding: 15px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); /* Soft shadow */
                transition: 0.3s;
            }

            .part-card:hover {
                background-color: #f3f3f3; /* Slight hover effect */
                transform: translateY(-2px);
            }

            .part-card:not(:last-child) {
                margin-bottom: 10px; /* Adjust as needed */
            }

            .course-image {
                max-width: 100%; /* Ensure it doesn't exceed the container width */
                height: auto; /* Maintain the aspect ratio */
                border-radius: 10px; /* Round the corners */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add a subtle shadow */
                object-fit: cover; /* Crop and cover the space proportionally */
                transition: transform 0.3s ease; /* Smooth hover effect */
            }

            .button-blue {
                background-color: #3b82f6;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                text-align: center;
                font-weight: bold;
                display: inline-block;
                transition: 0.3s;
            }
            .button-blue:hover {
                background-color: #2563eb;
            }
            .button-outline {
                border: 2px solid #3b82f6;
                color: #3b82f6;
                padding: 12px 20px;
                border-radius: 8px;
                text-align: center;
                font-weight: bold;
                display: inline-block;
                transition: 0.3s;
            }
            .button-outline:hover {
                background-color: #3b82f6;
                color: white;
            }
            .icon-button {
                background-color: #3b82f6;
                color: white;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                font-size: 18px;
            }

            .icon-button img {
                width: 20px;
                height: 20px;
            }

            .button-red {
                background-color: #ef4444;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                text-align: center;
                font-weight: bold;
                display: inline-block;
                transition: 0.3s;
            }
            .button-red:hover {
                background-color: #dc2626;
            }
        </style>

    </head>
    <body>
        @include('navigation')

        <div class="max-w-6xl mx-auto mt-10 grid grid-cols-2 gap-8">
            <!-- Left Section: Chapter Details -->
            <div class="card">
                @if ($courseImage)
                <img src="{{ asset('storage/' . $courseImage) }}" alt="Course Image" class="course-image">
                @else
                <p>No image available.</p>
                @endif

                <h2 class="text-2xl font-bold mt-4">{{ $chapter->chapterName }}</h2>

                <div class="flex items-center space-x-2 text-gray-500 mt-2">
                    <span class="font-semibold">Instructor : {{ $teacherName }}</span>
                </div>

                <h3 class="text-lg font-bold mt-4">Chapter Details</h3>
                <p class="text-gray-600 mt-1">{{ $chapter->description }}</p>
            </div>

            <!-- Right Section: Chapter Overview -->
            <div>
                <div class="card">
                    <h3 class="text-xl font-bold mb-4">Chapter Overview</h3>

                    @if ($parts->isNotEmpty())
                    @foreach ($parts as $part)
                    <div class="mb-2">
                        <!-- Clickable Part Section -->
                        <div class="flex justify-between items-center p-4 border-b border-gray-300 part-card cursor-pointer" onclick="toggleDropdown('dropdown-{{ $part->partID }}')">
                            <div>
                                <h4 class="text-lg font-semibold">{{ $part->title }}</h4>
                                <p class="text-gray-600 text-sm">
                                    {{ $part->lectureNotes->count() }} Lecture Note{{ $part->lectureNotes->count() > 1 ? 's' : '' }} & 
                                    {{ $part->lectureVideos->count() }} Video{{ $part->lectureVideos->count() > 1 ? 's' : '' }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="openModifyPartModal(
                                        '{{ $part->partID }}',
                                        '{{ $part->title }}',
                                                    {{ json_encode($part->lectureNotes) }},
                                                                {{ json_encode($part->lectureVideos) }}
)" class="icon-button">
                                    <img src="{{ asset('icon/settings.png') }}" alt="Edit"/>
                                </button>
                            </div>
                        </div>

                        <!-- Hidden Dropdown Content -->
                        <div id="dropdown-{{ $part->partID }}" class="dropdown-content bg-gray-100 rounded-lg p-4 hidden">
                            <h5 class="text-md font-semibold text-green-700">Lecture Notes</h5>
                            <ul class="list-disc list-inside text-gray-700">
                                @foreach ($part->lectureNotes as $note)
                                <li>
                                    <a href="{{ url('/lecture-notes/' . $note->id) }}?title={{ urlencode($note->title) }}" class="text-[#3a3b3c] no-underline hover:underline" target="_blank">
                                        {{ $note->title }}
                                    </a>
                                </li>                                
                                @endforeach
                            </ul>

                            <h5 class="text-md font-semibold text-green-700 mt-2">Lecture Videos</h5>
                            <ul class="list-disc list-inside text-gray-700">
                                @foreach ($part->lectureVideos as $video)
                                <li>
                                    <a href="{{ url('/lecture-videos/' . $video->id) }}?title={{ urlencode($video->title) }}" class="text-[#3a3b3c] no-underline hover:underline" target="_blank">
                                        {{ $video->title }}
                                    </a>
                                </li>                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-gray-500 text-center">No parts available for this chapter.</p>
                    @endif
                </div>

                <div class="mt-4 text-center">
                    <button onclick="openUploadModal()" class="button-blue w-full block">
                        Upload Study Resources
                    </button>
                    <button onclick="openPartModal()" class="button-outline w-full block mt-3">Create New Part</button>
                </div>
            </div>
        </div>

        @php
        $part = $part ?? null;
        @endphp

        <div class="text-center mt-6">
            @if ($part)
            <a href="{{ route('upload.question', ['courseID' => $course->courseID, 'chapterID' => $chapter->chapterID, 'partID' => $part->partID]) }}" class="button-blue inline-block px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                Upload Questions
            </a>
            @else
            <p class="text-gray-500">No parts available. Please create a part first.</p>
            @endif
        </div>

        <!-- Upload Study Resource Modal -->
        <div id="uploadModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-xl font-bold">Upload Study Resource</h2>
                    <button onclick="closeUploadModal()" class="text-red-500 text-xl font-bold">&times;</button>
                </div>

                @if ($parts->isNotEmpty())
                <form action="{{ route('studyresource.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mt-3">Part:</label>
                    <select name="partID" class="w-full border rounded-lg p-2 mt-1" required>
                        @foreach ($parts as $part)
                        <option value="{{ $part->partID }}">{{ $part->title }}</option>
                        @endforeach
                    </select>

                    <label class="block text-sm font-medium text-gray-700 mt-3">Upload Lecture Notes:</label>
                    <input type="file" name="lectureNotes[]" multiple accept=".doc,.docx,.pdf" class="w-full border rounded-lg p-2 mt-1" required>

                    <label class="block text-sm font-medium text-gray-700 mt-3">Upload Lecture Videos:</label>
                    <input type="file" name="lectureVideos[]" multiple accept=".mp4,.avi,.mkv" class="w-full border rounded-lg p-2 mt-1">

                    <p class="text-gray-500 text-xs mt-2">
                        Max file size: 1GB. Accepted formats: DOC, DOCX, AVI, MP4.
                    </p>

                    <div class="flex justify-between mt-4">
                        <button type="submit" class="button-blue">Confirm</button>
                        <button type="button" onclick="closeUploadModal()" class="button-outline">Cancel</button>
                    </div>
                </form> 
                @else
                <p class="text-gray-500 text-center">No parts available for this chapter.</p>
                @endif
            </div>
        </div>

        <!-- Create New Part Modal -->
        <div id="createPartModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-xl font-bold">Create New Part</h2>
                    <button onclick="closePartModal()" class="text-red-500 text-xl font-bold">&times;</button>
                </div>

                <form action="{{ route('part.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="chapterID" value="{{ $chapter->chapterID }}">

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700">Title</span>
                        <input type="text" name="title" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700">Description</span>
                        <textarea name="description" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700">Upload Lecture Note (PDF/DOC/DOCX)</span>
                        <input type="file" name="lectureNotes[]" multiple accept=".pdf,.doc,.docx" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700">Upload Lecture Video (MP4/MKV/AVI)</span>
                        <input type="file" name="lectureVideo[]" multiple accept=".mp4,.mkv,.avi" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                    </label>

                    <button type="submit" class="button-blue mt-4">Create Part</button>
                </form>
            </div>
        </div>

        <!-- Modify Part Modal -->
        <div id="modifyPartModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-xl font-bold">Modify Part</h2>
                    <button onclick="closeModifyPartModal()" class="text-red-500 text-xl font-bold">&times;</button>
                </div>
                <form id="modifyPartForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Part Title -->
                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700">Title</span>
                        <input type="text" name="title" id="partTitle" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                    </label>
                    <!-- Lecture Notes -->
                    <div class="mt-4">
                        <h5 class="text-md font-semibold text-green-700">Lecture Notes</h5>
                        <ul id="lectureNotesList" class="list-disc list-inside text-gray-700">
                            <!-- Lecture notes will be dynamically populated here -->
                        </ul>
                    </div>
                    <!-- Lecture Videos -->
                    <div class="mt-4">
                        <h5 class="text-md font-semibold text-green-700">Lecture Videos</h5>
                        <ul id="lectureVideosList" class="list-disc list-inside text-gray-700">
                            <!-- Lecture videos will be dynamically populated here -->
                        </ul>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex justify-between mt-4">
                        <button type="submit" class="button-blue">Save Changes</button>
                        <button type="button" onclick="closeModifyPartModal()" class="button-outline">Cancel</button>
                    </div>
                </form>
                <!-- Delete Button -->
                <div class="mt-4 text-center">
                    <button onclick="deletePart()" class="button-red w-full block">
                        Delete Part
                    </button>
                </div>
            </div>
        </div>

        <script>
            function openUploadModal() {
            document.getElementById('uploadModal').classList.remove('hidden');
            }

            function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            }

            function openPartModal() {
            document.getElementById('createPartModal').classList.remove('hidden');
            }

            function closePartModal() {
            document.getElementById('createPartModal').classList.add('hidden');
            }

            function toggleDropdown(id) {
            let dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
            }

            let currentPartID = null; // Store the current part ID globally

            function openModifyPartModal(partID, title, lectureNotes, lectureVideos) {
            currentPartID = partID; // Set the current part ID
            // Set part ID in the form action
            document.getElementById('modifyPartForm').action = `/parts/${partID}`;
            // Set part title
            document.getElementById('partTitle').value = title;
            // Populate lecture notes
            const lectureNotesList = document.getElementById('lectureNotesList');
            lectureNotesList.innerHTML = lectureNotes.map(note => `
                    <li>
                        ${note.title}
                        <button onclick="deleteLectureNote(${note.id})" class="text-red-500 ml-2">Delete</button>
                    </li>
                `).join('');
            // Populate lecture videos
            const lectureVideosList = document.getElementById('lectureVideosList');
            lectureVideosList.innerHTML = lectureVideos.map(video => `
                    <li>
                        ${video.title}
                        <button onclick="deleteLectureVideo(${video.id})" class="text-red-500 ml-2">Delete</button>
                    </li>
                `).join('');
            // Show modal
            document.getElementById('modifyPartModal').classList.remove('hidden');
            }

            function deletePart() {
            if (confirm('Are you sure you want to delete this part? This action cannot be undone.')) {
            fetch(`/parts/${currentPartID}`, {
            method: 'DELETE',
                    headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
            })
                    .then(response => response.json())
                    .then(data => {
                    if (data.success) {
                    alert('Part deleted successfully!');
                    window.location.href = `/access-study-resource/${data.chapterID}`; // Redirect to the chapter's study resource page
                    } else {
                    alert('Failed to delete part.');
                    }
                    })
                    .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the part.');
                    });
            }
            }

            function deletePart() {
            if (confirm('Are you sure you want to delete this part? This action cannot be undone.')) {
            fetch(`/parts/${currentPartID}`, {
            method: 'DELETE',
                    headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
            })
                    .then(response => response.json())
                    .then(data => {
                    if (data.success) {
                    alert('Part deleted successfully!');
                    window.location.href = `/access-study-resource/${data.chapterID}`; // Redirect to the chapter's study resource page
                    } else {
                    alert('Failed to delete part.');
                    }
                    })
                    .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the part.');
                    });
            }
            }

            function deleteLectureNote(id) {
            if (confirm('Are you sure you want to delete this lecture note?')) {
            fetch(`/lecture-notes/${id}`, {
            method: 'DELETE',
                    headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
            })
                    .then(response => response.json())
                    .then(data => {
                    if (data.success) {
                    alert('Lecture note deleted successfully!');
                    window.location.href = `/access-study-resource/${data.chapterID}`; // Redirect to the chapter's study resource page
                    } else {
                    alert('Failed to delete lecture note.');
                    }
                    })
                    .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the lecture note.');
                    });
            }
            }

            function deleteLectureVideo(id) {
            if (confirm('Are you sure you want to delete this lecture video?')) {
            fetch(`/lecture-videos/${id}`, {
            method: 'DELETE',
                    headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
            })
                    .then(response => response.json())
                    .then(data => {
                    if (data.success) {
                    alert('Lecture video deleted successfully!');
                    window.location.href = `/access-study-resource/${data.chapterID}`; // Redirect to the chapter's study resource page
                    } else {
                    alert('Failed to delete lecture video.');
                    }
                    })
                    .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the lecture video.');
                    });
            }
            }

            function closeModifyPartModal() {
            document.getElementById('modifyPartModal').classList.add('hidden');
            }
        </script>
    </body>
</html>