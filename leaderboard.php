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
            <style>
                .button button {
                    padding: 10px 30px;
                    font-size: 25px;
                    border: none;
                    border-radius: 15px;
                    font-weight: 700;
                    background-color: #CE0037;
                    color: white;
                    margin: 20px;
                    transition: 0.3s;
                    box-shadow: 0 0 0.3rem black;
                }

                .button button:hover {
                    background-color: white;
                    color: #CE0037;
                    transition: 0.3s;
                }

                .image-container {
                    max-width: 310px;
                    max-height: 180px;
                    min-height: 200px;
                    overflow: hidden;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: grey;
                    margin: auto;
                    border: #CE0037 5px solid;
                }

                .image-container img {
                    border-radius: 5px 5px 0 0;
                    width: 100%;
                    height: 100%;
                    display: block;
                }

                .container {
                    background-color: white;
                    box-shadow: 0 0 0.2rem black;
                    border-radius: 20px;
                    margin-top: 5%;
                }

                .ttitle h1 {
                    font-size: 25px;
                }

                .table {
                    max-height: 250px;
                    overflow-y: scroll;
                }
                
                .table thead th {
                    position: sticky;
                    top: 0;
                    background-color: #343a40;
                    z-index: 10;
                }
            </style>
            <div class="container">
                <div class="row">
                    <div class="col-12 button"><button type='button'><i class="bi bi-backspace-fill"></i> Leave</button></div>
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
            <style>
                .button button {
                    padding: 10px 30px;
                    font-size: 25px;
                    border: none;
                    border-radius: 15px;
                    font-weight: 700;
                    background-color: #CE0037;
                    color: white;
                    margin: 20px;
                    transition: 0.3s;
                    box-shadow: 0 0 0.3rem black;
                }

                .button button:hover {
                    background-color: white;
                    color: #CE0037;
                    transition: 0.3s;
                }

                .image-container {
                    max-width: 310px;
                    max-height: 180px;
                    min-height: 200px;
                    overflow: hidden;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: grey;
                    margin: auto;
                    border: #CE0037 5px solid;
                }

                .image-container img {
                    border-radius: 5px 5px 0 0;
                    width: 100%;
                    height: 100%;
                    display: block;
                }

                .container {
                    background-color: white;
                    box-shadow: 0 0 0.2rem black;
                    border-radius: 20px;
                    margin-top: 5%;
                }

                .ttitle h1 {
                    font-size: 25px;
                }
            </style>
            <div class="container josefin-sans">
                <div class="row">
                    <div class="col-12 button"><button type='button'><i class="bi bi-backspace-fill"></i> Leave</button></div>
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
        history.back();
    })
</script>