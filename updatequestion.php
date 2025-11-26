<?php
session_start();
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "khmer_quizter";
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["question"]) && isset($_POST["answer1"]) && isset($_POST["answer2"])) {
        $question = mysqli_real_escape_string($conn, $_POST['question']);
        $quesid = mysqli_real_escape_string($conn, $_POST['quesid']);
        $duration = $_POST['duration'];
        $update_question_query = "UPDATE Question SET Question = '$question', Duration = '$duration' WHERE QuestionID = '$quesid'";
        if (mysqli_query($conn, $update_question_query)) {
            $answers = array(
                1 => array('id' => $_POST['answerid1'], 'text' => $_POST['answer1'], 'correct' => $_POST['iscorrect1']),
                2 => array('id' => $_POST['answerid2'], 'text' => $_POST['answer2'], 'correct' => $_POST['iscorrect2']),
                3 => array('id' => $_POST['answerid3'], 'text' => $_POST['answer3'], 'correct' => $_POST['iscorrect3']),
                4 => array('id' => $_POST['answerid4'], 'text' => $_POST['answer4'], 'correct' => $_POST['iscorrect4'])
            );
            foreach ($answers as $key => $answer) {
                if (!empty($answer['text'])) {
                    $answer_id = mysqli_real_escape_string($conn, $answer['id']);
                    $answer_text = mysqli_real_escape_string($conn, $answer['text']);
                    $is_correct = mysqli_real_escape_string($conn, $answer['correct']);
                    $update_answer_query = "UPDATE Answers SET Answer = '$answer_text', is_correct = '$is_correct' WHERE AnswerID = '$answer_id'";
                    mysqli_query($conn, $update_answer_query);
                }
            }
            echo "Update Success!";
        } else {
            echo "Error executing the query: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Incomplete data.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
