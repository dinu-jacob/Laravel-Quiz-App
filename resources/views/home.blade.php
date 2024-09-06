<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }

        h1 {
            font-size: 2.5em;
            font-weight: 700;
            text-align: center;
            background: linear-gradient(90deg, #ff8a00, #e52e71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        h3 {
            font-size: 1.5em;
            text-align: center;
            color: #0d6efd;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
        }

        .quiz-box {
            border: 2px solid #0d6efd;
            border-radius: 10px;
            padding: 20px;
            margin: 12px;
            text-align: center;
            background-color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .quiz-box:hover {
            background-color: #0d6efd;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .back-btn {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .navbar {
            background-color: #007bff; 
            padding: 15px;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .btn-danger {
            background-color: #dc3545; 
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333; 
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-primary text-white">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="#">Online Quiz</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
            <form action="{{ route('logout') }}" method="POST" class="d-flex" role="search">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-lg fw-bold" type="submit">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    
<h1>Welcome, {{ Auth::user()->name }}</h1>

    <h3>Select QUIZ Type</h3>
    <div id="quiz-container" class="row">
    </div>
    <div class="pagination-container">
        <nav id="pagination-nav"></nav>
    </div>
    <div id="back-button-container" class="text-center back-btn">
        <button class="btn btn-secondary" id="back-button" style="display: none;">Back to Categories</button>
    </div>
</div>

<script>
$(document).ready(function() {
    const apiUrl = 'https://the-trivia-api.com/api/questions';
    let allQuizzes = [];
    const itemsPerPage = 6; 
    let currentPage = 1;

    const fetchQuizzes = () => {
        $.ajax({
            url: apiUrl,
            method: 'GET',
            success: function(data) {
                allQuizzes = data;
                const categories = [...new Set(data.map(quiz => quiz.category))]; 
                renderCategories(categories);
                setupPagination(categories);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching quizzes:', error);
            }
        });
    };

    const renderCategories = (categories) => {
        const container = $('#quiz-container');
        container.empty();
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedCategories = categories.slice(start, end);

        const leftColumn = $('<div class="col-md-6"></div>');
        const rightColumn = $('<div class="col-md-6"></div>');

        paginatedCategories.forEach((category, index) => {
            const quizBox = `
                <div class="quiz-box">
                    <a href="/quiz/${encodeURIComponent(category)}" class="text-decoration-none">
                        <h5>${category}</h5>
                    </a>
                </div>
            `;
            if (index < 3) {
                leftColumn.append(quizBox); 
            } else {
                rightColumn.append(quizBox); 
            }
        });

        container.append(leftColumn);
        container.append(rightColumn);
    };

    const setupPagination = (categories) => {
        const paginationNav = $('#pagination-nav');
        paginationNav.empty();
        const totalPages = Math.ceil(categories.length / itemsPerPage);

        if (currentPage > 1) {
            paginationNav.append(`<button class="btn btn-primary" id="prev-page">Previous</button>`);
        }

        if (currentPage < totalPages) {
            paginationNav.append(`<button class="btn btn-primary" id="next-page">Next</button>`);
        }

        $('#prev-page').on('click', function() {
            currentPage--;
            renderCategories(categories);
            setupPagination(categories);
        });

        $('#next-page').on('click', function() {
            currentPage++;
            renderCategories(categories);
            setupPagination(categories);
        });
    };

    fetchQuizzes(); 
});
</script>

</body>
</html>
