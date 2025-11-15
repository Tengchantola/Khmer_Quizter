<?php
session_start();
include "database/database.php";

if (!isset($_SESSION['Username'])) {
    $_SESSION['Username'] = "Guest";
    $_SESSION['Email'] = "guest";
    $_SESSION['Role'] = 'Guest';
    $_SESSION['UserID'] = 11;
    $_SESSION['Profile'] = "https://media.valorant-api.com/agents/22697a3d-45bf-8dd7-4fec-84a9e28c69d7/displayicon.png";
}

if (isset($_SESSION['Role']) && $_SESSION['Role'] === "Admin") {
    header("Location: admin.php");
    exit;
}

$currentPage = 'findquiz.php';
include_once 'nav.php';
?>
<link rel="stylesheet" href="styles/findQuiz.css">
<div id="notificationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="notificationMessage"></p>
    </div>
</div>
<div class="detail">
    <div class='col-xxl-3 col-lg-4 col-md-6 col-12 text-center'>
        <div class='card card-display'>
            <div class='titlle pt-3 px-3 d-flex'>
                <h5 class='card-title text-start quiztitle josefin-sans'>$Quiztitle</h5>
                <i class="bi bi-x-square-fill josefin-sans close"></i>
            </div>

            <div class='image-container'>
                <img src='uploads/222.jpg' class='images' alt=''>
            </div>
            <div class='card-body contentbody'>
                <div class="col-12 d-flex rawr">
                    <div class='text-start authorr josefin-sans'>By $Author</div>
                    <h6 class='authorname plays'></h6>
                </div>
                <div class='text-start quizcodee josefin-sans'>By $Author</div>
                <div class='text-start numofques josefin-sans'></div>
                <button type="button" class="leaderbutton josefin-sans">Leaderboard</button><br>
                <button type="button" class="play josefin-sans my-3" data-quiz=""> <i class="bi bi-play-fill"></i> Play</button>
            </div>
        </div>
    </div>
</div>
<div class="container find mt-3">
    <div class="row">
        <form action='findquiz.php' method="POST">
            <div class="col-12 meow text-center py-5">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="josefin-sans" placeholder="Search Quiz Name">
                <input type="submit" class="josefin-sans" value="Search">
            </div>
        </form>
    </div>
</div>
<div class="container">
    <div class="row">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $keyword = $_POST['search'];
            $query = "SELECT Quiz.*, UserAccount.*
            FROM Quiz 
            JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID 
            LEFT JOIN (
                SELECT QuizID, COUNT(*) AS question_count
                FROM QuizQuestion
                GROUP BY QuizID
            ) AS QuestionCount ON Quiz.QuizID = QuestionCount.QuizID
            WHERE QuizTitle LIKE ? AND QuestionCount.question_count > 0;
            ";
            $keyword = "%$keyword%";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $keyword);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $numRows = mysqli_num_rows($result);
            if (mysqli_num_rows($result) > 0) {
                echo "<div class='col-12 mt-3'><h2>$numRows Quiz Found.</h2></div>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $QuizID = $row['QuizID'];
                    $QuizCode = $row['QuizCode'];
                    $Quiztitle = $row['QuizTitle'];
                    $Author = $row['Username'];
                    $image = $row['Image'];
                    $play = $row['Play'];
                    $getquestionamount = "SELECT QuestionID from QuizQuestion where QuizID = '$QuizID'";
                    $resultt = mysqli_query($conn, $getquestionamount);
                    if ($resultt) {
                        $numQues = mysqli_num_rows($resultt);
                    }
                    echo "<div class='col-xxl-3 col-lg-4 col-md-6 mt-3 text-center'>
                             <div class='quiz-card' 
                        data-quizcode='$QuizCode' 
                        data-quizid='$QuizID' 
                        data-questions='$numQues' 
                        data-plays='$play' 
                        data-title='$Quiztitle' 
                        data-author='$Author' 
                        data-image='$image'>

                        <div class='quiz-card-image'>
                            <img src='$image' alt='$Quiztitle'>
                            <span class='quiz-badge'>
                                <i class='bi bi-play-circle'></i> $play Plays
                            </span>
                        </div>

                        <div class='quiz-card-body'>
                            <h3 class='quiz-title'>$Quiztitle</h3>
                            <div class='quiz-author'>
                                <i class='bi bi-person-circle'></i>
                                <span>By $Author</span>
                            </div>
                            <div class='quiz-stats'>
                                <span class='stat-badge'>
                                    <i class='bi bi-question-circle'></i>
                                    $numQues Questions
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                ";
                }
            } else {
                echo "<h2 class='mt-3'>No Quiz found.</h2>";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
        ?>
    </div>
</div>
<script src="javascripts/findQuiz.js"></script>