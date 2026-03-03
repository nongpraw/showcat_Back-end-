<?php
session_start();
// 1. เชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "it67040233123";
$pass = "R0H1A9X3";
$db   = "it67040233123";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 2. ส่วนประมวลผลเมื่อมีการกดปุ่ม save
if(isset($_POST['save'])) {
    $name_th           = mysqli_real_escape_string($conn, $_POST['name_th']);
    $name_en           = mysqli_real_escape_string($conn, $_POST['name_en']);
    $description       = mysqli_real_escape_string($conn, $_POST['description']);
    $characteristics   = mysqli_real_escape_string($conn, $_POST['characteristics']);
    $care_instructions = mysqli_real_escape_string($conn, $_POST['care_instructions']);
    
    $main_image = ""; 
    $uploaded_files = array(); 

    // จัดการอัปโหลดรูปภาพ
    if(!empty($_FILES['images']['name'][0])) {
        $upload_path = "../uploads/"; // ตรวจสอบว่ามีโฟลเดอร์นี้อยู่จริง
        if (!is_dir($upload_path)) { 
            mkdir($upload_path, 0777, true); 
        }

        foreach($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] == 0) {
                $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $file_name = "cat_" . time() . "_" . $key . "." . $ext;
                
                if(move_uploaded_file($tmp_name, $upload_path . $file_name)) {
                    if($key == 0) { 
                        $main_image = $file_name; 
                    }
                    $uploaded_files[] = $file_name; 
                }
            }
        }
    }

    // บันทึกลงตาราง CatBreeds
    $sql_cat = "INSERT INTO CatBreeds (name_th, name_en, description, characteristics, care_instructions, image_url) 
                VALUES ('$name_th', '$name_en', '$description', '$characteristics', '$care_instructions', '$main_image')";
    
    if($conn->query($sql_cat)) {
        $cat_id = $conn->insert_id; 

        // บันทึกรูปภาพประกอบลงตาราง cat_images
        if(!empty($uploaded_files)) {
            foreach($uploaded_files as $file) {
                $file_safe = mysqli_real_escape_string($conn, $file);
                $conn->query("INSERT INTO cat_images (cat_id, image_url) VALUES ($cat_id, '$file_safe')");
            }
        }
        
        echo "<script>alert('เพิ่มข้อมูลแมวและรูปภาพเรียบร้อยแล้ว!'); window.location.href='index.php';</script>";
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มข้อมูลสายพันธุ์แมว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Sarabun', sans-serif; }
        .form-container { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h3 class="text-center mb-4">🐾 เพิ่มข้อมูลสายพันธุ์แมวใหม่</h3>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ชื่อสายพันธุ์ (ไทย)</label>
                            <input type="text" name="name_th" class="form-control" required placeholder="เช่น แมววิเชียรมาศ">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ชื่อสายพันธุ์ (อังกฤษ)</label>
                            <input type="text" name="name_en" class="form-control" required placeholder="เช่น Siamese">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">รายละเอียด/ประวัติ</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ลักษณะเด่น</label>
                        <textarea name="characteristics" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">การดูแลรักษา</label>
                        <textarea name="care_instructions" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">อัปโหลดรูปภาพ (เลือกได้หลายรูป - รูปแรกจะเป็นรูปหลัก)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                        <div class="form-text text-muted small">* คุณสามารถเลือกได้หลายไฟล์พร้อมกันโดยกด Ctrl ค้างไว้</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="save" class="btn btn-primary btn-lg">บันทึกข้อมูล</button>
                        <a href="index.php" class="btn btn-outline-secondary">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>