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
        $totalPlays = $row['total_plays'] ?: 0;
        echo $totalPlays;
    } else {
        echo 0;
    }
}

function calculatePercentage($current, $previous) {
    if ($previous == 0) {
        return $current > 0 ? 100 : 0;
    }
    return (($current - $previous) / $previous) * 100;
}

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="styles/record.css">

<div class="container mt-5 ">
    <div class="row mt-3">
        <div class="col-12 josefin-sans">
            <h1>Dashboard</h1>
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
                                $gettotaluser = "SELECT COUNT(*) AS totaluser
                                FROM useraccount
                                WHERE Role NOT IN ('Admin', 'Guest')
                                AND CreateData >= DATE_SUB(CURDATE(), INTERVAL 28 DAY)";
                                $resultuser = mysqli_query($conn, $gettotaluser);
                                if ($resultuser) {
                                    $row = $resultuser->fetch_assoc();
                                    $userCount = $row['totaluser'] ?: 0;
                                    echo $userCount;
                                } else {
                                    $userCount = 0;
                                    echo 0;
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
                                $lastuserCount = $row['lastuser'] ?: 0;
                            } else {
                                $lastuserCount = 0;
                            }
                            $newpercent = calculatePercentage($userCount, $lastuserCount);
                            ?>
                            <?php if ($newpercent < 0): ?>
                                <h4 class="ms-2 ahsor-danger">
                                    (<?php echo round($newpercent, 1); ?>%)
                                </h4>
                            <?php else: ?>
                                <h4 class="ms-2 ahsor-success">
                                    (+<?php echo round($newpercent, 1); ?>%)
                                </h4>
                            <?php endif; ?>
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
                                WHERE CreateData >= DATE_SUB(CURRENT_DATE(), INTERVAL 28 DAY)";
                                $resultquiz = mysqli_query($conn, $gettotalquiz);
                                if ($resultquiz) {
                                    $row = $resultquiz->fetch_assoc();
                                    $quizCount = $row['totalquiz'] ?: 0;
                                    echo $quizCount;
                                } else {
                                    $quizCount = 0;
                                    echo 0;
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
                                $lastquizCount = $row['lastquiz'] ?: 0;
                            } else {
                                $lastquizCount = 0;
                            }
                            
                            $newpercent = calculatePercentage($quizCount, $lastquizCount);
                            ?>
                            <?php if ($newpercent < 0): ?>
                                <h4 class="ms-2 ahsor-danger">
                                    (<?php echo round($newpercent, 1); ?>%)
                                </h4>
                            <?php else: ?>
                                <h4 class="ms-2 ahsor-success">
                                    (+<?php echo round($newpercent, 1); ?>%)
                                </h4>
                            <?php endif; ?>
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
                                $gettotalplay = "SELECT COUNT(*) AS todayplay
                                FROM score
                                WHERE DATE(Date) = CURDATE()";
                                $resultplay = mysqli_query($conn, $gettotalplay);
                                if ($resultplay) {
                                    $row = $resultplay->fetch_assoc();
                                    $playCount = $row['todayplay'] ?: 0;
                                    echo $playCount;
                                } else {
                                    $playCount = 0;
                                    echo 0;
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
                                $lastplayCount = $row['yesterdayplay'] ?: 0;
                            } else {
                                $lastplayCount = 0;
                            }
                            $newpercent = calculatePercentage($playCount, $lastplayCount);
                            ?>
                            <?php if ($newpercent <= 0): ?>
                                <h4 class="ms-2 ahsor-danger">
                                    (<?php echo round($newpercent, 1); ?>%)
                                </h4>
                            <?php else: ?>
                                <h4 class="ms-2 ahsor-success">
                                    (+<?php echo round($newpercent, 1); ?>%)
                                </h4>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Most Played Quiz -->
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
                            q.QuizID,
                            q.QuizTitle,
                            q.Play,
                            COUNT(qq.QuestionID) AS QuestionCount,
                            u.Username
                        FROM Quiz q
                        JOIN UserAccount u ON q.CreatorID = u.UserID
                        LEFT JOIN QuizQuestion qq ON q.QuizID = qq.QuizID
                        GROUP BY q.QuizID, q.QuizTitle, q.Play, u.Username
                        HAVING QuestionCount > 0 AND q.Play > 0
                        ORDER BY q.Play DESC
                        LIMIT 1";
                        $result = mysqli_query($conn, $getmostplay);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<h4 class='text-light m-0'>" . $row['QuizTitle'] . "</h4>";
                                echo "<h5 class='text-light'>" . $row['Play'] . " Plays</h5>";
                            }
                        } else {
                            echo "<h4 class='text-light m-0'>No quizzes yet</h4>";
                            echo "<h5 class='text-light'>0 Plays</h5>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5 mb-5">
        <div class="col-12">
            <h2 class="josefin-sans">Total Plays </h2>
        </div>
        <div class="col-12">
            <h4 class="josefin-sans">Total Plays:
                <?php
                $gettotalplayss = "SELECT COUNT(*) as allplays FROM score";
                $resulttt = mysqli_query($conn, $gettotalplayss);
                if ($resulttt) {
                    $row = $resulttt->fetch_assoc();
                    echo $row['allplays'] ?: 0;
                } else {
                    echo 0;
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
    var quizTypes = [
                    'Computer Science', 
                    'Programming', 
                    'Web Development', 
                    'Databases', 
                    'Networking', 
                    'Cybersecurity', 
                    'Artificial Intelligence & Machine Learning', 
                    'Operating Systems',
                    'Computer Hardware',
                    'Data Structures & Algorithms',
                    'Cloud Computing'
                    ];
    var playCounts = [
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Computer Science"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Programming"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Web Development"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Databases"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Networking"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Cybersecurity"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Artificial Intelligence & Machine Learning"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Operating Systems"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Computer Hardware"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Data Structures & Algorithms"); ?>,
        <?php $gamesPlayCount = getTotalPlaysByCategory($conn, "Cloud Computing"); ?>
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