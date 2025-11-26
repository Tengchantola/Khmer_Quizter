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
    header("Location: admin.php");
    exit;
}

$currentPage = 'activity.php';
include_once 'nav.php';

$UserID = $_SESSION['UserID'];
$getactivity = "SELECT 
                Quiz.QuizTitle,
                Quiz.Play,
                Score.UserID,
                Score.QuizID, 
                UserAccount.Username, 
                Quiz.Image, 
                MAX(Score.ScoreValue) AS MaxScore, MAX(Score.Date) AS LastDate
                    FROM Score
                    JOIN Quiz on Score.QuizID = Quiz.QuizID 
                    JOIN UserAccount on Score.UserID = UserAccount.UserID WHERE Score.UserID = '$UserID'
                    GROUP BY Score.QuizID;
                ";
$result = mysqli_query($conn, $getactivity);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <title>Activity</title>
    <link rel="stylesheet" href="styles/activity.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $QuizID = $row['QuizID'];
                    $Quiztitle = $row['QuizTitle'];
                    $Author = $row['Username'];
                    $image = $row['Image'];
                    $score = $row['MaxScore'];
                    $play = $row['Play'];
                    $getquestionamount = "SELECT QuestionID from QuizQuestion where QuizID = '$QuizID'";
                    $resultt = mysqli_query($conn, $getquestionamount);
                    if ($resultt) {
                        $numRows = mysqli_num_rows($resultt);
                        if ($score > $numRows) {
                            $score = $numRows;
                        }
                    }
                    echo "<div class='col-xxl-3 col-lg-4 col-md-6 text-center ccc'>
                        <div class='quiz-card'>
                            <div class='quiz-card-image'>
                                <img src='$image' alt='$Quiztitle'>
                                <span class='quiz-badge'>
                                    <i class='bi bi-play-circle'></i> 
                                    $play Plays
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
                                    <span class='stat-badge'>
                                        <i class='bi bi-check-circle'></i>
                                        Score: $score / $numRows
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "
                <div class='col-12'>
                    <div class='empty-activity'>
                        <div class='empty-activity-icon'>
                            <i class='bi bi-activity'></i>
                        </div>
                        <h2 class='josefin-sans'>No Activity Found</h2>
                        <p class='josefin-sans'>It looks like you haven't played any quizzes yet.</p>
                        <p class='josefin-sans sub-text'>Your completed quizzes and scores will appear here once you start find quiz.</p>
                        <a href='findquiz.php' class='play-now-btn josefin-sans'>
                            <i class='bi bi-play-circle'></i> Start Find Quiz Now
                        </a>
                    </div>
                </div>";
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>
    <script src="javascripts/activity.js"></script>
</body>
</html>