<?php
include_once "database/database.php";
session_start();
if (!isset($_SESSION['Username'])) {
    $_SESSION['Username'] = "Guest";
    $_SESSION['Email'] = "guest";
    $_SESSION['Role'] = 'Guest';
    $_SESSION['Profile'] = "https://media.valorant-api.com/agents/22697a3d-45bf-8dd7-4fec-84a9e28c69d7/displayicon.png";
}
if (isset($_SESSION['Role']) === "Admin") {
    header("Location: record.php");
    exit;
}
$currentPage = 'home.php';
include_once 'nav.php';
if (isset($_GET['quizid'])) {
    $quizid = $_GET['quizid'];
} else {
    header("Location: home.php");
    exit;
}
?>
<link rel="stylesheet" href="styles/play.css">
<div class="container maining josefin-sans">
    <div class="row">
        <div class="col-12 button"><button type='button'><i class="bi bi-backspace-fill backk"></i> Leave</button></div>
        <div class="col-12 text-center mainn">
            <?php
             $getquiz = "SELECT Quiz.*, UserAccount.Username, UserAccount.UserID 
                       FROM Quiz 
                       JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID 
                       WHERE QuizID = '$quizid'";
            $result = mysqli_query($conn, $getquiz);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $QuizTitle = $row['QuizTitle'];
                    $Creator = $row['Username'];
                    $Image = $row['Image'];
                    $play = isset($row['Play']) ? $row['Play'] : 0;
                    $getquestionamount = "SELECT QuestionID from QuizQuestion where QuizID = '$quizid'";
                    $resultt = mysqli_query($conn, $getquestionamount);
                    if ($resultt) {
                        $numRows = mysqli_num_rows($resultt);
                    }
            ?>
                    <div class="image-container">
                        <img src="<?php echo $Image; ?>">
                    </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-3">

                </div>
                <div class="col-xxl-2 col-12 quiztitle">
                    <h1><?php echo $QuizTitle ?></h1>
                </div>
                <div class="col-3">

                </div>
                <div class="col-xxl-2 col-12 plays">
                    <h1>
                        <?php 
                            echo $play . ' ' . ($play == 1 ? 'Play' : 'Plays'); 
                        ?>
                    </h1>
                </div>

                <div class="col-2">

                </div>
            </div>
            <div class="row">
                <div class="col-3">

                </div>
                <div class="col-xxl-2 col-12 creator">
                    <h1>By <?php echo $Creator ?></h1>
                </div>
                <div class="col-3">

                </div>
                <div class="col-3">

                </div>
                <div class="col-1">

                </div>
            </div>
            <div class="row">
                <div class="col-3">

                </div>
                <div class="col-xxl-2 col-12 ques">
                    <h1>
                        <?php 
                            echo $numRows . ' ' . ($numRows == 1 ? 'Question' : 'Questions'); 
                        ?>
                    </h1>
                </div>

                <div class="col-3">

                </div>
                <div class="col-3">

                </div>
                <div class="col-1">

                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center start">
                    <button type='button ' id="startButton"> <i class="bi bi-play-fill"></i> Start</button>
                </div>
            </div>
    <?php
        } else {
            echo "not_exists";
        }
        }
    ?>
        </div>
    </div>
</div>
<div class="container showing josefin-sans">
    <div class="row">
        <div class="col-12 button"><button type='button'><i class="bi bi-backspace-fill"></i> Leave</button></div>
        <div class="col-12 mainn">
            <div class="image-container image2">
                <img src="<?php echo $Image; ?>">
            </div>
            <h1><?php echo $QuizTitle ?></h1>
        </div>
        <div class="col-12 text-center">
            <h3 id="questionNumber"></h3>
        </div>
        <div class="col-12 timer text-center">

        </div>
        <div class="col-12 text-center  ">
            <h1 id="questionContainer"></h1>
        </div>
    </div>
</div>

<div class="congrats container josefin-sans mt-4">
    <div class="row">
        <div class="col-12 button"><button type='button'><i class="bi bi-backspace-fill"></i> Leave</button></div>
        <div class="col-12 mainn main2 text-center">
            <div class="image-container main3">
                <img src="<?php echo $Image; ?>">
            </div>
            <h1 class="mt-3"><?php echo $QuizTitle ?></h1>
        </div>
        <div class="col-12 text-center congratss">
            <h1>Congratulation you completed the quiz!</h1>
        </div>
        <div class="col-12 text-center congratss">
            <h1 class='yourscore'></h1>
        </div>
        <div class="col-12 text-center congratss">
            <a type="button" class="view" href="leaderboard.php?scorequiz=<?php echo $quizid; ?>"><i class="fa-solid fa-chart-simple"></i> View Leaderboard</a>
        </div>
    </div>
</div>

<script>
    var quizid = '<?php echo $quizid; ?>';
    var userID = '<?php echo isset($_SESSION['UserID']) ? $_SESSION['UserID'] : ''; ?>';
</script>
<script src="javascripts/play.js"></script>