<?php
$host = "localhost";
$user = "it67040233123";
$pass = "R0H1A9X3";
$db   = "it67040233123";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
session_start();
?>