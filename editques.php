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
include_once "nav.php";
$quesid = $_GET['quesid'];
$editques = "SELECT * FROM Question  WHERE QuestionID = '$quesid'";
$result = mysqli_query($conn, $editques);
?>
<link rel="stylesheet" href="styles/editQues.css">
<div class="container mt-4 josefin-sans">
    <div class="row">
        <div class="col-12">
            <button class="back"><i class="bi bi-backspace-fill"></i> Back</button>
        </div>
    </div>
</div>
<div class="container quesss mt-3 josefin-sans">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <?php
                if ($result) {
                    if ($row = mysqli_fetch_assoc($result)) {
                        $question = $row['Question'];
                        $duration = $row['Duration'];
                        echo "<div class='col-12 text-center my-4'><div class='questioning'><input type='text' value='$question' id='question'></div></div>";
                        echo "<div class='col-12 text-center my-4' style='display:none;'><div class='questioning'><input type='text'  value='$quesid' id='quesid'></div></div>";
                        echo "<div class='col-12 text-center my-4'><div class='questioning duration'><h1>Duration</h1><input type='number' value='$duration' id='time'></div></div>";
                        $getanswer = "SELECT * From Answers Where QuestionID = '$quesid'";
                        $resultt = mysqli_query($conn, $getanswer);
                        if ($resultt) {
                            $answers = 1;
                            while ($rows = mysqli_fetch_assoc($resultt)) {
                                $answer = $rows['Answer'];
                                $iscorrect = $rows['is_correct'];
                                $answerid = $rows['AnswerID'];
                                echo "<div class='col-12 col-md-6 mt-2 answer'><h4>Answer $answers </h4><input type='text' value='$answer' id='answer$answers'><input type='text' value='$answerid' style='display:none' id='answerid$answers'><input type='text' style='display:none;' class='correctbox' placeholder='Answer here' id='iscorrect$answers' value='$iscorrect'>
                                <i class='bi bi-check-square-fill correct-icon' data-answer='$answers'></i></div>";
                                $answers++;
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 text-center">
            <button class="change" id="change">Change</button>
        </div>
    </div>
</div>

<script src="javascripts/editQues.js"></script>