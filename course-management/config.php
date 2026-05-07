<?php
// ================================================================
// CONFIG - Database connection
// ================================================================
$host = 'localhost';
$db   = 'course_db';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
?>