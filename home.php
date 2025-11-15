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
    header("Location: record.php");
    exit;
}

$currentPage = 'home.php';
include_once 'nav.php';
?>

<link rel="stylesheet" href="styles/home.css">

<!-- Notification Toast -->
<div class="notification-toast" id="notificationToast">
    <i class="bi bi-check-circle-fill"></i>
    <span id="toastMessage">Quiz code copied!</span>
</div>

<!-- Hero Welcome Section -->
<div class="container">
    <div class="hero-welcome" style="background-image: url('<?php echo !empty($_SESSION['Profile']) ? $_SESSION['Profile'] : 'https://media.valorant-api.com/agents/707eab51-4836-f488-046a-cda6bf494859/displayicon.png'; ?>'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;">
        <div class="hero-content">
            <div class="welcome-text">
                <h1>Hello, <span class="username"><?php echo $_SESSION['Username']; ?>!</span></h1>
                <p style="font-size: 1.1rem; opacity: 0.9; margin-top: 0.5rem;">Ready to challenge your knowledge?</p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="profile-section">
                        <div class="profile-avatar">
                            <img src="<?php echo !empty($_SESSION['Profile']) ? $_SESSION['Profile'] : 'https://media.valorant-api.com/agents/707eab51-4836-f488-046a-cda6bf494859/displayicon.png'; ?>" alt="Profile">
                        </div>
                        <button class="btn-change-avatar" id="changepf">
                            <i class="bi bi-image"></i> Change Avatar
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                    <button class="btn-create-quiz">
                        <i class="bi bi-plus-circle-fill"></i>
                        Create Quiz
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Join Quiz Section -->
    <div class="join-section">
        <h3><i class="bi bi-box-arrow-in-right"></i> Join a Quiz</h3>
        <div class="join-input-group">
            <input type="text" id="quizCodeInput" placeholder="Enter quiz code...">
            <button class="btn-join" id="joinBtn">Join Now</button>
        </div>
    </div>

    <!-- Quiz Modal -->
    <div class="quiz-modal" id="quizModal">
        <div class="modal-content-wrapper">
            <div class="modal-header">
                <img src="" alt="Quiz" class="modal-image" id="modalImage">
                <button class="btn-close-modal" id="closeModal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="modal-title" id="modalTitle">Quiz Title</h2>
                <div class="quiz-author" id="modalAuthor">
                    <i class="bi bi-person-circle"></i>
                    <span>By Author</span>
                </div>
                <div class="quiz-stats">
                    <span class="stat-badge" id="modalPlays">
                        <i class="bi bi-play-circle"></i>
                        <span>0 plays</span>
                    </span>
                    <span class="stat-badge" id="modalQuestions">
                        <i class="bi bi-question-circle"></i>
                        <span>0 questions</span>
                    </span>
                </div>
                <div class="quiz-code-section">
                    <div>
                        <small style="color: var(--text-light); display: block; margin-bottom: 0.25rem;">Quiz Code</small>
                        <span class="quiz-code" id="modalQuizCode">XXXXX</span>
                    </div>
                    <button class="btn-copy" id="copyCodeBtn">
                        <i class="bi bi-clipboard"></i>
                        Copy
                    </button>
                </div>
                <div class="modal-actions">
                    <button class="btn-play" id="playBtn">
                        <i class="bi bi-play-fill"></i>
                        Play Now
                    </button>
                    <button class="btn-leaderboard" id="leaderboardBtn">
                        <i class="bi bi-trophy"></i>
                        Leaderboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quizzes Section -->
    <div class="quizes-container">
        <?php
        $displayQuery = "SELECT Quiz.*, UserAccount.*, COUNT(QuizQuestion.QuestionID) AS question_count 
        FROM Quiz 
        JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID 
        LEFT JOIN QuizQuestion ON Quiz.QuizID = QuizQuestion.QuizID
        GROUP BY Quiz.QuizID
        HAVING question_count > 0 and Quiz.Play > 0
        ORDER BY Quiz.Play DESC;";
        $result = mysqli_query($conn, $displayQuery);
        if ($result) {
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if (count($rows) > 0) {
                echo "<div class='show' id='popular'>";
                echo "<div class='section-header'>
                    <h2><i class='bi bi-fire' style='color: var(--primary-color);'></i> Popular Quizzes</h2>
                </div>
                <div class='row'>";
                $count = 0;
                foreach ($rows as $row) {
                    if ($count < 4) {
                        $QuizID = $row['QuizID'];
                        $Quiztitle = htmlspecialchars($row['QuizTitle']);
                        $Author = htmlspecialchars($row['Username']);
                        $image = htmlspecialchars($row['Image']);
                        $QuizCode = htmlspecialchars($row['QuizCode']);
                        $play = $row['Play'];
                        $getquestionamount = "SELECT COUNT(*) as question_count FROM QuizQuestion WHERE QuizID = '$QuizID'";
                        $resultt = mysqli_query($conn, $getquestionamount);
                        $numRows = 0;
                        if ($resultt) {
                            $rowt = mysqli_fetch_assoc($resultt);
                            $numRows = $rowt['question_count'];
                        }
                        
                        echo "<div class='col-lg-3 col-md-6 col-12 mt-4 mt-md-0'>
                            <div class='quiz-card' data-quizcode='$QuizCode' data-quizid='$QuizID' data-questions='$numRows' data-plays='$play' data-title='$Quiztitle' data-author='$Author' data-image='$image'>
                                <div class='quiz-card-image'>
                                    <img src='$image' alt='$Quiztitle'>
                                    <span class='quiz-badge'><i class='bi bi-play-circle'></i> $play Plays</span>
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
                    } else {
                        break;
                    }
                }
                echo "</div></div>";
            }
        }

        function displayQuizzes($conn, $type, $part) {
            $displayQuery = "SELECT Quiz.*, UserAccount.*, COUNT(QuizQuestion.QuestionID) AS question_count 
            FROM Quiz 
            JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID 
            LEFT JOIN QuizQuestion ON Quiz.QuizID = QuizQuestion.QuizID
            WHERE Type = '$type'
            GROUP BY Quiz.QuizID
            HAVING question_count > 0;";
            $result = mysqli_query($conn, $displayQuery);
            if ($result) {
                $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                shuffle($rows);
                if (count($rows) > 0) {
                    echo "<div class='show' id='$part'>";
                    echo "<div class='section-header'>
                        <h2>$type</h2>
                        <a href='category.php?Type=$type&Part=$part' class='btn-see-more'>
                            See More <i class='bi bi-arrow-right'></i>
                        </a>
                    </div>
                    <div class='row'>";
                    $count = 0;
                    foreach ($rows as $row) {
                        if ($count < 4) {
                            $QuizID = $row['QuizID'];
                            $Quiztitle = htmlspecialchars($row['QuizTitle']);
                            $Author = htmlspecialchars($row['Username']);
                            $image = htmlspecialchars($row['Image']);
                            $QuizCode = htmlspecialchars($row['QuizCode']);
                            $play = $row['Play'];
                            $getquestionamount = "SELECT COUNT(*) as question_count FROM QuizQuestion WHERE QuizID = '$QuizID'";
                            $resultt = mysqli_query($conn, $getquestionamount);
                            $numRows = 0;
                            if ($resultt) {
                                $rowt = mysqli_fetch_assoc($resultt);
                                $numRows = $rowt['question_count'];
                            }
                            
                            echo "<div class='col-lg-3 col-md-6 col-12 mt-4 mt-md-0'>
                                <div class='quiz-card' data-quizcode='$QuizCode' data-quizid='$QuizID' data-questions='$numRows' data-plays='$play' data-title='$Quiztitle' data-author='$Author' data-image='$image'>
                                    <div class='quiz-card-image'>
                                        <img src='$image' alt='$Quiztitle'>
                                        <span class='quiz-badge'><i class='bi bi-play-circle'></i> $play Plays</span>
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
                        } else {
                            break;
                        }
                    }
                    echo "</div></div>";
                }
            }
        }
        displayQuizzes($conn, "Computer Science", "Computer Science");
        displayQuizzes($conn, "Programming", 'Programming');
        displayQuizzes($conn, "Software Engineering", 'Software Engineering');
        displayQuizzes($conn, "Web Development", 'Web Development');
        displayQuizzes($conn, "Databases", 'Databases');
        displayQuizzes($conn, "Networking", 'Networking');
        displayQuizzes($conn, "Cybersecurity", 'Cybersecurity');
        displayQuizzes($conn, "Artificial Intelligence & Machine Learning", 'Artificial Intelligence & Machine Learning');
        displayQuizzes($conn, "Operating Systems", 'Operating Systems');
        displayQuizzes($conn, "Computer Hardware", 'Computer Hardware');
        displayQuizzes($conn, "Data Structures & Algorithms", 'Data Structures & Algorithms');
        displayQuizzes($conn, "Cloud Computing", 'Cloud Computing');
        ?>
    </div>
</div>
<?php include_once "footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="javascripts/home.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>

