<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz - {{ $category }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: bold;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .timer {
            font-size: 1.8rem;
            text-align: center;
            color: #dc3545;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .question-box {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .answer-box {
            border: 2px solid #0d6efd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            text-align: center;
            cursor: pointer;
            background-color: #f8f9fa;
            transition: background-color 0.3s, transform 0.3s;
        }
        .answer-box:hover {
            background-color: #0d6efd;
            color: white;
            transform: scale(1.05);
        }
        .selected {
            background-color: #0d6efd;
            color: white;
        }
        .result {
            font-size: 1.5rem;
            text-align: center;
            margin-top: 20px;
            color: #28a745;
        }
        .hidden {
            display: none;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Category : {{ $category }}</h1>
    <div class="timer" id="timer">Time left: 60s</div>

    <form id="quiz-form" action="{{ route('quiz.submit') }}" method="POST">
        @csrf
        <div id="quiz-container">
            @foreach($questions as $index => $question)
            <div class="question-box @if($index !== 0) hidden @endif" data-question-id="{{ $index }}">
                <h3>Question {{ $index + 1 }}:</h3>
                <p>{{ $question->question }}</p>
                <div class="row">
                    @foreach($question->incorrectAnswers as $answer)
                        <div class="col-md-6">
                            <div class="answer-box" data-answer="{{ $answer }}">{{ $answer }}</div>
                            <input type="radio" name="answers[{{ $index }}]" value="{{ $answer }}" class="d-none">
                        </div>
                    @endforeach
                    <div class="col-md-6">
                        <div class="answer-box" data-answer="{{ $question->correctAnswer }}">{{ $question->correctAnswer }}</div>
                        <input type="radio" name="answers[{{ $index }}]" value="{{ $question->correctAnswer }}" class="d-none">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary hidden" id="submit-button">Submit Quiz</button>
        </div>
    </form>
    <div class="text-center">
        <a href="{{ route('home') }}" class="btn btn-secondary">Reset</a>
    </div>
    <div class="result mt-4"></div>
</div>

<script>
    let timeLeft = 30; 
    let currentQuestionIndex = 0;
    let correctAnswersCount = 0;
    let timer;

    function startTimer() {
        timeLeft = 30;
        $('#timer').text('Time left: ' + timeLeft + 's');

        timer = setInterval(function() {
            timeLeft--;
            $('#timer').text('Time left: ' + timeLeft + 's');
            if (timeLeft <= 0) {
                clearInterval(timer);
                $('#quiz-form').submit();
            }
        }, 1000);
    }

    const correctAnswers = @json(array_map(fn($q) => $q->correctAnswer, $questions));
    const totalQuestions = {{ count($questions) }};

    $('.answer-box').click(function() {
        const selectedAnswer = $(this).data('answer');
        const questionId = $(this).closest('.question-box').data('question-id');
        const correctAnswer = correctAnswers[questionId];

        $(this).siblings().removeClass('selected');
        $(this).addClass('selected');

        $(this).siblings('input[type="radio"]').prop('checked', false);
        $(this).next('input[type="radio"]').prop('checked', true);

        if (selectedAnswer === correctAnswer) {
            correctAnswersCount++;
        }

        currentQuestionIndex++;

        if (currentQuestionIndex >= totalQuestions) {
            $('#submit-button').removeClass('hidden');
            clearInterval(timer); 
        } else {
            $('.question-box').eq(currentQuestionIndex - 1).addClass('hidden');
            $('.question-box').eq(currentQuestionIndex).removeClass('hidden');
            clearInterval(timer); 
            startTimer(); 
        }
    });

    startTimer();

    $('#quiz-form').submit(function(event) {
        $('.answer-box').off('click');
        clearInterval(timer);  
    });
</script>

</body>
</html>
