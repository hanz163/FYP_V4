<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Ensure this exists -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Created Courses</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>

        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f4f4f4;
            }
            .progress-bar {
                height: 8px;
                border-radius: 4px;
                background-color: #e5e7eb;
            }
            .progress-fill {
                height: 8px;
                border-radius: 4px;
                background-color: #3b82f6;
            }
            .course-item {
                cursor: grab;
                transition: background 0.3s ease;
            }
            .course-item:hover {
                background-color: #d1d5db;
            }
            .dragging {
                opacity: 0.5;
            }
            .pagination {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 20px;
            }
            .pagination a, .pagination span {
                margin: 0 5px;
                padding: 8px 12px;
                border-radius: 5px;
                font-size: 14px;
                text-decoration: none;
                background: #fff;
                color: #333;
                border: 1px solid #ddd;
            }
            .pagination a:hover {
                background: #3b82f6;
                color: #fff;
            }
            .pagination .active {
                background: #3b82f6;
                color: #fff;
                font-weight: bold;
            }
            .no-courses {
                text-align: center;
                padding: 20px;
                background-color: #f9fafb;
                border-radius: 8px;
                margin-top: 20px;
            }
            .no-courses img {
                width: 100px;
                height: 100px;
                margin-bottom: 16px;
            }
            .no-courses h2 {
                font-size: 18px;
                font-weight: 600;
                color: #4b5563;
                margin-bottom: 8px;
            }
            .no-courses p {
                font-size: 14px;
                color: #6b7280;
                margin-bottom: 16px;
            }
            .no-courses a {
                background-color: #3b82f6;
                color: white;
                padding: 8px 16px;
                border-radius: 4px;
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
                transition: background-color 0.3s ease;
            }
            .no-courses a:hover {
                background-color: #2563eb;
            }
        </style>

        <script>
document.addEventListener("DOMContentLoaded", function () {
// Initialize Sortable for the arrange courses modal
let sortableList = document.getElementById("sortableCourses");
if (sortableList) {
new Sortable(sortableList, {
animation: 150, // Smooth animation
        ghostClass: "dragging", // Class applied to the ghost element
        onEnd: function (evt) {
        console.log("Moved item from index", evt.oldIndex, "to", evt.newIndex);
        }
});
}

// Handle form submission for editing courses
let editForm = document.getElementById("editCourseForm");
if (editForm) {
editForm.addEventListener("submit", function (event) {
event.preventDefault(); // Prevent default form submission

let courseID = document.getElementById("editCourseID").value;
if (!courseID) {
console.error("Course ID is missing!");
return;
}

let formAction = `/course/update/${courseID}`;
this.action = formAction;
this.submit();
});
}
});
// Function to display course details in the right panel
function showCourseDetails(courseID, courseName, category, description, studentCount, capacityOffered, progress, image) {
document.getElementById("selectedCourseName").innerText = courseName;
document.getElementById("selectedCategory").innerText = category;
document.getElementById("selectedCourseID").innerText = courseID; // Add this line
document.getElementById("selectedDescription").innerText = description;
document.getElementById("studentCount").innerText = studentCount + " students enrolled";
document.getElementById("capacityOffered").innerText = "Capacity: " + capacityOffered;
document.getElementById("selectedCourseImage").src = image;
// Ensure progress is within valid range (0-100)
let progressValue = parseInt(progress, 10) || 0;
progressValue = Math.min(Math.max(progressValue, 0), 100);
// Update progress bar in the right section
let progressBar = document.getElementById("progressBar");
progressBar.style.width = progressValue + "%";
document.getElementById("progressText").innerText = progressValue + "% Progress";
// Update Manage Course button link dynamically
document.getElementById("manageCourseLink").href = `/course/${courseID}/chapters`;
// Hide default message and show course details
document.getElementById("defaultMessage").classList.add("hidden");
document.getElementById("courseDetails").classList.remove("hidden");
// Update progress bar in the left section dynamically
let leftProgressBar = document.getElementById(`progressBar_${courseID}`);
if (leftProgressBar) {
leftProgressBar.style.width = progressValue + "%";
}
}

