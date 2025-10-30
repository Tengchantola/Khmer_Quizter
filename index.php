<?php
session_start();
if (isset($_SESSION['Username'])) {
   header("location:home.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="./assets/Khmer_Quizter.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="styles/index.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Khmer Quizter</title>
    <style>
        body {
            background-image: url("./assets/Background.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    <div class="notify">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-8 text-center noticontent">
                    <div class="notititle">
                        <h4 class='text-start'>Login Failed!</h4>
                        <i class="bi bi-x-square-fill"></i>
                    </div>
                    <hr>
                    <div class='reason text-start px-4'>
                        <p class='reasontext'>asd</p>
                    </div>
                    <br>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
    <div class="black"></div>
    <div class="container main">
        <div class="row">
            <div class="col-6"></div>
            <div class="col-xxl-6 col-sm-12 buttons josefin-sans mt-3 d-flex">
                <div class="col-xxl-4 text-center"><button class="login gotologin active">Login</button></div>
                <div class="col-xxl-4 text-center"><button class="register">Register</button></div>
                <div class="col-xxl-4 text-center"><button class="about">About Us</button></div>
            </div>
        </div>
        <div class="logo mt-5 rounded">
            <img src="./assets/Khmer_Quizter.png" alt="Khmer Quizter Logo">
        </div>
        <div class="row">
            <div class="formlogin">
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <div class="username josefin-sans">
                            <input type="text" placeholder="Email" require id="EmailLogin" name="EmailLogin">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="invalided-email-feedback invalid-message fw-bold mt-1 mb-0">Email is empty</div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <div class="username josefin-sans">
                            <input type="password" placeholder="Password" require id="passwordLogin" name="passwordLogin">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div class="invalided-password-feedback invalid-message fw-bold mt-1 mb-0">Password is empty</div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12 text-center josefin-sans">
                        <button id="loginBtn" class="login">Login</button>
                    </div>
                    <div class="col-12 text-center josefin-sans mt-3">
                        <input type="button" value="Create New Account" id="create">
                    </div>
                    <div class="col-12 text-center josefin-sans">
                        <button class="guest">Continue as guest</button>
                    </div>
                </div>
            </div>
            <div class="formregis">
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <div class="username josefin-sans">
                            <input type="text" placeholder="Username" require id="usernameRegis">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="invalided-regis-username-feedback invalid-message fw-bold mt-1 mb-0">Email is empty</div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <div class="username josefin-sans">
                            <input type="text" placeholder="Email" require id="EmailRegis">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="invalided-regis-email-feedback invalid-message fw-bold mt-1 mb-0">Email is empty</div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <div class="username josefin-sans">
                            <input type="password" placeholder="Password" require id="passwordRegis">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div class="invalided-regis-password-feedback invalid-message fw-bold mt-1 mb-0">Email is empty</div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <div class="username josefin-sans">
                            <input type="password" placeholder="Confirm Password" require id="confirmRegis">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div class="invalided-regis-cfpassword-feedback invalid-message fw-bold mt-1 mb-0">Email is empty</div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 text-center josefin-sans">
                        <button class="regis" id="Register">Sign up</button>
                    </div>
                </div>
            </div>
            <div class="josefin-sans aboutus">
                <div class="row mt-5">
                    <!-- First row with 3 team members -->
                    <div class="col-lg-4 col-md-6 dev-image text-center">
                        <img src="assets/Teng_Chantola.png" alt="Teng Chantola">
                        <h1 class="mt-3">Teng Chantola</h1>
                        <h2>Scrum Master & Full-Stack Developer</h2>
                    </div>

                    <div class="col-lg-4 col-md-6 dev-image text-center">
                        <img src="assets/Oeurn_Channy.png" alt="Oeurn Channy">
                        <h1 class="mt-3">Oeurn Channy</h1>
                        <h2>UI/UX Designer</h2>
                    </div>

                    <div class="col-lg-4 col-md-6 dev-image text-center">
                        <img src="assets/Ret_Dara.png" alt="Ret Dara">
                        <h1 class="mt-3">Ret Dara</h1>
                        <h2>Backend Developer</h2>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Second row with 3 team members -->
                    <div class="col-lg-4 col-md-6 dev-image text-center">
                        <img src="assets/Hor_Sokna.png" alt="Hor Sokna">
                        <h1 class="mt-3">Hor Sokna</h1>
                        <h2>Product Owner</h2>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 dev-image text-center">
                        <img src="assets/Eng_Risa.png" alt="Eng Risa">
                        <h1 class="mt-3">Eng Risa</h1>
                        <h2>QA Tester</h2>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 dev-image text-center">
                        <img src="assets/Noy_Channy.png" alt="Noy Channy">
                        <h1 class="mt-3">Noy Channy</h1>
                        <h2>DevOps Engineer</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="snowflakes" aria-hidden="true">
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
        <div class="snowflake">
            <i class="bi bi-snow2"></i>
        </div>
    </div>
</body>
<script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="javascripts/index.js"></script>
</html>