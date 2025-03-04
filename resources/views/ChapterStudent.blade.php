<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Course Chapters</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/ChapterStudent.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <style>
            /* Dragging effects */
            .chapter-card.dragging {
                background-color: #f0f0f0;
                transform: scale(1.05);
                opacity: 0.7;
            }
        </style>
    </head>
    <body>

        @include('navigation')

        <div class="course-content">
            <div class="course-header">
                <h1>Course: {{ $course->courseName }}</h1>
            </div>

            <div class="chapters" id="chapterList">
                @if($chapters->count())
                @foreach($chapters as $chapter)
                <div class="chapter-card" data-id="{{ $chapter->chapterID }}">
                    <h2>{{ $chapter->chapterName }}</h2>

                    @if($chapter->parts->count())
                    @foreach($chapter->parts->take(3) as $index => $part)
                    <p>Part {{ $index + 1 }}: {{ $part->title }}</p>
                    @endforeach
                    @else
                    <p>No parts available for this chapter.</p>
                    @endif

                    <a href="{{ route('chapter.show', $chapter->chapterID) }}" class="view-button">View</a>
                </div>
                @endforeach
                @endif
            </div>

        </div>

        <script>
$(document).ready(function () {
    // Enable drag-and-drop sorting for students
    let sortableInstance = new Sortable(document.getElementById("chapterList"), {
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
});
        </script>
    </body>
</html>
