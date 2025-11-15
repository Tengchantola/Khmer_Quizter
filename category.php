<?php
    session_start();
    include "database/database.php";

    if (!isset($_SESSION['Username'])) {
        $_SESSION['Username'] = "Guest";
        $_SESSION['Email'] = "guest";
        $_SESSION['Role'] = 'Guest';
        $_SESSION['UserID'] = 11;
        $_SESSION['Profile'] = "https://i.postimg.cc/8zn3qLcL/3e7b76dbafbc491605e5b1fccd3ed7b3.jpg";
    }

    if (isset($_SESSION['Role']) && $_SESSION['Role'] === "Admin") {
        header("Location: admin.php");
        exit;
    }

    $currentPage = 'home.php';
    include_once 'nav.php';
?>
<link rel="stylesheet" href="styles/category.css">
<?php

function displayQuizzes($conn, $type, $part)
{
    $displayQuery = "SELECT Quiz.*, UserAccount.*, COUNT(QuizQuestion.QuestionID) AS question_count 
    FROM Quiz 
    JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID 
    LEFT JOIN QuizQuestion ON Quiz.QuizID = QuizQuestion.QuizID
    WHERE Type = '$type'
    GROUP BY Quiz.QuizID
    HAVING question_count > 0;
    ";
    $result = mysqli_query($conn, $displayQuery);
    if ($result) {
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        shuffle($rows);
        $count = 0;
        if (count($rows) > 0) {
            echo "<div class='show'id='$part'>";
            echo "<div class='row mt-3 josefin-sans card-title' >
            <div class='d-flex justify-content-between'>
            <h1>$type</h1>
            </div>
    </div>
    <div class='row cards'>";
            foreach ($rows as $row) {
                $QuizID = $row['QuizID'];
                $Quiztitle = $row['QuizTitle'];
                $Author = $row['Username'];
                $image = $row['Image'];
                $QuizCode = $row['QuizCode'];
                $play = $row['Play'];
                $getquestionamount = "SELECT QuestionID from QuizQuestion where QuizID = '$QuizID'";
                $resultt = mysqli_query($conn, $getquestionamount);
                if ($resultt) {
                    $numRows = mysqli_num_rows($resultt);
                }
                echo "<div class='col-xxl-3 mt-3 col-lg-4 col-md-6 col-12 text-center'>
                        <div class='quiz-card' 
                            data-quizcode='$QuizCode' 
                            data-quizid='$QuizID' 
                            data-questions='$numRows' 
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
                                    $numRows Questions
                                </span>
                            </div>
                        </div>
                    </div>
                </div>";
                $count++;
            }
            echo "</div>";
            echo "</div>";
        } else {
            echo "";
        }
    } else {
        echo "";
    }
}
$type = $_GET['Type'];
$part = $_GET['Part'];
?>
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
                <div class='text-start quizcodee josefin-sans'>123</div>
                <div class='text-start numofques josefin-sans'></div>
                <button type="button" class="leaderbutton josefin-sans">Leaderboard</button><br>
                <button type="button" class="play josefin-sans my-3" data-quiz=""> <i class="bi bi-play-fill"></i> Play</button>
            </div>
        </div>
    </div>
</div>
<div class="quizes container">
    <div class="col-12 button"><button type='button'><i class="bi bi-backspace-fill"></i> Leave</button></div>
    <?php
        displayQuizzes($conn, $type, $part)
    ?>
</div>
<?php

include_once "footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="javascripts/category.js"></script>