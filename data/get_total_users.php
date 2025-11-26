<?php
include "database/database.php";

$query = "SELECT COUNT(*) AS totaluser
          FROM useraccount
          WHERE Role NOT IN ('Admin', 'Guest')
          AND CreateData >= DATE_SUB(CURDATE(), INTERVAL 28 DAY)";

$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);
echo $row['totaluser'];
?>
