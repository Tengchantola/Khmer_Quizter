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
    header("Location: record.php");
    exit;
}

$currentPage = 'myquiz.php';
include_once 'nav.php';
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link href="styles/myQuiz.css" rel="stylesheet">

<!-- Quiz Detail Modal -->
<div class="modal fade" id="quizDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold quiztitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- <img src="" class="w-100 detail-image" style="max-height: 300px; object-fit: cover;" alt="Quiz Image"> -->
                <div class="p-4">
                    <!-- Author and Plays -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted">
                            <i class="bi bi-person-circle me-2"></i>
                            <span class="authorr"></span>
                        </div>
                        <span class="badge badge-plays rounded-pill px-3 py-2 plays-display">
                            <i class="bi bi-play-circle-fill me-1"></i>
                            <span></span>
                        </span>
                    </div>

                    <!-- Quiz Code -->
                    <div class="card info-card border-0 bg-light mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center py-2">
                            <div class="text-muted quizcodee">
                                <i class="bi bi-code-square me-2"></i>
                                Code: <span id="quizCode" class="fw-bold text-dark"></span>
                            </div>
                            <i class="bi bi-copy copy-icon fs-5" id="copyIcon"></i>
                        </div>
                    </div>

                    <!-- Number of Questions -->
                    <div class="card info-card border-0 bg-light mb-3">
                        <div class="card-body py-2">
                            <i class="bi bi-question-circle me-2 text-muted"></i>
                            <span class="numofques text-muted"></span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-warning flex-fill edit-btn">
                            <i class="bi bi-pencil-square me-1"></i> Edit Quiz
                        </button>
                        <button class="btn btn-danger flex-fill delete-btn">
                            <i class="bi bi-trash me-1"></i> Delete Quiz
                        </button>
                    </div>

                    <!-- Play and Leaderboard -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-danger leaderboard-btn">
                            <i class="bi bi-trophy-fill me-2"></i> View Leaderboard
                        </button>
                        <button class="btn btn-success btn-play play-btn" data-quiz="">
                            <i class="bi bi-play-fill me-2"></i> Play Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
    <div id="notificationToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="notificationMessage"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="container py-4">
    <!-- <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-danger">
            <i class="bi bi-collection-play"></i> My Quizzes
        </h1>
        <p class="lead text-muted">Manage and play your created quizzes</p>
    </div> -->

    <!-- Quiz Cards -->
    <div class="row g-4" id="quizContainer">
        <?php
        $displayquestion = "SELECT * FROM Quiz JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID WHERE UserID = {$_SESSION['UserID']}";
        $result = mysqli_query($conn, $displayquestion);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $QuizID = $row['QuizID'];
                $Quiztitle = $row['QuizTitle'];
                $QuizCode = $row['QuizCode'];
                $Author = $row['Username'];
                $image = $row['Image'];
                $play = $row['Play'];
                $getquestionamount = "SELECT QuestionID FROM QuizQuestion WHERE QuizID = '$QuizID'";
                $resultt = mysqli_query($conn, $getquestionamount);
                $numQues = 0;
                if ($resultt) {
                    $numQues = mysqli_num_rows($resultt);
                }
                echo "
                <div class='col-lg-3 col-md-4 col-sm-6 fade-in quiz-item'>
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
                        <h3 class='quiz-title fw-bold'>$Quiztitle</h3>
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
                </div>";
            }
        } else {
            echo "
            <div class='col-12'>
                <div class='text-center py-5'>
                    <i class='bi bi-inbox display-1 text-muted opacity-50'></i>
                    <h3 class='mt-4 text-muted'>No Quizzes Yet</h3>
                    <p class='text-muted'>Create your first quiz to get started!</p>
                    <a href='create.php' class='btn btn-danger mt-3'>
                        <i class='bi bi-plus-circle me-2'></i>Create Quiz
                    </a>
                </div>
            </div>";
        }
        mysqli_close($conn);
        ?>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="javascripts/myQuiz.js"></script>
<?php
    include_once "footer.php";
?>