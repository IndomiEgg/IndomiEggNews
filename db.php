<?php
// db.php - database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'indomieggnews';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
