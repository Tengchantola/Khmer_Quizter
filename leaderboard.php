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
    COUNT(q.Play) AS PlayCount,
    MAX(ut.ScoreValue) AS MaxScore, 
    MAX(ut.Date) AS Date 
    FROM Quiz q 
    LEFT JOIN Score ut ON q.QuizID = ut.QuizID 
    LEFT JOIN UserAccount u ON u.UserID = ut.UserID WHERE q.QuizID = '$QuizID' 
    GROUP BY u.UserID, u.Username ORDER BY MaxScore DESC, PlayCount ASC";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result); 
        if ($row && $row['Username'] !== null) { 
?>
            <link rel="stylesheet" href="styles/leaderboard.css">
            <!-- Add Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <!-- Add Google Fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            
            <div class="container-fluid leaderboard-container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-8">
                        <!-- Header Section -->
                        <div class="leaderboard-header">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <button type='button' class="btn leave-btn">
                                    <i class="bi bi-arrow-left-circle me-2"></i> Leave
                                </button>
                                <h2 class="leaderboard-title mb-0">Quiz Leaderboard</h2>
                                <div class="placeholder"></div> <!-- For alignment -->
                            </div>
                            
                            <!-- Quiz Info Card -->
                            <div class="quiz-info-card mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center">
                                        <div class="quiz-image-container">
                                            <img src="<?php echo $row['Image'] ?>" class="quiz-image" alt="Quiz Image">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <h1 class="quiz-title"><?php echo $row['QuizTitle'] ?></h1>
                                        <div class="quiz-stats d-flex flex-wrap">
                                            <div class="stat-item me-4">
                                                <span class="stat-number"><?php echo mysqli_num_rows($result); ?></span>
                                                <span class="stat-label">Players</span>
                                            </div>
                                            <?php
                                            $getquestionamount = "SELECT COUNT(*) as total FROM QuizQuestion WHERE QuizID = '$QuizID'";
                                            $resultt = mysqli_query($conn, $getquestionamount);
                                            if ($resultt) {
                                                $questionRow = mysqli_fetch_assoc($resultt);
                                                $numRows = $questionRow['total'];
                                            ?>
                                            <div class="stat-item me-4">
                                                <span class="stat-number"><?php echo $numRows; ?></span>
                                                <span class="stat-label">Questions</span>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Leaderboard Table -->
                        <div class="leaderboard-table-container">
                            <div class="table-responsive">
                                <table class="table table-hover leaderboard-table">
                                    <thead class="leaderboard-header-bg">
                                        <tr>
                                            <th scope="col" class="rank-col">Rank</th>
                                            <th scope="col" class="player-col">Player</th>
                                            <th scope="col" class="attempts-col">Attempts</th>
                                            <th scope="col" class="score-col">Score</th>
                                            <th scope="col" class="date-col">Last Attempt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rank = 1;
                                        mysqli_data_seek($result, 0);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            if ($row['Username'] === null) continue;
                                            $rowClass = "";
                                            if ($rank == 1) $rowClass = "first-place";
                                            elseif ($rank == 2) $rowClass = "second-place";
                                            elseif ($rank == 3) $rowClass = "third-place";
                                            $score = $row['MaxScore'] ?? 0;
                                            if (isset($numRows) && $score > $numRows) {
                                                $score = $numRows;
                                            }
                                        ?>
                                            <tr class="<?php echo $rowClass; ?>">
                                                <td class="rank-cell">
                                                    <div class="rank-badge">
                                                        <?php if($rank <= 3): ?>
                                                            <i class="bi bi-trophy-fill rank-icon"></i>
                                                        <?php endif; ?>
                                                        <span class="rank-number"><?php echo $rank; ?></span>
                                                    </div>
                                                </td>
                                                <td class="player-cell">
                                                    <div class="player-info">
                                                        <div class="player-avatar">
                                                            <i class="bi bi-person-circle"></i>
                                                        </div>
                                                        <div class="player-details">
                                                            <span class="player-name"><?php echo $row['Username']; ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                 <td class="attempts-cell">
                                                    <span class="attempts-count"><?php echo $row['PlayCount']; ?></span>
                                                </td>
                                                <td class="score-cell">
                                                    <div class="score-display">
                                                        <span class="score-value"><?php echo $score; ?></span>
                                                        <span class="score-divider">/</span>
                                                        <span class="score-total"><?php echo $numRows ?? '?'; ?></span>
                                                    </div>
                                                </td>
                                                <td class="date-cell">
                                                    <?php 
                                                    if ($row['Date']) {
                                                        echo '<span class="date-text">' . date('M j, Y', strtotime($row['Date'])) . '</span>';
                                                    } else {
                                                        echo '<span class="text-muted">N/A</span>';
                                                    }
                                                    ?>
                                                </td>
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
                </div>
            </div>
        <?php
        } else {
        ?>
            <link rel="stylesheet" href="styles/leaderboard.css">
            <!-- Add Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <!-- Add Google Fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            
            <div class="container-fluid leaderboard-container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <button type='button' class="btn btn-outline-secondary leave-btn">
                                <i class="bi bi-arrow-left-circle me-2"></i> Leave
                            </button>
                            <h2 class="leaderboard-title mb-0">Quiz Leaderboard</h2>
                            <div class="placeholder"></div> <!-- For alignment -->
                        </div>
                        
                        <div class="empty-state text-center py-5">
                            <?php
                            $quizQuery = "SELECT QuizTitle, Image FROM Quiz WHERE QuizID = '$QuizID'";
                            $quizResult = mysqli_query($conn, $quizQuery);
                            if ($quizResult && $quizRow = mysqli_fetch_assoc($quizResult)) {
                            ?>
                                <div class="empty-image-container mb-4">
                                    <img src="<?php echo $quizRow['Image'] ?>" class="empty-quiz-image" alt="Quiz Image">
                                </div>
                                <h1 class="empty-quiz-title mb-3"><?php echo $quizRow['QuizTitle'] ?? 'Quiz'; ?></h1>
                            <?php } ?>
                            
                            <div class="empty-content">
                                <div class="empty-icon mb-3">
                                    <i class="bi bi-bar-chart"></i>
                                </div>
                                <h3 class="empty-title">No Players Yet</h3>
                                <p class="empty-text text-muted">Be the first to attempt this quiz and claim the top spot on the leaderboard!</p>
                                <button class="btn btn-primary mt-3 take-quiz-btn">
                                    <i class="bi bi-play-circle me-2"></i> Take Quiz Now
                                </button>
                            </div>
                        </div>
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
    $(document).ready(function() {
        $('.leave-btn').click(function() {
            window.location.href = 'home.php';
        });
        
        // Add animation to table rows
        $('.leaderboard-table tbody tr').each(function(i) {
            $(this).delay(i * 100).fadeIn(300);
        });
        
        // Add click handler for take quiz button (if present)
        $('.take-quiz-btn').click(function() {
            // You can implement navigation to the quiz here
            alert('Redirect to quiz taking page');
        });
    });
</script>