<?php
include "database/database.php";
session_start();

if (isset($_POST["new_name"])) {
    $newName = mysqli_real_escape_string($conn, $_POST['new_name']);
    $sql = "UPDATE UserAccount SET Username = '$newName' WHERE UserID = '{$_SESSION['UserID']}'";
    if ($conn->query($sql) === TRUE) {
        echo "Name updated successfully $newName";
        $_SESSION['Username'] = $newName;
    } else {
        echo "Error updating name: " . $conn->error;
    }
    $conn->close();
} else {
    echo "New name is not provided";
}
