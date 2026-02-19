<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../db_connect.php";

function uploadImage($file){
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // ดึงนามสกุลไฟล์
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($ext == "") {
        return null;
    }

    // ตั้งชื่อไฟล์ใหม่ (ปลอดภัย)
    $filename = time() . "_" . uniqid() . "." . strtolower($ext);

    // ✅ path ที่ถูกต้อง: uploads อยู่นอก admin
    $target = __DIR__ . "/../uploads/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return $filename;
    }

    return null;
}

$image1 = uploadImage($_FILES['image1'] ?? null);
$image2 = uploadImage($_FILES['image2'] ?? null);
$image3 = uploadImage($_FILES['image3'] ?? null);

// checkbox ถ้าไม่ติ๊ก จะไม่มีค่า
$is_visible = isset($_POST['is_visible']) ? 1 : 0;

$stmt = $conn->prepare("
    INSERT INTO cats
    (name, breed, description, image1, image2, image3, is_visible)
    VALUES (?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "ssssssi",
    $_POST['name'],
    $_POST['breed'],
    $_POST['description'],
    $image1,
    $image2,
    $image3,
    $is_visible
);

$stmt->execute();

header("Location: admin_index.php");
exit;
