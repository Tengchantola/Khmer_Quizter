<?php
session_start();
include "database/database.php";
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit;
}
if ($_SESSION['Username'] === "Guest") {
    header("Location: index.php");
    exit;
}
if (isset($_SESSION['Role']) && $_SESSION['Role'] === "Admin") {
    header("Location: record.php");
    exit;
}
$currentPage = 'home.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="./assets/Khmer_Quizter.png">
    <link rel="stylesheet" href="styles/createQuestion.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Khmer Quizter</title>
</head>

<nav>
    <button class="leave leaveBtn josefin-sans"><i class="bi bi-backspace-fill"></i> Leave</button>
</nav>
<form class="quiz">
    <div class="container main mt-5">
        <div class="row text-center">
            <div class="col-12 text josefin-sans mt-5">
                <h1>Create Your Quiz</h1>
            </div>
        </div>
        <div class="row inputhere josefin-sans mt-5 py-2">
            <div class="col-xxl-6 col-sm-12 quizname mb-5">
                <h1>Quiz Name</h1>
                <input type="text" placeholder="Quiz name here" id="quizname">
                <h1 class="mt-3">Category</h1>
                <select id="quiztype">
                    <option value="Computer Science">Computer Science</option>
                    <option value="Programming">Programming</option>
                    <option value="Software Engineering">Software Engineering</option>
                    <option value="Web Development">Web Development</option>
                    <option value="Databases">Databases</option>
                    <option value="Networking">Networking</option>
                    <option value="Cybersecurity">Cybersecurity</option>
                    <option value="Artificial Intelligence & Machine Learning">Artificial Intelligence & Machine Learning</option>
                    <option value="Operating Systems">Operating Systems</option>
                    <option value="Computer Hardware">Computer Hardware</option>
                    <option value="Data Structures & Algorithms">Data Structures & Algorithms</option>
                    <option value="Cloud Computing">Cloud Computing</option>
                </select>
            </div>
            <div class="col-xxl-6 col-sm-12 text-center choose">
                <div class="image-upload-container">
                    <h3>Quiz Cover Image</h3>
                    <img id="image-preview"  src="#" alt="Preview Image">
                    <label for="image-upload" class="upload-btn mt-4">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Choose Image
                    </label>
                    <input type="file" id="image-upload" accept="image/*">
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 text-end">
                    <button class="leave createBtn josefin-sans" id="create">Create <i class="bi bi-check-square-fill"></i></button>
                </div>
            </div>
        </div>
        <br>
    </div>
    <div class="container mt-4">
        
    </div>
</form>
<form class="mrquestion mb-10">
    <div class="container main mt-5">
        <div class="row py-3">
            <div class="col-12">
                <h1 class="text-end px-4 ques"></h1>
            </div>
            <div class="col-12 text-center">
                <h1 id="session-quiz" class="josefin-sans"></h1>
                <h2 class="josefin-sans">Creating a question</h2>
            </div>
            <div class="col-12 text-center questioning josefin-sans mt-3">
                <h1 class="text-start">Question</h1>
                <input type="text" placeholder="Question here" id='question'>
                <div class="duration d-flex">
                    <h1>Duration</h1><input type="number" id="time" value="30">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-xxl-6 col-sm-12 answer text-center josefin-sans mt-3">
                    <h1 class="text-start">Answer 1 : </h1>
                    <input type="text" placeholder="Answer here" id="answer1">
                    <input type="text" style="display:none;" placeholder="Answer here" id="iscorrect1" value="0">
                    <i class="bi bi-check-square-fill correct-icon" data-answer="1"></i>
                </div>
                <div class="col-xxl-6 col-sm-12 answer text-center josefin-sans mt-3">
                    <h1 class="text-start">Answer 2 :</h1>
                    <input type="text" placeholder="Answer here" id="answer2">
                    <input type="text" style="display:none;" placeholder="Answer here" id="iscorrect2" value="0">
                    <i class="bi bi-check-square-fill correct-icon" data-answer="2"></i>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-xxl-6 col-sm-12 answer text-center josefin-sans mt-3">
                    <h1 class="text-start">Answer 3 : </h1>
                    <input type="text" placeholder="Answer here" id="answer3">
                    <input type="text" style="display:none;" placeholder="Answer here" id="iscorrect3" value="0">
                    <i class="bi bi-check-square-fill correct-icon" data-answer="3"></i>
                </div>
                <div class="col-xxl-6 col-sm-12 answer text-center josefin-sans mt-3">
                    <h1 class="text-start">Answer 4 :</h1>
                    <input type="text" placeholder="Answer here" id="answer4">
                    <input type="text" style="display:none;" placeholder="Answer here" id="iscorrect4" value="0">
                    <i class="bi bi-check-square-fill correct-icon" data-answer="4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12 text-end">
                <button class="leave josefin-sans finish" id="finish" type="button">Finish <i class="bi bi-check-all"></i></button>
                <button class="leave createquestion josefin-sans" id="createquestion">Create <i class="bi bi-check-square-fill"></i></button>
            </div>
        </div>
    </div>
</form>
<script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="javascripts/createQuestion.js"></script>