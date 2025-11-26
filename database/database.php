<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "khmer_quizter";
$conn = "";

try {
    $conn = mysqli_connect(
        $db_server,
        $db_user,
        $db_pass,
        $db_name
    );
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (mysqli_sql_exception) {
    echo "Maybe the server is offline?";
}
