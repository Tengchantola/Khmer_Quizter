<?php
session_start();
include "database/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["userid"])) {
        $userid = $_POST["userid"];
        try {
            $trydelete = "DELETE FROM UserAccount WHERE UserID = $userid";
            $resulte = mysqli_query($conn, $trydelete);
        } catch (Exception $e) {
            // echo "Error: " . $e->getMessage();
        }
        $getquiz = "SELECT QuizID FROM Quiz Where CreatorID = $userid";
        $firstresult = mysqli_query($conn, $getquiz);
        if ($firstresult) {
            while ($userrow = mysqli_fetch_assoc($firstresult)) {
                $quizId = $userrow['QuizID'];
                $getQuestionsQuery = "SELECT QuestionID FROM QuizQuestion WHERE QuizID = '$quizId'";
                $result = mysqli_query($conn, $getQuestionsQuery);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $questionid = $row['QuestionID'];
                        $deleteanswer = "DELETE FROM Answers WHERE QuestionID = '$questionid'";
                        $results = mysqli_query($conn, $deleteanswer);
                        if ($results) {
                            $deletequestion = "DELETE FROM QuizQuestion WHERE QuizID = '$quizId'";
                            $resultss = mysqli_query($conn, $deletequestion);
                            if ($resultss) {
                                $deletequestions = "DELETE FROM Question WHERE QuestionID = '$questionid'";
                                $resultsss = mysqli_query($conn, $deletequestions);
                                if ($resultsss) {
                                    $lastdelete = "DELETE FROM Score WHERE UserID = '$userid'";
                                    $deletequiz = "DELETE FROM Quiz WHERE QuizID = '$quizId'";
                                    $finalresult = mysqli_query($conn, $lastdelete);
                                    $resultssss = mysqli_query($conn, $deletequiz);
                                    if ($resultssss && $finalresult) {
                                    } else {
                                        echo "Error: " . mysqli_error($conn);
                                        echo "meow";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $deleteuser = "DELETE FROM UserAccount Where UserID = '$userid'";
            $deluser = mysqli_query($conn, $deleteuser);
            if ($deluser) {
                // echo "Sucess";
            } else {
                echo "Error: " . mysqli_error($conn);
                echo "meow";
            }
        }
    } else {
        echo "Error: Quiz ID is missing.";
    }
} else {
    echo "Error: Invalid request method.";
}