// Function to open the edit modal and prefill fields
function openEditModal() {
let courseID = document.getElementById("manageCourseLink").href.split('/').slice( - 2, - 1)[0]; // Extract course ID
let courseName = document.getElementById("selectedCourseName").innerText;
let description = document.getElementById("selectedDescription").innerText;
let capacityOffered = document.getElementById("capacityOffered").innerText.replace("Capacity: ", "");
if (!courseID) {
console.error("Course ID is missing!");
return;
}

// Set values in the modal
document.getElementById("editCourseID").value = courseID;
document.getElementById("editCourseName").value = courseName;
document.getElementById("editCourseOverview").value = description;
document.getElementById("editCourseCapacity").value = capacityOffered;
// Show the modal
document.getElementById("editCourseModal").classList.remove("hidden");
}

// Function to delete a course
function deleteCourse() {
let courseID = document.getElementById("manageCourseLink").href.split('/').slice( - 2, - 1)[0]; // Extract course ID

if (!courseID) {
console.error("Course ID is missing!");
return;
}

if (confirm("Are you sure you want to delete this course?")) {
fetch(`/course/${courseID}`, {
method: 'DELETE',
        headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
        alert("Course deleted successfully!");
        window.location.href = "/course/myCreatedCourses"; // Redirect to the created courses page
        } else {
        alert("Failed to delete course.");
        }
        })
        .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while deleting the course.");
        });
}
}

// Function to close the edit modal
function closeEditModal() {
document.getElementById("editCourseModal").classList.add("hidden");
}

// Function to open the arrange modal
function arrangeCourses() {
document.getElementById("arrangeCoursesModal").classList.remove("hidden");
}

// Function to close the arrange modal
function closeArrangeModal() {
document.getElementById("arrangeCoursesModal").classList.add("hidden");
}

