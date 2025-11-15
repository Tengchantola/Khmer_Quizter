<?php
include "database/database.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["current_password"]) && isset($_POST["new_password"])) {
        $currentPassword = $_POST["current_password"];
        $newPassword = $_POST["new_password"];
        $sql = "SELECT Password FROM UserAccount WHERE UserID = '{$_SESSION['UserID']}'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row["Password"];
            
            if (password_verify($currentPassword, $storedPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateSql = "UPDATE UserAccount SET Password = '$hashedPassword' WHERE UserID = '{$_SESSION['UserID']}'";
                if ($conn->query($updateSql) === TRUE) {
                    echo "Password changed successfully";
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "Current password does not match";
            }
        } else {
            echo "Error: User not found";
        }
    } else {
        echo "Error: Current password or new password not provided";
    }
} else {
    echo "Error: Invalid request method";
}
?>
