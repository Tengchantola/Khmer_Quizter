<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="./assets/Khmer_Quizter.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="styles/nav.css" rel="stylesheet">
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Khmer Quizter</title>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="./assets/Khmer_Quizter.png">
            <h3 class="josefin-sans">Khmer Quizter</h3>
        </div>
        <div class="home homepage  <?php if ($currentPage === 'home.php') echo 'onpage'; ?>">
            <i class="fa-solid fa-house"></i>
            <h3 class="josefin-sans">Home</h3>
        </div>
        <?php
            $role = $_SESSION['Role'];
            if ($role === "Guest") {
                echo "";
            } else {
                echo " <div class='home activity";
                if ($currentPage === 'activity.php') echo ' onpage';
                echo "'><i class='fa-solid fa-clock-rotate-left'></i>";
                echo "<h3 class='josefin-sans'>Activity</h3></div>";
            }
        ?>
        <div class="home findquiz  <?php if ($currentPage === 'findquiz.php') echo 'onpage'; ?>">
            <i class="fa-solid fa-magnifying-glass"></i>
            <h3 class="josefin-sans">Find Quiz</h3>
        </div>
        <?php
            if ($role === "Guest") {
                echo "";
            } else {
                echo " <div class='home myquiz ";
                if ($currentPage === 'myquiz.php') echo ' onpage';
                echo "'><i class='fa-solid fa-book'></i>";
                echo "<h3 class='josefin-sans'>My Quiz</h3></div>";
            }
        ?>
        <div class="menu">
            <div class="dropdown"><i class="bi bi-list"></i></div>
            <div class="dropdown-menu py-3 josefin-sans">
                <div class="name px-3">
                    <h1><?php
                        echo $_SESSION["Username"];
                        ?></h1>
                </div>
                <div class="email px-3">
                    <h1><?php
                        echo $_SESSION["Email"];
                        ?></h1>
                </div>
                <hr class="px-3">
                <?php
                if ($_SESSION['Role'] === "Guest") {
                } else {
                    echo " <div class='dropdown-content py-2 d-flex settingg'>
                    <i class='bi bi-person'></i>
                    <h1>Profile</h1>
                </div>";
                }
                ?>
                <div class='dropdown-content py-3 d-flex' id='logout'>
                    <?php
                    if ($_SESSION['Role'] === "Guest") {

                        echo "<i class='bi bi-person-fill-add'></i><h1>Sign in<h1>";
                    } else {
                        echo " <i class='bi bi-box-arrow-left'></i><h1>Log out</h1>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>
    <!-- <div class="pagetitle josefin-sans">
        <h1><?php if ($currentPage === 'home.php') {
                echo 'Home';
            } elseif ($currentPage === 'activity.php') {
                echo 'My Quiz';
            } elseif ($currentPage === 'findquiz.php') {
                echo 'Activity';
            } elseif ($currentPage === 'myquiz.php') {
                echo 'Find Quiz';
            };
            ?></h1>
    </div> -->
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="javascripts/nav.js"></script>
</html>