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
$currentPage = "admin.php";
include_once "dashboard.php";
?>

<link rel="stylesheet" href="styles/admin.css">

<div class="container josefin-sans">
    <div class="row" id="myTable">
        <?php
        $countuser = "SELECT COUNT(*) AS user_count FROM UserAccount where Role = 'User'";
        $getuseramount = mysqli_query($conn, $countuser);
        if ($getuseramount) {
            $rows = mysqli_fetch_assoc($getuseramount);
            $user_count = $rows['user_count'];
            echo "<div class='col-12 useramount'><h2>Total Users : $user_count</h2></div>";
        }
        ?>
        <div class="col-12 search">
            <input id="myInput" type="text" placeholder="Search..">
        </div>
        <div class="no-results p-3 ps-5">
            <h3>No results found for "<span id="searchTerm"></span>"</h3>
        </div>
        <?php
        $getuser = "SELECT * FROM UserAccount where Role != 'Admin' and Role !='Guest'";
        $result = mysqli_query($conn, $getuser);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $userid = $row['UserID'];
                $countquiz = "SELECT COUNT(*) AS quiz_count FROM Quiz WHERE CreatorID = $userid";
                $getquizamount = mysqli_query($conn, $countquiz);
                if ($getquizamount) {
                    $rows = mysqli_fetch_assoc($getquizamount);
                    $quiz_count = $rows['quiz_count'];
                }
                $username = $row['Username'];
                $profile = $row['Profile'];
                $email = $row['Email'];
                $status = $row['Status'];
                echo "<div class='col-xxl-3 col-lg-4 col-md-6 col-12 text-center meow'><div class='users'>
                <img src='$profile'>
                <h2 class='name'>$username</h2>
                <h2 class='totalquiz'>$email</h2>
                <h2 class='totalquiz'>Quiz created: $quiz_count</h2>
                <input type='text' style='display:none;' name='userid' value='$userid'>
                 <button class='diable $status disableButton'>Disable User</button><button class='delete'>Delete User</button>
                </div>
                </div>";
            }
        }
        if ($user_count <= 0) {
            echo "<div class='col-12 p-3'><h3>No user found !</h3></div>";
        }
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="javascripts/admin.js"></script>