<?php
session_start();
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "khmer-quizter";
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Error: Connection failed. " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["QuizName"]) && isset($_POST["Quiztype"]) && isset($_FILES["image"])) {
        $QuizName = $_POST["QuizName"];
        $Quiztype = $_POST["Quiztype"];
        $file_name = $_FILES["image"]["name"];
        $file_temp = $_FILES["image"]["tmp_name"];
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                echo "Failed to create upload directory!";
                exit;
            }
        }
        if (!is_writable($upload_dir)) {
            echo "Upload directory is not writable!";
            exit;
        }
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_filename = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $upload_dir . $unique_filename;
        $check = getimagesize($file_temp);
        if ($check === false) {
            echo "File is not a valid image!";
            exit;
        }
        if ($_FILES["image"]["size"] > 5000000) {
            echo "File is too large. Maximum size is 5MB.";
            exit;
        }
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($file_extension), $allowed_types)) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        }
        function generateQuizCode($length = 6) {
            $characters = '0123456789';
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $code;
        }
        $quizCode = generateQuizCode();
        $sql = "SELECT COUNT(*) AS count FROM Quiz WHERE QuizCode = '$quizCode'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['count'] > 0) {
                $quizCode = generateQuizCode();
            }
        }
        if (move_uploaded_file($file_temp, $target_file)) {
            $createquiz = "INSERT INTO Quiz (QuizTitle, Type, CreatorID, Image, QuizCode) VALUES ('$QuizName', '$Quiztype', '{$_SESSION['UserID']}', '$target_file', '$quizCode')";
            if (mysqli_query($conn, $createquiz)) {
                $quizID = mysqli_insert_id($conn);
                $_SESSION['QuizID'] = $quizID;
                $_SESSION['quiz'] = $QuizName;
                $sql = "SELECT COUNT(*) AS table_length FROM QuizQuestion WHERE QuizID = $quizID";
                $result = mysqli_query($conn, $sql);
                if ($result !== false) {
                    $row = mysqli_fetch_assoc($result);
                    $tableLength = (int)$row['table_length'];
                    $_SESSION['questionnum'] = $tableLength + 1;
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
                echo "Create Success!";
            } else {
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Error uploading image. Please check directory permissions.";
        }
    } else {
        echo "Invalid request.";
    }
}
mysqli_close($conn);
?>