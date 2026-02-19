<?php
// 1. ตรวจสอบการเชื่อมต่อ (เช็ค Path ให้ถูกต้องเหมือนไฟล์ edit.php)
if (file_exists("db_connect.php")) {
    include "db_connect.php";
} elseif (file_exists("../db_connect.php")) {
    include "../db_connect.php";
} else {
    die("Error: ไม่พบไฟล์ db_connect.php");
}

// 2. รับค่า ID และป้องกันการใส่ค่าว่างหรือตัวอักษร
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // 3. แก้ไขชื่อตารางเป็น cats (มีตัว s)
    // ใช้ Prepared Statement เพื่อความปลอดภัยสูงสุด
    $stmt = $conn->prepare("DELETE FROM cats WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // ลบสำเร็จ กลับไปหน้าหลักพร้อมส่งสถานะ success
        header("Location: admin_index.php?msg=deleted");
    } else {
        echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "ID ไม่ถูกต้อง";
}

$conn->close();
?>