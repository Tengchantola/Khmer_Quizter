<?php
include "database/database.php";
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit;
}
if ($_SESSION['Role'] !== "Admin") {
    header("Location: index.php");
    exit;
}
$currentPage = "quizes.php";
include_once "dashboard.php";
$getquizamount = "SELECT COUNT(*) AS quiz_count FROM Quiz ";
$result = mysqli_query($conn, $getquizamount);
if ($result) {
    $rows = mysqli_fetch_assoc($result);
    $quiz_count = $rows['quiz_count'];
}
?>
<div class="container josefin-sans">
    <div class="row">
        <div class="col-12 pt-5">
            <h2>Total Quiz : <?php echo $quiz_count; ?></h2>
        </div>
    </div>
</div>
<link rel="stylesheet" href="styles/quizes.css">
<div id="notificationModal" class="modals">
    <div class="modal-contents">
        <span class="close">&times;</span>
        <p id="notificationMessage"></p>
    </div>
</div>

<div id="toast" class="custom-toast">
    <i class="bi bi-check-circle-fill"></i>
    <span id="toastMessage">Copied!</span>
</div>

<div class="detail">
    <div class='col-xxl-4 col-lg-4 col-md-6 col-12 text-center'>
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
                <div class="col-12 d-flex rawr">
                    <div class='text-start quizcodee josefin-sans'>QuizCode: $QuizCode</div>
                    <h6 class='authorname edit'>Edit Quiz</h6>
                </div>
                <div class="col-12 d-flex rawr mt-2">
                    <div class='text-start numofques josefin-sans'></div>
                    <h6 class='authorname delques'>Delete Quiz</h6>
                </div>
                <br><br>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12 search">
            <input id="myInput" type="text" placeholder="Search..">
        </div>
    </div>
</div>
<div class="container mb-5">
    <div class="no-results p-3">
        <h3>No results found for "<span id="searchTerm"></span>"</h3>
    </div>
    <div class="row" id="myTable">
        <?php
        $displayquestion = "SELECT * FROM Quiz JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID ";
        $result = mysqli_query($conn, $displayquestion);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $QuizID = $row['QuizID'];
                $Quiztitle = $row['QuizTitle'];
                $QuizCode = $row['QuizCode'];
                $Author = $row['Username'];
                $image = $row['Image'];
                $play = $row['Play'];
                $Type = $row["Type"];
                $getquestionamount = "SELECT QuestionID from QuizQuestion where QuizID = '$QuizID'";
                $resultt = mysqli_query($conn, $getquestionamount);
                if ($resultt) {
                    $numQues = mysqli_num_rows($resultt);
                }
                $label = ($numQues <= 1) ? "Question" : "Questions";
                echo "<div class='col-xxl-3 col-lg-4 col-md-6 mt-3 text-center'>
                <div class='quiz-card carde'
                    data-quizcode='$QuizCode'
                    data-quizid='$QuizID'
                    data-questions='$numQues'
                    data-plays='$play'
                    data-title='$Quiztitle'
                    data-author='$Author'
                    data-image='$image'
                    data-type='$Type'>
                    <input type='text' style='display:none' value='$QuizCode'> 
                    <input type='text' style='display:none' value='$QuizID'> 
                    <input type='text' style='display:none' value='$numQues'> 
                    <input type='text' style='display:none' value='$play'>
                    <div class='quiz-card-image'>
                        <img src='$image' alt='$Quiztitle'>
                        <span class='quiz-badge'>
                            <i class='bi bi-play-circle'></i> $play Plays
                        </span>
                    </div>
                    <div class='quiz-card-body'>
                        <h3 class='quiz-title quiztitles'>$Quiztitle</h3>
                        <div class='quiz-author mrauthor'>
                            <i class='bi bi-person-circle'></i>
                            <span>By $Author</span>
                        </div>
                        <div class='quiz-category'>
                            <i class='bi bi-tags'></i>
                            <span>Category: $Type</span>
                        </div>
                        <div class='quiz-stats'>
                            <span class='stat-badge'>
                                <i class='bi bi-question-circle'></i>
                                $numQues {$label}
                            </span>
                        </div>
                    </div>
                </div>
            </div>";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_close($conn);
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="javascripts/quizes.js"></script>