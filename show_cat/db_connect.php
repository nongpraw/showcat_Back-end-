<?php
$host = "localhost";
$user = "it67040233123";
$pass = "R0H1A9X3";
$dbname = "it67040233123";

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลไม่ได้: " . $conn->connect_error);
}
?>
