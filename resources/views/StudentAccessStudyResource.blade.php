<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

            .button-green {
                background-color: #34a853; /* Base green color */
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                text-align: center;
                font-weight: bold;
                display: inline-block;
                transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
            }

            .button-green:hover {
                background-color: #2d8b44; /* Darker green for hover */
                transform: translateY(-2px); /* Slight lift effect */
            }

            .button-outline-green {
                border: 2px solid #34a853; /* Base green color */
                color: #34a853;
                padding: 12px 20px;
                border-radius: 8px;
                text-align: center;
                font-weight: bold;
                display: inline-block;
                transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
            }

            .button-outline-green:hover {
                background-color: #34a853; /* Fill with green on hover */
                color: white; /* Text color changes to white */
            }
            .icon-button {
                background-color: #34a853;
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

                <h2 class="text-2xl font-bold mt-4 text-[#34A853]">{{ $chapter->chapterName }}</h2>

                <div class="flex items-center space-x-2 text-gray-500 mt-2">
                    <span class="font-semibold">Instructor: {{ $teacherName }}</span>
                </div>

                <h3 class="text-lg font-bold mt-4">Chapter Details</h3>
                <p class="text-gray-600 mt-1">{{ $chapter->description }}</p>
            </div>

            <!-- Right Section: Chapter Overview -->
            <div>
                <div class="card">
                    <h3 class="text-xl font-bold mb-4 text-[#34A853]">Chapter Overview</h3>

                    @if ($parts->isNotEmpty())
                    @foreach ($parts as $part)
                    <div class="mb-2">
                        <!-- Clickable Part Section -->
                        <div onclick="toggleDropdown('dropdown-{{ $part->partID }}')" 
                             class="flex justify-between items-center p-4 border-b border-gray-300 part-card cursor-pointer">
                            <div>
                                <h4 class="text-lg font-semibold">{{ $part->title }}</h4>
                                <p class="text-gray-600 text-sm">
                                    {{ $part->lectureNotes->count() }} Lecture Note{{ $part->lectureNotes->count() > 1 ? 's' : '' }} & 
                                    {{ $part->lectureVideos->count() }} Video{{ $part->lectureVideos->count() > 1 ? 's' : '' }}
                                </p>
                            </div>
                            <div class="icon-button">
                                <img src="{{ asset('icon/right-arrow.png') }}" alt="rightArrow"/>
                            </div>
                        </div>

                        <!-- Hidden Dropdown Content -->
                        <div id="dropdown-{{ $part->partID }}" class="dropdown-content bg-gray-100 rounded-lg p-4 hidden">
                            <h5 class="text-md font-semibold text-green-700">Lecture Notes</h5>
                            <ul class="list-disc list-inside text-gray-700">
                                @foreach ($part->lectureNotes as $note)
                                <li>
                                    <a href="{{ url('/lecture-notes/' . $note->id) }}" class="text-[#3a3b3c] no-underline hover:underline" target="_blank">
                                        {{ $note->title }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>

                            <h5 class="text-md font-semibold text-green-700 mt-2">Lecture Videos</h5>
                            <ul class="list-disc list-inside text-gray-700">
                                @foreach ($part->lectureVideos as $video)
                                <li>
                                    <a href="{{ url('/lecture-videos/' . $video->id) }}" class="text-[#3a3b3c] no-underline hover:underline" target="_blank">
                                        {{ $video->title }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-gray-500 text-center">No parts available for this chapter.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bottom Button -->
        <div class="text-center mt-6">
            <button onclick="openDifficultyQuestionModal()" class="button-green w-full max-w-lg block mx-auto">Answer Question</button>
        </div>

        <!-- Difficulty and Question Modal -->
        <div id="difficultyQuestionModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-xl font-bold text-[#34A853]">Select Difficulty and Question</h2>
                    <button onclick="closeDifficultyQuestionModal()" class="text-red-500 text-xl font-bold">&times;</button>
                </div>

                <!-- Course (Disabled) -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Course:</label>
                    <input type="text" value="{{ $course->courseName }}" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" disabled>
                </div>

                <!-- Chapter (Disabled) -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Chapter:</label>
                    <input type="text" value="{{ $chapter->chapterName }}" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" disabled>
                </div>

                <!-- Part Selection (Dropdown) -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Part:</label>
                    <select class="w-full border rounded-lg p-2 mt-1">
                        @foreach ($parts as $part)
                        <option value="{{ $part->partID }}">{{ $part->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Difficulty Selection (Radio Buttons) -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Difficulty:</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="difficulty" value="Easy" class="form-radio">
                            <span class="ml-2">Easy</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="difficulty" value="Medium" class="form-radio">
                            <span class="ml-2">Medium</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="difficulty" value="Hard" class="form-radio">
                            <span class="ml-2">Hard</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between mt-6">
                    <button onclick="closeDifficultyQuestionModal()" class="button-outline-green">Back</button>
                    <button onclick="startQuestions()" class="button-green">Start</button>
                </div>
            </div>
        </div>

        <script>
            function toggleDropdown(id) {
            let dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
            }

            function openDifficultyQuestionModal() {
            document.getElementById('difficultyQuestionModal').classList.remove('hidden');
            }

            function closeDifficultyQuestionModal() {
            document.getElementById('difficultyQuestionModal').classList.add('hidden');
            }

            function startQuestions() {
            const selectedPart = document.querySelector('#difficultyQuestionModal select').value;
            const selectedDifficulty = document.querySelector('#difficultyQuestionModal input[name="difficulty"]:checked').value;
            // Redirect or perform an action based on the selected part and difficulty
            window.location.href = `/questions?partID=${selectedPart}&difficulty=${selectedDifficulty}`;
            }
        </script>
    </body>
</html>