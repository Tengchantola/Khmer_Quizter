<?php
include "database/database.php";
session_start();
$currentPage = 'home.php';
include_once "nav.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $QuizID = $_GET['scorequiz'];
    $query = "SELECT 
    q.QuizTitle, 
    q.Image, 
    u.Username, 
    u.UserID, 
    COUNT(ut.AttemptID) AS PlayCount, 
    MAX(ut.Score) AS MaxScore, 
    MAX(ut.CompletedAt) AS Date 
    FROM Quiz q 
    LEFT JOIN UserQuizAttempts ut ON q.QuizID = ut.QuizID 
    LEFT JOIN UserAccount u ON u.UserID = ut.UserID WHERE q.QuizID = '$QuizID' 
    GROUP BY u.UserID, u.Username ORDER BY MaxScore DESC, PlayCount ASC;";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result); 
        if ($row && $row['Username'] !== null) { 
?>
            <link rel="stylesheet" href="styles/leaderboard.css">
            <div class="container">
                <div class="row">
                    <div class="col-12 button">
                        <button type='button'>
                            <i class="bi bi-backspace-fill"></i> Leave
                        </button>
                    </div>
                    <div class="col-12">
                        <div class="image-container">
                            <img src="<?php echo $row['Image'] ?>">
                        </div>
                    </div>
                    <div class="col-12 text-center ttitle">
                        <h1><?php echo $row['QuizTitle'] ?></h1>
                    </div>
                    <div class="col-12 table">
                        <table class="table text-center table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Rank</th>
                                    <th>Player's Name</th>
                                    <th>Score</th>
                                    <th>Attempt</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rank = 1;
                                mysqli_data_seek($result, 0);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    if ($row['Username'] === null) continue;
                                ?>
                                    <tr>
                                        <td><?php echo $rank; ?></td>
                                        <td><?php echo $row['Username']; ?></td>
                                        <td><?php
                                            $score = $row['MaxScore'] ?? 0;
                                            $getquestionamount = "SELECT COUNT(*) as total FROM QuizQuestion WHERE QuizID = '$QuizID'";
                                            $resultt = mysqli_query($conn, $getquestionamount);
                                            if ($resultt) {
                                                $questionRow = mysqli_fetch_assoc($resultt);
                                                $numRows = $questionRow['total'];
                                                if ($score > $numRows) {
                                                    $score = $numRows;
                                                }
                                            }
                                            echo $score . "/" . $numRows;
                                            ?></td>
                                        <td><?php echo $row['PlayCount']; ?></td>
                                        <td><?php 
                                            if ($row['Date']) {
                                                echo date('M j, Y', strtotime($row['Date']));
                                            } else {
                                                echo "N/A";
                                            }
                                        ?></td>
                                    </tr>
                                <?php
                                    $rank++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
        } else {
        ?>
            <link rel="stylesheet" href="styles/leaderboard.css">
            <div class="container josefin-sans">
                <div class="row">
                    <div class="col-12 button">
                        <button type='button'><i class="bi bi-backspace-fill"></i> 
                            Leave
                        </button>
                    </div>
                    <div class="col-12 text-center">
                        <div class="image-container">
                            <?php
                            $quizQuery = "SELECT QuizTitle, Image FROM Quiz WHERE QuizID = '$QuizID'";
                            $quizResult = mysqli_query($conn, $quizQuery);
                            if ($quizResult && $quizRow = mysqli_fetch_assoc($quizResult)) {
                            ?>
                                <img src="<?php echo $quizRow['Image'] ?>">
                            <?php } ?>
                        </div>
                        <h1 class="mt-3"><?php echo $quizRow['QuizTitle'] ?? 'Quiz'; ?></h1>
                        <h2 class="text-muted mt-4">No users have taken this quiz yet.</h2>
                        <p class="text-muted">Be the first to attempt this quiz!</p>
                    </div>
                </div>
            </div>
<?php
        }
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
}
?>
<script>
    $('.button').click(function() {
        // history.back();
        window.location.href = 'home.php';
    })
</script>