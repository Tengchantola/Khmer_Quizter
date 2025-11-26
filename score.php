<?php
include "database/database.php";
session_start();

if (isset($_GET['score']) && isset($_GET['UserID']) && isset($_GET['quizid'])) {
    $score = mysqli_real_escape_string($conn, $_GET['score']);
    $UserID = mysqli_real_escape_string($conn, $_GET['UserID']);
    $QuizID = mysqli_real_escape_string($conn, $_GET['quizid']);
    $addScore = "INSERT INTO Score (QuizID, UserID, ScoreValue) VALUES ('$QuizID', '$UserID', '$score')";
    $result = mysqli_query($conn, $addScore);
    if ($result) {
        echo json_encode(array('success' => true));
        $_SESSION['UserID'] = $UserID;
        $addplays= "UPDATE Quiz SET Play = Play + 1 WHERE QuizID = '$QuizID'";
        $add = mysqli_query($conn,$addplays);
    } else {
        echo json_encode(array('error' => 'Failed to add score to the database'));
    }
} else {
    echo json_encode(array('error' => 'Required parameters are missing'));
}
?>
