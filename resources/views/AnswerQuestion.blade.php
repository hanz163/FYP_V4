<!DOCTYPE html>
<html lang="en">
    <head>
        @include('navigation')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Answer Questions</title>
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
            }

            .context-container {
                background-color: #ffffff;
                padding: 2rem;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 2rem;
                text-align: left; /* Align text to the left */
            }

            .context-container h4 {
                margin: 0.5rem 0;
                color: #34a853;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .context-container h4.course {
                font-size: 1.5rem; /* Larger font size for Course */
            }

            .context-container h4.chapter {
                font-size: 1.3rem; /* Smaller font size for Chapter */
            }

            .context-container h4.part {
                font-size: 1rem; /* Smaller font size for Part */
                color: #333; /* Black but not too dark for Part */
            }

            .context-container p {
                font-size: 1rem;
                color: #666; /* Darker grey for Part description */
                margin: 0.5rem 0;
            }

            .difficulty {
                color: #2c7a2c;
                font-weight: bold;
            }

            .question {
                margin-top: 10px;
                margin-bottom: 2rem; /* Added gap between questions */
            }

            .question-number {
                margin-bottom: 10px;
            }

            .answer {
                margin-left: 20px;
            }

            .correct {
                background-color: #d4edda;
                padding: 10px;
                border-radius: 5px;
                color: green;
            }

            .incorrect {
                background-color: #f8d7da;
                padding: 10px;
                border-radius: 5px;
                color: red;
            }

            .correct-radio {
                accent-color: green;
            }

            .incorrect-radio {
                accent-color: red;
            }

            ul {
                list-style-type: none;
                padding-left: 0;
            }

            ul li {
                margin-bottom: 15px;
                cursor: pointer;
            }

            .question p {
                margin-bottom: 20px;
            }

            .submit-button {
                display: block;
                margin: 2rem auto;
                padding: 1rem 2rem;
                font-size: 1rem;
                color: #fff;
                background-color: #34a853;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .submit-button:hover {
                background-color: #2d8b44;
            }

            .submit-button:disabled {
                background-color: #ccc;
                cursor: not-allowed;
            }
        </style>
    </head>
    <body>
        <h2>Answer Questions</h2>

        <!-- Display Course, Chapter, Part, and Part Description once at the top -->
        <div class="container">
            <div class="context-container">
                <h4 class="course" style="color:#2d8b44">Course: {{ $course->courseName }}</h4>
                <h4 class="chapter">Chapter: {{ $chapter->chapterName }}</h4>
                <h4 class="part">Part: {{ $part->title }}</h4>
                <p>{{ $part->description }}</p>
            </div>

            <div class="card">
                @if($part->questions)
                    @foreach($part->questions as $index => $question)
                        <div class="question">
                            <p class="difficulty">Difficulty: {{ $question->difficulty->level }}</p>
                            <p class="question-number">{{ $index + 1 }}. {{ $question->question_text }}</p>
                            <ul>
                                @php
                                // Get all answers (correct and wrong)
                                $answers = [
                                ['text' => $question->answers->first()->answer_text, 'value' => 'correct'],
                                ['text' => $question->answers->first()->wrong_answer_1, 'value' => 'wrong_1'],
                                ['text' => $question->answers->first()->wrong_answer_2, 'value' => 'wrong_2'],
                                ['text' => $question->answers->first()->wrong_answer_3, 'value' => 'wrong_3'],
                                ];
                                // Shuffle the answers
                                shuffle($answers);
                                @endphp
                                @foreach($answers as $answer)
                                <li onclick="selectAnswer(this)">
                                    <input type="radio" name="question_{{ $question->QuestionID }}" value="{{ $answer['value'] }}">
                                    <label>{{ $answer['text'] }}</label>
                                </li>
                                @endforeach
                            </ul>
                            <p id="explanation_{{ $question->QuestionID }}" class="explanation" style="display: none;">
                                Explanation: {{ $question->answers->first()->explanation }}
                            </p>
                        </div>
                    @endforeach
                @else
                    <p>No questions available for this part.</p>
                @endif
            </div>
        </div>

        <button class="submit-button" onclick="submitAnswers()">Submit</button>

        <script>
            // Function to select the radio button when clicking on the answer text
            function selectAnswer(listItem) {
                const radioButton = listItem.querySelector('input[type="radio"]');
                if (radioButton) {
                    radioButton.checked = true;
                }
            }

            function submitAnswers() {
                // Disable the submit button
                const submitButton = document.querySelector('.submit-button');
                submitButton.disabled = true;

                // Loop through all questions
                const questions = document.querySelectorAll('.question');
                questions.forEach(question => {
                    const questionId = question.querySelector('input[type="radio"]').name.split('_')[1];
                    const selectedRadio = question.querySelector(`input[name="question_${questionId}"]:checked`);

                    if (selectedRadio) {
                        const selectedValue = selectedRadio.value;
                        const explanation = document.getElementById(`explanation_${questionId}`);
                        explanation.style.display = "block";

                        // Check if the selected answer is correct
                        if (selectedValue === "correct") {
                            // Correct answer
                            selectedRadio.classList.add("correct-radio");
                            explanation.classList.add("correct");
                        } else {
                            // Incorrect answer
                            selectedRadio.classList.add("incorrect-radio");
                            explanation.classList.add("incorrect");

                            // Highlight the correct answer
                            const correctRadio = question.querySelector(`input[name="question_${questionId}"][value="correct"]`);
                            correctRadio.classList.add("correct-radio");
                        }

                        // Disable all radio buttons for this question
                        const allRadios = question.querySelectorAll(`input[name="question_${questionId}"]`);
                        allRadios.forEach(radio => radio.disabled = true);
                    }
                });
            }
        </script>
    </body>
</html>