<?php
include_once "database/database.php";

if (isset($_GET['quizid'])) {
    $quizid = mysqli_real_escape_string($conn, $_GET['quizid']);
    $query = "SELECT * FROM QuizQuestion Join Question on QuizQuestion.QuestionID= Question.QuestionID join Answers on Answers.QuestionID = Question.QuestionID WHERE quizid = '$quizid'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $questions = array();
        while ($row = mysqli_fetch_assoc($result)) {
            if (!isset($questions[$row['QuestionID']])) {
                $questions[$row['QuestionID']] = array(
                    'question_id' => $row['QuestionID'],
                    'question_text' => $row['Question'],
                    'duration'=> $row['Duration'],
                    'answers' => array()
                );
            }
            $questions[$row['QuestionID']]['answers'][] = array(
                'answer_text' => $row['Answer'],
                'iscorrect' => $row['is_correct']
            );
        }
        echo json_encode(array_values($questions));
    } else {
        echo json_encode(array('error' => 'Failed to fetch questions'));
    }
} else {
    echo json_encode(array('error' => 'Quiz ID parameter is missing'));
}

mysqli_close($conn);
