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
if ($_SESSION['Role'] !== "Admin") {
    $currentPage = 'myquiz.php';
    include_once "nav.php";
} else {
    $currentPage = 'quizes.php';
    include_once "dashboard.php";
}

$quizid = $_GET['quizid'];
$amountofques = "SELECT QuestionID from QuizQuestion where QuizID = '$quizid'";
$result = mysqli_query($conn, $amountofques);
if ($result) {
    $numQues = mysqli_num_rows($result);
}

$getquizquestion = "SELECT * from Quiz Where QuizID = '$quizid'";
$resultquiz = mysqli_query($conn, $getquizquestion);
if ($resultquiz) {
    while ($row = mysqli_fetch_assoc($resultquiz)) {
        $QuizTitle = $row['QuizTitle'];
    }
}
?>
<link rel="stylesheet" href="styles/edit.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <button class="back"><i class="bi bi-backspace-fill"></i> Back</button>
        </div>
    </div>
</div>

<div class="container quiz mt-3 josefin-sans">
    <div class="row">
        <div class='col-12 text-center'>
            <h1>
                <?php
                echo $QuizTitle;
                ?>
            </h1>
            <input type="text" id="quizidhere" value="<?php echo $quizid ?>" style="display:none">
        </div>
    </div>

</div>
<div class="container py-2 josefin-sans">
    <div class="row">
        <div class="col-12 mt-3">
            <h2>
                <?php
                echo $numQues;
                ?>
                Questions
            </h2>
        </div>
    </div>
</div>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete Question</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Are you sure you want to delete?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="understoodBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="container josefin-sans">
    <div class="row">
        <?php
        $getallquestion = "SELECT * from QuizQuestion join Question on QuizQuestion.QuestionID = Question.QuestionID where QuizID = '$quizid'";
        $resultquestion = mysqli_query($conn, $getallquestion);
        if ($resultquestion) {
            $index = 1;
            while ($row = mysqli_fetch_assoc($resultquestion)) {
                $question = $row['Question'];
                $questionid = $row['QuestionID'];
                echo " <div class='col-12 text-start eques mb-3'><h3>";
                echo $index . ". ";
                echo $question;
                echo " </h3><button class='edit btn btn-warning mx-2'>Edit</button><button class='delete btn btn-danger' >Delete</button> <input type='text' value='$questionid' style='display:none'><input type='text' id='quizid' value='$quizid' style='display:none'></div>";
                $index++;
            }
        }
        ?>
    </div>
    <div class="row text-center mt-3 josefin-sans">
        <div class="col-12"><button class="addquestion btn btn-success"><i class="bi bi-plus-circle-fill"></i> Add Question</button></div>
    </div>
</div>
<script src="javascripts/confirmDialog.js"></script>
<script src="javascripts/edit.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>