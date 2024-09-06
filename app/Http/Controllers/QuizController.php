<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question; 

class QuizController extends Controller
{
    public function index()
    {

        return view('home');
    }

    public function showQuestions($category)
    {

        $apiUrl = 'https://the-trivia-api.com/api/questions?categories=' . urlencode($category);

        $questions = json_decode(file_get_contents($apiUrl));

        session(['questions' => $questions]);

        return view('questions', ['category' => $category, 'questions' => $questions]);
    }

    public function submitQuiz(Request $request)
    {
        $questions = session('questions');
        if (!$questions || count($questions) == 0) {
            return redirect()->route('home')->with('error', 'Quiz expired or no questions available.');
        }

        $userAnswers = $request->input('answers');
        $correctAnswersCount = 0;
        $totalQuestions = count($questions);
        
        $results = [];

        foreach ($questions as $index => $question) {
            $isCorrect = isset($userAnswers[$index]) && $userAnswers[$index] === $question->correctAnswer;
            if ($isCorrect) {
                $correctAnswersCount++;
            }

            $results[] = [
                'question' => $question->question,
                'correctAnswer' => $question->correctAnswer,
                'userAnswer' => $userAnswers[$index] ?? null,
                'isCorrect' => $isCorrect
            ];
        }

        $scorePercentage = ($correctAnswersCount / $totalQuestions) * 100;
        $resultMessage = $scorePercentage < 40 ? 'Failed' 
                            : ($scorePercentage <= 60 ? 'Better' : 'WINNER!');

        return view('result', [
            'results' => $results,
            'scorePercentage' => $scorePercentage,
            'resultMessage' => $resultMessage,
            'correctAnswersCount' => $correctAnswersCount,
            'totalQuestions' => $totalQuestions
        ]);
    }
}
