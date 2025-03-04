<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Course Chapters</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/ChapterTeacher.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <style>
            h1 {
                color: black;
            }

            .delete-form {
                display: none;
            }

            /* Dragging effects */
            .chapter-card.dragging {
                background-color: #f0f0f0;
                transform: scale(1.05);
                opacity: 0.7;
            }

            /* Editable Chapter Name */
            .editable-chapter-name {
                display: none;
            }

            .chapter-card.editing .editable-chapter-name {
                display: inline-block;
            }

            .chapter-card.editing h2 {
                display: none;
            }

            /* Reminder below the course name */
            .edit-chapter-reminder {
                display: none;
                font-size: 14px;
                color: #777;
                margin-bottom: 20px;
            }

            .edit-chapter-reminder.active {
                display: block;
            }

            .edit-button {
                background-color: #3b82f6; /* Blue-500 */
                color: white;
                font-weight: 500;
                padding: 10px 28px; /* py-1.5 px-3 */
                font-size: 14px; /* text-sm */
                margin-bottom: 16px; /* mb-4 */
                border-radius: 6px; /* rounded-md */
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* shadow */
                transition: background-color 0.3s ease-in-out;
                border: none;
                cursor: pointer;
            }

            .edit-button:hover {
                background-color: #2563eb; /* Blue-600 */
            }

            .view-button,
            .new-chapter-button {
                background-color: #3b82f6; /* Blue-500 */
                color: white;
                font-weight: 500;
                padding: 10px 28px;
                font-size: 14px;
                border-radius: 6px;
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
                transition: background-color 0.3s ease-in-out;
                border: none;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }

            .view-button:hover,
            .new-chapter-button:hover {
                background-color: #2563eb; /* Blue-600 */
            }

            .no-parts-message {
                color: #777;
                font-style: italic;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>

        @include('navigation')

        <div class="course-content">
            <div class="course-header">
                <h1 style="color: black">Course: {{ $course->courseName }}</h1>
                <button class="edit-button" id="editToggle">Edit</button>
            </div>
            <p class="edit-chapter-reminder" id="editReminder">Click on a chapter name to edit it.</p> <!-- Reminder -->

            <div class="chapters" id="chapterList">
                @if($chapters->count())
                @foreach($chapters as $index => $chapter)
                <div class="chapter-card" data-id="{{ $chapter->chapterID }}">
                    <h2>Chapter {{ $index + 1 }} :  {{ $chapter->chapterName }}</h2>
                    <input type="text" class="editable-chapter-name" value="{{ $chapter->chapterName }}" />

                    @php
                    $parts = $chapter->parts->take(3); // Get only the first 3 parts
                    @endphp

                    @if($parts->count())
                    @php $partIndex = 1; @endphp
                    @foreach($parts as $part)
                    <p>Part: {{ $partIndex++ }}: {{ $part->title }}</p>
                    @endforeach
                    @else
                    <p class="no-parts-message">Currently there are no parts available.</p>
                    @endif

                    <a href="{{ route('chapter.view', $chapter->chapterID) }}" class="view-button">View</a>
                    <p></p>

                    <form action="{{ route('chapter.destroy', $chapter->chapterID) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </div>
                @endforeach
                @else
                @endif

                <!-- New Chapter Section -->
                <div class="chapter-card new-chapter">
                    <form id="newChapterForm">
                        @csrf
                        <input type="hidden" name="courseID" value="{{ $course->courseID }}">
                        <input type="text" name="chapterName" placeholder="Chapter Title" required>
                        <textarea name="description" placeholder="Chapter Description" required></textarea>
                        <button type="submit" class="new-chapter-button">Create Chapter</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
$(document).ready(function () {
    let isEditing = false;
    let sortableInstance = null;

    // Hide delete buttons on page load
    $(".delete-form").hide();

    // Toggle edit mode
    $("#editToggle").click(function () {
        isEditing = !isEditing;

        if (isEditing) {
            $(".delete-form").fadeIn(); // Show delete buttons
            $(".view-button").hide(); // Hide view buttons
            enableDragAndDrop();
            $(this).text("Done"); // Change button text
            $("#editReminder").addClass("active"); // Show the reminder
        } else {
            $(".delete-form").fadeOut(); // Hide delete buttons
            $(".view-button").show(); // Show view buttons
            disableDragAndDrop();
            $(this).text("Edit"); // Change button text back
            $("#editReminder").removeClass("active"); // Hide the reminder
        }
    });

    function enableDragAndDrop() {
        sortableInstance = new Sortable(document.getElementById("chapterList"), {
            animation: 200,
            ghostClass: "dragging",
            onStart: function (evt) {
                evt.item.classList.add("dragging");
            },
            onEnd: function (evt) {
                evt.item.classList.remove("dragging");

                let order = [];
                $(".chapter-card").each(function (index) {
                    let chapterID = $(this).data("id");
                    if (chapterID) {
                        order.push({id: chapterID, position: index + 1});
                    }
                });

                // Send updated order via AJAX
                $.ajax({
                    url: "{{ route('chapter.reorder') }}",
                    type: "POST",
                    data: {order: order},
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function (response) {
                        console.log("Order updated successfully!");
                    },
                    error: function (xhr) {
                        alert("Error: " + xhr.responseText);
                    }
                });
            }
        });
    }

    function disableDragAndDrop() {
        if (sortableInstance) {
            sortableInstance.destroy();
            sortableInstance = null;
        }
    }

    // Confirm before deleting a chapter
    $(document).on("click", ".delete-button", function (event) {
        event.preventDefault();
        if (confirm("Are you sure you want to delete this chapter?")) {
            $(this).closest("form").submit();
        }
    });

    // Enable editing of chapter name
    $(document).on("click", ".chapter-card h2", function () {
        let chapterCard = $(this).closest(".chapter-card");
        if (isEditing) {
            chapterCard.addClass("editing");
        }
    });

    // Update chapter name via AJAX when the user finishes editing
    $(document).on("blur", ".editable-chapter-name", function () {
        let chapterCard = $(this).closest(".chapter-card");
        let chapterID = chapterCard.data("id");
        let newChapterName = $(this).val();

        $.ajax({
            url: "{{ route('chapter.update') }}",
            type: "POST",
            data: {
                chapterID: chapterID,
                chapterName: newChapterName,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    chapterCard.removeClass("editing");
                    let index = chapterCard.index() + 1; // Get the chapter index
                    chapterCard.find("h2").text("Chapter " + index + newChapterName);  // Update the chapter name with index
                    chapterCard.find(".editable-chapter-name").hide(); // Hide the input field
                } else {
                    alert("Error updating chapter name.");
                }
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });

    // AJAX Chapter Creation
    $("#newChapterForm").submit(function (event) {
        event.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: "{{ route('chapter.store', $course->courseID) }}",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert("Error creating chapter.");
                }
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });
});
        </script>
    </body>
</html>