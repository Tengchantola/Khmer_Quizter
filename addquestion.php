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

$currentPage = 'myquiz.php';
$quizid = $_GET['quizid'];
$_SESSION['QuizID'] = $quizid;
$selectquiz = "SELECT * FROM Quiz where QuizID = '$quizid'";
$result = mysqli_query($conn, $selectquiz);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $quiztitle = $row['QuizTitle'];
        $getquestionamount = "SELECT QuestionID from QuizQuestion where QuizID = '$quizid'";
        $resultt = mysqli_query($conn, $getquestionamount);
        if ($resultt) {
            $numQues = mysqli_num_rows($resultt);
        }
?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="icon" type="image/png" href="./assets/Khmer_Quizter.png">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="styles/addquestion.css">
        <nav>
            <button class="leave leaveBtn josefin-sans"><i class="bi bi-backspace-fill"></i> Leave</button>
        </nav>
        <form class="mrquestion">
            <div class="container main mt-5">
                <div class="row py-3">
                    <div class="col-12">
                        <h1 class="text-end px-4 ques"></h1>
                    </div>
                    <div class="col-12 text-center">
                        <h1 id="session-quiz" class="josefin-sans"><?php echo $quiztitle ?></h1>
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
                    <div class="col-12 text-end mb-5">
                        <input type="text" style="display: none;" value="<?php echo $quizid ?>" id="getquiz">
                        <button class="leave josefin-sans finish" id="finish" type="button">Finish <i class="bi bi-check-all"></i></button>
                        <button class="leave createquestion josefin-sans" id="createquestion">Create <i class="bi bi-check-square-fill"></i></button>
                    </div>
                </div>
            </div>
        </form>
        <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="javascripts/addquestion.js"></script>
<?php
        $_SESSION['questionnum'] = $numQues + 1;
    }
    }
?>