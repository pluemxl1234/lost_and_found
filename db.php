<?php
$host = 'localhost';
$user = 's68160406';
$pass = 'XEwKvWsv';
$dbname = 's68160406';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>