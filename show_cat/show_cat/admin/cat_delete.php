<?php
include '../config/db.php';
if(!isset($_SESSION['admin_id'])) header("Location: ../login.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. ดึงชื่อไฟล์รูปภาพทั้งหมดที่เกี่ยวข้องมาลบทิ้งจากโฟลเดอร์
    $res_images = $conn->query("SELECT image_url FROM cat_images WHERE cat_id = $id");
    while($img = $res_images->fetch_assoc()) {
        $file_path = "../uploads/" . $img['image_url'];
        if(file_exists($file_path)) {
            unlink($file_path); // ลบไฟล์จริง
        }
    }

    // 2. ลบข้อมูลในตาราง cat_images และ CatBreeds
    $conn->query("DELETE FROM cat_images WHERE cat_id = $id");
    $conn->query("DELETE FROM CatBreeds WHERE id = $id");

    header("Location: index.php");
}
?>