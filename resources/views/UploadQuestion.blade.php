<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Upload Questions</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
            }

            h2 {
                color: #1a73e8;
                text-align: center;
                margin-top: 2rem;
                font-size: 2rem;
                font-weight: 600;
            }

            .container {
                max-width: 1200px;
                margin: 2rem auto;
                padding: 0 1rem;
            }

            .card {
                background-color: #ffffff;
                padding: 2rem;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 1.5rem;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            }

            .upload-box {
                background-color: #f9f9f9;
                border: 2px dashed #1a73e8;
                border-radius: 12px;
                padding: 2rem;
                text-align: center;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .upload-box:hover {
                background-color: #e9f2ff;
            }

            .btn {
                background-color: #1a73e8;
                color: #fff;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                cursor: pointer;
                font-size: 0.875rem;
                font-weight: 500;
                transition: background-color 0.3s ease;
            }

            .btn:hover {
                background-color: #1557b0;
            }

            .btn-success {
                background-color: #3b82f6;
            }

            .btn-success:hover {
                background-color: #2563eb;
            }

            .btn-danger {
                background-color: #dc3545;
            }

            .btn-danger:hover {
                background-color: #c82333;
            }

            .btn-warning {
                background-color: #ffc107;
                color: #000;
            }

            .btn-warning:hover {
                background-color: #e0a800;
            }

            .selection-card {
                background-color: #ffffff;
                padding: 1.5rem;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 1.5rem;
            }

            .selection-card h4 {
                color: #1a73e8;
                margin-bottom: 1rem;
            }

            .selection-card p {
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
                color: #333;
            }

            .selection-card label {
                font-weight: 500;
                color: #1a73e8;
            }

            .selection-card select {
                width: 100%;
                padding: 0.5rem;
                border-radius: 8px;
                border: 1px solid #ddd;
                margin-top: 0.5rem;
            }

            .category-card {
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 1rem;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .category-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .category-card h6 {
                color: #1a73e8;
                margin-bottom: 1rem;
            }

            .category-card ul {
                padding-left: 0;
                list-style: none;
            }

            .category-card ul li {
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
                color: #333;
            }
            .selected-course-card {
                background-color: #e9f2ff;
                border: 1px solid #1a73e8;
                border-radius: 12px;
                padding: 1.5rem;
            }

            .text-muted {
                color: #6c757d;
                font-size: 0.875rem;
            }

            .list-unstyled {
                padding-left: 0;
                list-style: none;
            }

            .list-unstyled li {
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
                color: #333;
            }

            .row {
                display: flex;
                flex-wrap: wrap;
                margin: -0.75rem;
            }

            .col-md-8, .col-md-4 {
                padding: 0.75rem;
            }

            .col-md-8 {
                flex: 0 0 66.666667%;
                max-width: 66.666667%;
            }

            .col-md-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }

            .w-100 {
                width: 100%;
            }

            .mt-2 {
                margin-top: 0.5rem;
            }

            .mt-3 {
                margin-top: 1rem;
            }

            .mt-4 {
                margin-top: 1.5rem;
            }

            .mb-3 {
                margin-bottom: 1rem;
            }

            .fw-bold {
                font-weight: 600;
            }

            .fas {
                margin-right: 0.5rem;
            }

            .visually-hidden {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                border: 0;
            }

            @media (max-width: 768px) {
                .col-md-8, .col-md-4 {
                    flex: 0 0 100%;
                    max-width: 100%;
                }
            }

            .draggable {
                cursor: move;
                margin: 5px;
                padding: 10px;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-family: 'Poppins', sans-serif; /* Ensure font consistency */
                font-size: 0.875rem; /* Ensure font size consistency */
                color: #333; /* Ensure text color consistency */
            }

            .droppable {
                min-height: 100px;
                padding: 10px;
                background-color: #e9f2ff;
                border: 2px dashed #1a73e8;
                border-radius: 4px;
                margin-bottom: 10px;
                font-family: 'Poppins', sans-serif; /* Ensure font consistency */
                font-size: 0.875rem; /* Ensure font size consistency */
                color: #333; /* Ensure text color consistency */

            }

            @keyframes spinner-border {
                to {
                    transform: rotate(360deg);
                }
            }

            .spinner-border {
                display: inline-block;
                width: 2rem;
                height: 2rem;
                vertical-align: text-bottom;
                border: 0.25em solid currentColor;
                border-right-color: transparent;
                border-radius: 50%;
                animation: spinner-border .75s linear infinite;
            }

            /* Format Reminder Styling */
            .format-reminder {
                background-color: #fff3cd; /* Light yellow background */
                border: 1px solid #ffeeba; /* Slightly darker border */
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1.5rem;
            }

            .format-reminder-header {
                display: flex;
                align-items: center;
                font-size: 1.1rem;
                color: #856404; /* Dark yellow text */
                margin-bottom: 0.75rem;
            }

            .format-reminder-header i {
                margin-right: 0.5rem;
                font-size: 1.25rem;
            }

            .format-reminder-body {
                font-size: 0.9rem;
                color: #333;
            }

            .format-example {
                background-color: #f8f9fa; /* Light gray background */
                border: 1px solid #ddd; /* Light border */
                border-radius: 6px;
                padding: 0.75rem;
                margin: 0.75rem 0;
                overflow-x: auto; /* Add horizontal scroll if needed */
            }

            .format-example pre {
                margin: 0;
                font-family: 'Courier New', Courier, monospace;
                font-size: 0.85rem;
                line-height: 1.5;
                color: #333;
            }

            .text-muted {
                color: #6c757d; /* Gray text for additional instructions */
            }
        </style>
        <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                const fileInput = document.getElementById("questionFile");
                                        const uploadBox = document.querySelector(".upload-box");
                                        const processBtn = document.querySelector(".btn-success.mt-3.w-100");
                                        if (!fileInput || !uploadBox || !processBtn) {
                                console.error("One or more elements not found!");
        return;
}

                                        // Upload box click handler
                                uploadBox.addEventListener("click", () => fileInput.click());
        // File input change handler
                                        fileInput.addEventListener("change", () => {
                                        if (fileInput.files.length > 0) {
                                        uploadBox.innerHTML = `<p>${fileInput.files[0].name}</p>`;
                        }
                        });
                                        // Process button click handler
                                        processBtn.addEventListener("click", async function () {
                                        if (!fileInput.files.length) {
                                        alert("Please select a file first.");
                                                return;
        }

                                        // Show the loading modal
                                        document.getElementById("loadingModal").style.display = "block";
                                                const formData= new FormData();
                                                formData.append("file", fileInput.files[0]);
                                                formData.append("partID", document.getElementById('partSelect').value);
                                                try{
                                                const response = await fetch('/process-questions', {
                                                method: "POST",
                                                        headers: {
                                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                                                        },
                                                        body: formData
                                                });
                                                        const data = await response.json();
                                                        console.log("API Response:", data); // Log the API response

                                                        // Hide the loading modal
                                                        document.getElementById("loadingModal").style.display = "none";
                                                if (data.error) {
                                                alert(data.error);
                                                } else {
                                                // Ensure the response has the expected structure
                                                if (data.categorized_questions && data.categorized_questions.easy) {
                                                        document.getElementById("easyQuestions").innerHTML =
                        data.categorized_questions.easy.map(q => `
                                <li class="draggable" 
                                    data-question="${q.question}" 
                                    data-correct-answer="${q.correct_answer}" 
                                    data-wrong-answer-1="${q.wrong_answer_1}" 
                                    data-wrong-answer-2="${q.wrong_answer_2}" 
                                    data-wrong-answer-3="${q.wrong_answer_3}" 
                                    data-explanation="${q.explanation}"
                                    data-difficulty="easy"> <!-- Set initial difficulty level -->
                                    ${q.question}
                                </li>
                                                        `).join("");
                                                        document.getElementById("moderateQuestions").innerHTML =
                        data.categorized_questions.normal.map(q => `
                                <li class="draggable" 
                                    data-question="${q.question}" 
                                    data-correct-answer="${q.correct_answer}" 
                                    data-wrong-answer-1="${q.wrong_answer_1}" 
                                    data-wrong-answer-2="${q.wrong_answer_2}" 
                                    data-wrong-answer-3="${q.wrong_answer_3}" 
                                    data-explanation="${q.explanation}"
                                    data-difficulty="normal"> <!-- Set initial difficulty level -->
                                    ${q.question}
                                </li>
                            `).join("");
                                                        document.getElementById("hardQuestions").innerHTML =
                                                        data.categorized_questions.hard.map(q => `
                                <li class="draggable" 
                                    data-question="${q.question}" 
                                    data-correct-answer="${q.correct_answer}" 
                                    data-wrong-answer-1="${q.wrong_answer_1}" 
                                    data-wrong-answer-2="${q.wrong_answer_2}" 
                                    data-wrong-answer-3="${q.wrong_answer_3}" 
                                    data-explanation="${q.explanation}"
                                    data-difficulty="hard"> <!-- Set initial difficulty level -->
                                    ${q.question}
                                </li>
                            `).join("");
                                                        initializeDragAndDrop(); // Initialize drag-and-drop after populating questions
                                                } else {
                                                console.error("Invalid API response structure:", data);
                                                        alert("Invalid response from the server.");
                                                }
                                                }
                                                } catch (error) {
                                        // Hide the loading modal in case of error
                                        document.getElementById("loadingModal").style.display = "none";
                                                console.error("Error:", error);
                                                alert("An error occurred while processing the file.");
                                        }
                                        });
                                        // Initialize drag-and-drop functionality
                                                function initializeDragAndDrop() {
                                                const draggables = document.querySelectorAll('.draggable');
                                                const droppables = document.querySelectorAll('.droppable');

            draggables.forEach(draggable => {
                draggable.setAttribute('draggable', true);
                draggable.addEventListener('dragstart', () => {
                    draggable.classList.add('dragging');
                });
                draggable.addEventListener('dragend', () => {
                    draggable.classList.remove('dragging');
                    // Update the difficulty level of the question
                    const newDifficulty = draggable.closest('.droppable').getAttribute('data-difficulty');
                    draggable.setAttribute('data-difficulty', newDifficulty);
                    console.log(`Question difficulty updated to: ${newDifficulty}`); // Debugging line
                });
            });

            droppables.forEach(droppable => {
                droppable.addEventListener('dragover', e => {
                    e.preventDefault();
                    const draggable = document.querySelector('.dragging');
                    droppable.appendChild(draggable);
                });

                droppable.addEventListener('drop', e => {
                    e.preventDefault();
                    const draggable = document.querySelector('.dragging');
                    droppable.appendChild(draggable);
                });
            });
        }

        // Save all questions button click handler
        document.getElementById('saveQuestions').addEventListener('click', async function () {
            const questions = document.querySelectorAll('.draggable');
            const partID = document.getElementById('partSelect').value;

            const questionsToSave = [];
            const uniqueQuestions = new Set();

            questions.forEach(question => {
                const questionText = question.getAttribute('data-question');
                const difficultyLevel = question.getAttribute('data-difficulty'); // Ensure this is correctly set

                const correctAnswer = question.getAttribute('data-correct-answer');
                const wrongAnswer1 = question.getAttribute('data-wrong-answer-1');
                const wrongAnswer2 = question.getAttribute('data-wrong-answer-2');
                const wrongAnswer3 = question.getAttribute('data-wrong-answer-3');
                const explanation = question.getAttribute('data-explanation');

                if (!uniqueQuestions.has(questionText) && correctAnswer && wrongAnswer1 && wrongAnswer2 && wrongAnswer3 && explanation) {
                    uniqueQuestions.add(questionText);
                    questionsToSave.push({
                        question_text: questionText,
                        difficulty_level: difficultyLevel, // Ensure this is correctly set
                        partID: partID,
                        correct_answer: correctAnswer,
                        wrong_answer_1: wrongAnswer1,
                        wrong_answer_2: wrongAnswer2,
                        wrong_answer_3: wrongAnswer3,
                        explanation: explanation
                    });
                }
            });

            if (questionsToSave.length === 0) {
                alert("No valid questions to save.");
                return;
            }

            try {
                const response = await fetch('/save-questions', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ questions: questionsToSave })
                });

                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    const text = await response.text();
                    console.error("Expected JSON, but got:", text);
                    throw new Error("Server returned an invalid response.");
                }

                const data = await response.json();
                if (data.success) {
                    alert('All questions have been saved!');
                    window.location.reload(); // Refresh the page after saving
                } else {
                    console.error('Error saving questions:', data.error);
                    alert('Error saving questions: ' + data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while saving the questions.');
            }
        });
    });
        </script> 
    </head>
    <body>
        <header>
            @include('navigation')
        </header>
        <h2>Upload Question</h2>

        <div class="container">
            <div class="row">
                <!-- Upload Section -->
                <div class="col-md-8">
                    <div class="card">
                        <h4 class="mb-3">Upload Questions for AI Leveling</h4>

                        <div class="upload-box">
                            <input type="file" id="questionFile" class="visually-hidden" accept=".doc,.docx">
                            <p class="text-muted mt-2">Click Here to Select Your Question Docx.</p>
                        </div>
                        <button class="btn btn-success mt-3 w-100">Process with AI</button>
                    </div>

                    <!-- AI Categorized Questions -->
                    <div class="card mt-4">
                        <h4 class="mb-3">AI Categorized Questions</h4>
                        <div class="row">
                            <!-- Easy -->
                            <div class="col-md-4">
                                <div class="category-card droppable" data-difficulty="easy">
                                    <h6 class="fw-bold"><i class="fas fa-check-circle"></i> Easy</h6>
                                    <ul id="easyQuestions" class="list-unstyled">
                                        <!-- Easy questions will be populated here -->
                                    </ul>
                                </div>
                            </div>
                            <!-- Moderate -->
                            <div class="col-md-4">
                                <div class="category-card droppable" data-difficulty="normal">
                                    <h6 class="fw-bold"><i class="fas fa-check-circle"></i> Moderate</h6>
                                    <ul id="moderateQuestions" class="list-unstyled">
                                        <!-- Moderate questions will be populated here -->
                                    </ul>
                                </div>
                            </div>
                            <!-- Difficult -->
                            <div class="col-md-4">
                                <div class="category-card droppable" data-difficulty="hard">
                                    <h6 class="fw-bold"><i class="fas fa-check-circle"></i> Difficult</h6>
                                    <ul id="hardQuestions" class="list-unstyled">
                                        <!-- Difficult questions will be populated here -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Course Details Section -->
                <div class="col-md-4">
                    <div class="card selection-card">
                        <h4>Selected Course Details</h4>
                        <p><strong>Course:</strong> {{ $course->courseName ?? 'Not Selected' }}</p>
                        <p><strong>Chapter:</strong> {{ $chapter->chapterName ?? 'Not Selected' }}</p>
                        <label for="partSelect"><strong>Select Part:</strong></label>
                        <select id="partSelect">
                            <option value="" disabled>Select a Part</option>
                            @foreach ($parts as $p)
                            <option value="{{ $p->partID }}" {{ isset($part) && $part->partID == $p->partID ? 'selected' : '' }}>
                                {{ $p->title }}
                            </option>
                            @endforeach
                        </select>
                        <button id="saveQuestions" class="btn btn-primary mt-3 w-100">Save Questions</button>
                    </div>
                </div>
            </div>

            <div class="format-reminder">
                <div class="format-reminder-header">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Important Notice</strong>
                </div>
                <div class="format-reminder-body">
                    <p>The uploaded <strong>.docx</strong> file must follow the format below (compulsory):</p>
                    <div class="format-example">
                        <pre>
Who wrote the play "Romeo and Juliet"?
    Correct Answer: William Shakespeare
    Wrong Answer 1: Charles Dickens
    Wrong Answer 2: Mark Twain
    Wrong Answer 3: Jane Austen
Explanation: "Romeo and Juliet" is a tragedy written by William Shakespeare.

What is the largest planet in the solar system?
    Correct Answer: Jupiter
    Wrong Answer 1: Saturn
    Wrong Answer 2: Earth
    Wrong Answer 3: Mars
Explanation: Jupiter is the largest planet with a diameter of about 139,820 km.

What is the square root of 64?
    Correct Answer: 8
    Wrong Answer 1: 6
    Wrong Answer 2: 10
    Wrong Answer 3: 12
Explanation: The square root of 64 is 8 because 8 × 8 = 64.

                        </pre>
                    </div>
                    <p class="text-muted">Ensure each question follows this exact structure for proper processing.</p>
                </div>
            </div>

        </div>

        <!-- Loading Modal -->
        <div id="loadingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Processing with AI...</p>
            </div>
        </div>
    </body>
</html>
