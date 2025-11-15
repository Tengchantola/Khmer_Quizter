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
$currentPage = "record.php";
include_once "dashboard.php";

function getTotalPlaysByCategory($conn, $category)
{
    $getPlaysQuery = "SELECT 
                            SUM(Quiz.Play) AS total_plays
                        FROM 
                            Quiz 
                            JOIN UserAccount ON Quiz.CreatorID = UserAccount.UserID 
                        WHERE 
                            Quiz.type = '$category'";
    $result = mysqli_query($conn, $getPlaysQuery);
    if ($result) {
        $row = $result->fetch_assoc();
        $totalPlays = $row['total_plays'];
        echo $totalPlays;
    } else {
        echo 0;
    }
}






?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .boxes {
        border-radius: 20px;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        min-height: 16vh;
        transition: 0.3s;
    }

    .boxes:hover {
        cursor: pointer;
        transform: translateY(-10px);
        transition: 0.3s;
        box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
    }

    .usericon {
        font-size: 30px;
        padding: 10%;


    }

    .usericon i {
        background-color: white;
        border-radius: 30px;
        padding: 10px;
        width: 50px;
        height: 50px;
    }

    .ahsor-success {
        color: #5cdc46;

    }

    .ahsor-danger {
        color: #Bd0003;
    }

    .background-primary {
        background: #EB3349;
        background: -webkit-linear-gradient(to right, #F45C43, #EB3349);
        background: linear-gradient(to right, #F45C43, #EB3349);
    }

    .background-secondary {
        background: #4776E6;
        background: -webkit-linear-gradient(to right, #8E54E9, #4776E6);
        background: linear-gradient(to right, #8E54E9, #4776E6);
    }

    .background-success {
        background: #7F00FF;
        background: -webkit-linear-gradient(to right, #E100FF, #7F00FF);
        background: linear-gradient(to right, #E100FF, #7F00FF);
    }

    .background-warning {
        background: #F3904F;
        background: -webkit-linear-gradient(to right, #3B4371, #F3904F);
        background: linear-gradient(to right, #3B4371, #F3904F);
    }
</style>

<div class="container mt-5 ">
    <div class="row mt-3">
        <div class="col-12 josefin-sans">
            <h1>DashBoard</h1>
        </div>
    </div>
    <!-- New User -->
    <div class="row mt-2 josefin-sans" id="dashboard1">
        <div class="col-xxl-3 col-md-6 col-12 p-3 child">
            <div class="background-primary boxes">
                <div class="row">
                    <div class="col-4 usericon p-5">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="col-8 align-items-center p-3">
                        <h4 class="text-light text-start m-0">Total New Users</h4>
                        <h6 class="text-light">Last 28 Days</h6>
                        <div class="d-flex align-items-end">
                            <h2 class="text-light">
                                <?php
                                $gettotaluser = "SELECT COUNT(*) as totaluser
                            FROM useraccount
                            WHERE CreateData >= DATE_SUB(CURRENT_DATE(), INTERVAL 28 DAY)
                              AND CreateData <= CURRENT_DATE() AND Role != 'Admin' AND Role != 'Guest'";
                                $resultuser = mysqli_query($conn, $gettotaluser);
                                if ($resultuser) {
                                    $row = $resultuser->fetch_assoc();
                                    $userCount = $row['totaluser'];
                                    echo $userCount;
                                }
                                ?>

                            </h2>

                            <?php
                            $getlastuser = "SELECT COUNT(*) as lastuser
                               FROM useraccount
                               WHERE CreateData >= DATE_SUB(CURRENT_DATE(), INTERVAL 56 DAY)
                               AND CreateData < DATE_SUB(CURRENT_DATE(), INTERVAL 28 DAY)
                               AND Role != 'Admin' AND Role != 'Guest'";

                            $getlastuser = mysqli_query($conn, $getlastuser);
                            if ($getlastuser) {
                                $row = $getlastuser->fetch_assoc();
                                $lastuserCount = $row['lastuser'];
                            }
                            ?>
                            <?php
                            $newpercent = (($userCount - $lastuserCount) / $userCount) * 100;
                            if ($newpercent < 0) {
                            ?>
                                <h4 class="ms-2 ahsor-danger">
                                    <?php echo "(" . $newpercent . "%)"; ?>
                                </h4>
                            <?php
                            } else {
                            ?>
                                <h4 class="ms-2 ahsor-success">
                                    <?php echo "(+" . $newpercent . "%)"; ?>
                                </h4>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- New Quiz -->
        <div class="col-xxl-3 col-md-6 col-12 p-3 child">
            <div class="background-secondary boxes">
                <div class="row">
                    <div class="col-4 usericon p-5">
                        <i class="bi bi-clipboard2-pulse-fill"></i>
                    </div>
                    <div class="col-8 align-items-center p-3">
                        <h5 class="text-light text-start m-0">Total New Quizes</h5>
                        <h6 class="text-light">Last 28 Days</h6>
                        <div class="d-flex align-items-end">
                            <h2 class="text-light">
                                <?php
                                $gettotalquiz = "SELECT COUNT(*) as totalquiz
                            FROM quiz
                            WHERE CreateData >= DATE_SUB(CURRENT_DATE(), INTERVAL 28 DAY)
                              AND CreateData <= CURRENT_DATE() ";
                                $resultquiz = mysqli_query($conn, $gettotalquiz);
                                if ($resultquiz) {
                                    $row = $resultquiz->fetch_assoc();
                                    $quizCount = $row['totalquiz'];
                                    echo $quizCount;
                                }
                                ?>

                            </h2>
                            <?php
                            $getlastquiz = "SELECT COUNT(*) as lastquiz
                               FROM quiz
                               WHERE CreateData >= DATE_SUB(CURRENT_DATE(), INTERVAL 56 DAY)
                               AND CreateData < DATE_SUB(CURRENT_DATE(), INTERVAL 28 DAY)
                               ";

                            $getlastquiz = mysqli_query($conn, $getlastquiz);
                            if ($getlastquiz) {
                                $row = $getlastquiz->fetch_assoc();
                                $lastquizCount = $row['lastquiz'];
                            }
                            ?>
                            <?php
                            $newpercent = (($quizCount - $lastquizCount) / $quizCount) * 100;
                            if ($newpercent < 0) {
                            ?>
                                <h4 class="ms-2 ahsor-danger">
                                    <?php echo "(" . $newpercent . "%)"; ?>
                                </h4>
                            <?php
                            } else {
                            ?>
                                <h4 class="ms-2 ahsor-success">
                                    <?php echo "(+" . $newpercent . "%)"; ?>
                                </h4>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Quiz Played -->
        <div class="col-xxl-3 col-md-6 col-12 p-3 child">
            <div class="background-success boxes">
                <div class="row">
                    <div class="col-4 usericon p-5">
                        <i class="bi bi-play-fill"></i>
                    </div>
                    <div class="col-8 align-items-center p-3">
                        <h4 class="text-light text-start m-0">Total Plays</h4>
                        <h6 class="text-light">Daily</h6>
                        <div class="d-flex align-items-end">
                            <h2 class="text-light">
                                <?php
                                $gettotalplay = "SELECT COUNT(*) as todayplay
                                FROM score
                                WHERE Date >= CURRENT_DATE() AND Date < CURRENT_DATE() + INTERVAL 1 DAY;";
                                $resultplay = mysqli_query($conn, $gettotalplay);
                                if ($resultplay) {
                                    $row = $resultplay->fetch_assoc();
                                    $playCount = $row['todayplay'];
                                    echo $playCount;
                                }
                                ?>
                            </h2>
                            <?php
                            $getlastplay = "SELECT COUNT(*) as yesterdayplay
                            FROM score
                            WHERE Date >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)
                            AND Date < CURRENT_DATE();";
                            $getlastplayResult = mysqli_query($conn, $getlastplay);

                            if ($getlastplayResult) {
                                $row = $getlastplayResult->fetch_assoc();
                                $lastplayCount = $row['yesterdayplay'];
                            }
                            ?>
                            <?php
                            if ($lastplayCount == 0  && $playCount == 0) {
                                $newpercent = 0;
                            } else if ($lastplayCount == 0 && $playCount > 0) {
                                $newpercent = 100;
                            } else {
                                $newpercent = (($playCount - $lastplayCount) / $lastplayCount) * 100;
                            }

                            if ($newpercent <= 0) {
                            ?>
                                <h4 class="ms-2 ahsor-danger">
                                    <?php echo "(" . $newpercent . "%)"; ?>
                                </h4>
                            <?php
                            } else {
                            ?>
                                <h4 class="ms-2 ahsor-success">
                                    <?php echo "(+" . $newpercent . "%)"; ?>
                                </h4>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- New User -->
        <div class="col-xxl-3 col-md-6 col-12 p-3 child">
            <div class="background-warning boxes">
                <div class="row">
                    <div class="col-4 usericon p-5">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="col-8 align-items-center p-3">
                        <h5 class="text-light text-start m-0">Most Played Quiz</h5>
                        <?php
                        $getmostplay = "SELECT 
                    Quiz.*, 
                    UserAccount.*, 
                    COUNT(QuizQuestion.QuestionID) AS question_count 
                FROM 
                    Quiz 
                JOIN 
                    UserAccount ON Quiz.CreatorID = UserAccount.UserID 
                LEFT JOIN 
                    QuizQuestion ON Quiz.QuizID = QuizQuestion.QuizID
                GROUP BY 
                    Quiz.QuizID
                HAVING 
                    question_count > 0 AND Quiz.Play > 0
                ORDER BY 
                    Quiz.Play DESC limit 1";
                        $result = mysqli_query($conn, $getmostplay);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<h4 class='text-light m-0'>" . $row['QuizTitle'] . "</h4>";

                                echo "<h5 class='text-light'>" . $row['Play'] . " Plays</h5>";
                            }
                        } else {
                            echo "No quizzes found.";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="josefin-sans">Total Plays (by category)</h2>
        </div>
        <div class="col-12">
            <h4 class="josefin-sans">Total plays:
                <?php
                $gettotalplayss = "SELECT COUNT(*) as allplays FROM score";
                $resulttt = mysqli_query($conn, $gettotalplayss);
                if ($resulttt) {
                    $row = $resulttt->fetch_assoc();
                    echo $row['allplays'];
                }
                ?>
            </h4>
        </div>
        <div class="col-xxl-8 col-md-12 p-3">
            <canvas id="barChart"></canvas>
        </div>
        <div class="col-xxl-4 col-md-12">
            <div class="d-flex justiy-content-center align-items-center">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#dashboard1 .child').hide();
        $('#dashboard1 .child').each(function(index) {
            $(this).delay(300 * index).fadeIn(400).animate({
                marginLeft: '0'
            }, 500);
        });
    })

    var quizTypes = ['Computer Science and Skills', 'Mathematics', 'Games', 'Language', 'General Knowledge'];
    var playCounts = [
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Computer Science and Skills"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Mathematics"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Games"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Language"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "General Knowledge"); ?>
    ];
    var backgroundColors = ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99', '#c2c2f0'];
    var barCtx = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: quizTypes,
            datasets: [{
                label: "Number of plays",
                data: playCounts,
                backgroundColor: backgroundColors,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    var pieCtx = document.getElementById('pieChart').getContext('2d');
    var pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: quizTypes,
            datasets: [{
                label: 'Number of Plays',
                data: playCounts,
                backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99', '#c2c2f0']
            }]
        }
    });
</script>