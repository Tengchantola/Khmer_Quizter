<?php

session_start();
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "khmer_quizter";
$conn = "";
try {
    $conn = mysqli_connect(
        $db_server,
        $db_user,
        $db_pass,
        $db_name
    );
    if ($conn) {
    } else {
        echo "Error Connection";
    }
} catch (mysqli_sql_exception) {
    echo "Maybe the server is offline?";
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["question"]) && isset($_POST["answer1"]) && isset($_POST["answer2"])) {
        $question = mysqli_real_escape_string($conn, $_POST['question']);
        $answer1 = isset($_POST['answer1']) ? mysqli_real_escape_string($conn, $_POST['answer1']) : 'NULL';
        $answer2 = isset($_POST['answer2']) ? mysqli_real_escape_string($conn, $_POST['answer2']) : 'NULL';
        $answer3 = isset($_POST['answer3']) ? mysqli_real_escape_string($conn, $_POST['answer3']) : 'NULL';
        $answer4 = isset($_POST['answer4']) ? mysqli_real_escape_string($conn, $_POST['answer4']) : 'NULL';
        $duration =  $_POST['duration'];
        $iscorrect1 = $_POST['iscorrect1'];
        $iscorrect2 = $_POST['iscorrect2'];
        $iscorrect3 = $_POST['iscorrect3'];
        $iscorrect4 = $_POST['iscorrect4'];
        $createquestion = "INSERT INTO Question (Question,Duration) VALUES ('$question','$duration')";
        if (mysqli_query($conn, $createquestion)) {
            $QuizID = $_SESSION['QuizID'];
            $QuestionID = mysqli_insert_id($conn);
            $inserting = "INSERT INTO quizquestion (QuizID,QuestionID) VALUES ('$QuizID','$QuestionID')";
            if (mysqli_query($conn, $inserting)) {
            }
            if (!empty($answer1)) {
                $insertanswer1 = "INSERT INTO Answers (QuestionID, Answer, is_correct) VALUES ('$QuestionID', '$answer1', '$iscorrect1')";
                mysqli_query($conn, $insertanswer1);
            }

            if (!empty($answer2)) {
                $insertanswer2 = "INSERT INTO Answers (QuestionID, Answer, is_correct) VALUES ('$QuestionID', '$answer2', '$iscorrect2')";
                mysqli_query($conn, $insertanswer2);
            }

            if (!empty($answer3)) {
                $insertanswer3 = "INSERT INTO Answers (QuestionID, Answer, is_correct) VALUES ('$QuestionID', '$answer3', '$iscorrect3')";
                mysqli_query($conn, $insertanswer3);
            }

            if (!empty($answer4)) {
                $insertanswer4 = "INSERT INTO Answers (QuestionID, Answer, is_correct) VALUES ('$QuestionID', '$answer4', '$iscorrect4')";
                mysqli_query($conn, $insertanswer4);
            }
            echo "Create Success!";
            $sql = "SELECT COUNT(*) AS table_length FROM QuizQuestion WHERE QuizID = $QuizID";
            $result = $conn->query($sql);

            if ($result !== false) {
                $row = $result->fetch_assoc();
                $tableLength = (int)$row['table_length'];
                $_SESSION['questionnum'] = $tableLength + 1;
            } else {
                echo "No rows returned from the query.";
            }
        } else {
            echo "Error executing the query: " . $conn->error;
        }
    } else {
        echo "Error: ";
    }
} else {
    echo "Invalid request.";
}
