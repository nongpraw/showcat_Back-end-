<?php
session_start();
include '../config/db.php'; // ตรวจสอบ path ให้ถูกนะครับ

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = intval($_GET['status']);
    
    $sql = "UPDATE CatBreeds SET is_visible = $status WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: index.php"); // ทำเสร็จแล้วกลับไปหน้าหลัก
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>