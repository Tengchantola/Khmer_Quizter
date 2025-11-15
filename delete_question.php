<?php
include "database/database.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["quesid"])) {
        $quesid = $_POST["quesid"];
        $sql = "DELETE FROM Answers WHERE QuestionID = ?";
        $delsql = "DELETE FROM QuizQuestion WHERE QuestionID = ?";
        $dellast = "DELETE FROM Question WHERE QuestionID = ?";
        $stmt1 = mysqli_prepare($conn, $sql);
        $stmt2 = mysqli_prepare($conn, $delsql);
        $stmt3 = mysqli_prepare($conn, $dellast);
        if ($stmt1 && $stmt2 && $stmt3) {
            mysqli_stmt_bind_param($stmt1, "s", $quesid);
            mysqli_stmt_bind_param($stmt2, "s", $quesid);
            mysqli_stmt_bind_param($stmt3, "s", $quesid);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_execute($stmt3);
            if (mysqli_affected_rows($conn) > 0) {
                echo "Question and associated data deleted successfully";
                
            } else {
                echo "No records found for the provided question ID";
            }
        } else {
            echo "Error: Unable to prepare SQL statements";
        }
        mysqli_stmt_close($stmt1);
        mysqli_stmt_close($stmt2);
        mysqli_stmt_close($stmt3);
    } else {
        echo "Error: Question ID not provided";
    }
} else {
    echo "Error: Invalid request method";
}