// Function to save the new order
function saveCourseOrder() {
let sortedItems = document.querySelectorAll("#sortableCourses .course-item");
let newOrder = Array.from(sortedItems).map((item, index) => ({
courseID: item.getAttribute("data-course-id"), // Get the courseID from the data attribute
        newPosition: index + 1 // New position (starting from 1)
        }));
// Send the new order to the backend
fetch("{{ route('courses.reorder') }}", {
method: "POST",
        headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type": "application/json",
        },
        body: JSON.stringify({ order: newOrder }),
        })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
        alert("Order saved successfully!");
        closeArrangeModal(); // Close the modal
        location.reload(); // Refresh the page to reflect the new order
        } else {
        alert("Failed to save order.");
        }
        })
        .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while saving the order.");
        });
}
        </script>
    </head>
    <body>
        @include('navigation')

        <div class="max-w-6xl mx-auto mt-8 p-4">
            <div class="grid grid-cols-3 gap-6">
                <!-- Left Sidebar: Created Courses -->
                <div class="col-span-1 bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">Created Courses</h2>
                    <!-- Arrange Courses Button -->
                    <button id="arrangeCoursesBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1.5 px-3 text-sm mb-4 rounded-md shadow transition duration-300 ease-in-out" onclick="arrangeCourses()">
                        Arrange Courses
                    </button>

                    <div id="createdCoursesList" class="space-y-4">
                        @if ($courses->isEmpty())
                        <div class="no-courses">
                            <h2>You haven't created any courses yet.</h2>
                            <p>Start by creating your first course to get started.</p>
                            <a href="{{ route('course.create') }}">Create Course</a>
                        </div>
                        @else
                        @foreach ($courses as $course)
                        @php
                        $courseImage = $course->image ? Storage::url($course->image) : asset('images/course-placeholder.jpg');
                        @endphp
                        <div class="course-item bg-gray-100 p-4 rounded-lg flex items-center space-x-4"
                             onclick="showCourseDetails('{{ $course->courseID }}', '{{ $course->courseName }}', '{{ ucfirst($course->category) }}', '{{ $course->description }}', '{{ $course->studentCount }}', '{{ $course->capacityOffered }}', '{{ $course->progress }}', '{{ $courseImage }}')">
                            <img src="{{ $courseImage }}" alt="Course Image" class="w-12 h-12 rounded-full">
                            <div>
                                <p class="text-lg font-semibold">{{ $course->courseName }}</p>
                                <p class="text-sm text-gray-600">Category : {{ ucfirst($course->category) }}</p>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="mt-4">
                        {{ $courses->links() }}
                    </div>
                </div>

                <!-- Right Section: Course Details -->
                <div id="courseDetails" class="col-span-2 bg-white p-6 rounded-lg shadow hidden">
                    <img id="selectedCourseImage" src="{{ asset('images/selected-course.jpg') }}" class="w-full h-60 object-cover rounded-lg" alt="Course Image">
                    <h2 class="text-2xl font-bold mt-4" id="selectedCourseName"></h2>
                    <p class="text-gray-500 text-sm mt-1" id="selectedCategory"></p>
                    <p class="text-gray-500 text-sm mt-1">Course ID: <span id="selectedCourseID"></span></p> <!-- Display courseID -->

                    <h3 class="text-lg font-bold mt-4">Course Overview</h3>
                    <p class="text-gray-600 mt-0.5" id="selectedDescription"></p>

                    <p class="text-gray-600 mt-2" id="studentCount"></p>
                    <p class="text-gray-600 mt-1" id="capacityOffered"></p>

                    <div class="progress-bar mt-4">
                        <div class="progress-fill" id="progressBar" style="width: 0%;"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1" id="progressText">0% Progress</p>

                    <div class="mt-6">
                        <a id="manageCourseLink" href="#" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            Manage Course
                        </a>
                    </div>
                    <div class="mt-4">
                        <button onclick="openEditModal()" 
                                class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                            Edit Course
                        </button>
                        <button onclick="deleteCourse()" 
                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition ml-2">
                            Delete Course
                        </button>
                    </div>
                </div>

                <!-- Default Message (Visible Initially) -->
                <div id="defaultMessage" class="col-span-2 bg-white p-6 rounded-lg shadow flex flex-col justify-center items-center h-64">
                    <img src="{{ asset('photo/selectIcon.png') }}" alt="Select Course Icon" class="w-24 h-24 mb-4">
                    <h2 class="text-2xl font-semibold text-gray-500">Select a Course to View Details</h2>
                </div>
            </div>
        </div>

        <!-- Arrange Courses Modal -->
        <div id="arrangeCoursesModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h2 class="text-lg font-bold mb-4">Arrange Courses</h2>
                <ul id="sortableCourses" class="space-y-2">
                    @foreach ($createdCourses as $course)
                    <li class="course-item bg-gray-100 p-3 rounded flex items-center justify-between"
                        data-course-id="{{ $course->courseID }}">
                        <span>{{ $course->courseName }}</span>
                        <span class="cursor-move">☰</span>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-4 flex justify-end space-x-2">
                    <button onclick="closeArrangeModal()" class="px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                    <button onclick="saveCourseOrder()" class="px-4 py-2 bg-blue-600 text-white rounded">Save Order</button>
                </div>
            </div>
        </div>

        <!-- Edit Course Modal -->
        <div id="editCourseModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-bold mb-4">Edit Course</h2>
                <form id="editCourseForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="editCourseID" name="courseID">

                    <label class="block text-sm font-medium">Course Name</label>
                    <input type="text" name="courseName" id="editCourseName" class="block w-full border p-2 rounded mb-2">

                    <label class="block text-sm font-medium">Overview</label>
                    <textarea name="courseOverview" id="editCourseOverview" class="block w-full border p-2 rounded mb-2"></textarea>

                    <label class="block text-sm font-medium">Capacity</label>
                    <input type="number" name="capacityOffered" id="editCourseCapacity" class="block w-full border p-2 rounded mb-2">

                    <label class="block text-sm font-medium">Upload New Image (Optional)</label>
                    <input type="file" name="courseImage" id="editCourseImage" class="block w-full border p-2 rounded mb-2" accept=".jpg, .jpeg, .png">

                    <div class="flex justify-end">
                        <button type="button" onclick="closeEditModal()" class="mr-2 px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
    </body>
</html>