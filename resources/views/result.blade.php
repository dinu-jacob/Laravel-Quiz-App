<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        .result-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .result-header h1 {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 0.5rem;
        }
        .result-header p {
            font-size: 1.2rem;
            margin: 0.5rem 0;
        }
        .result-message {
            font-size: 1.5rem;
            font-weight: bold;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin: 20px 0;
        }
        .result-message.failed {
            background-color: #dc3545;
            color: #fff;
        }
        .result-message.better {
            background-color: #ffc107;
            color: #343a40;
        }
        .result-message.winner {
            background-color: #28a745;
            color: #fff;
        }
        .result-table th {
            background-color: #007bff;
            color: #fff;
            text-align: center;
        }
        .result-table td {
            vertical-align: middle;
            text-align: center;
        }
        .result-table .text-success {
            font-weight: bold;
            color: #28a745;
        }
        .result-table .text-danger {
            font-weight: bold;
            color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="result-header">
        <h1>Quiz Results</h1>
        <p><strong>Your Score:</strong> {{ $scorePercentage }}%</p>
        <p class="result-message {{ $scorePercentage < 40 ? 'failed' : ($scorePercentage <= 60 ? 'better' : 'winner') }}">
            <strong>Result:</strong> {{ $resultMessage }}
        </p>
        <p><strong>You got {{ $correctAnswersCount }} out of {{ $totalQuestions }} correct!</strong></p>
    </div>

    <table class="table table-striped result-table">
        <thead>
            <tr>
                <th>Question</th>
                <th>Your Answer</th>
                <th>Correct Answer</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $result)
            <tr>
                <td>{{ $result['question'] }}</td>
                <td>{{ $result['userAnswer'] ?? 'No Answer' }}</td>
                <td>{{ $result['correctAnswer'] }}</td>
                <td>
                    @if ($result['isCorrect'])
                        <span class="text-success">Correct</span>
                    @else
                        <span class="text-danger">Incorrect</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-primary">Go Back to Home</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
