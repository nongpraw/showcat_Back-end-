<?php
session_start();

// 1. เชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "it67040233123"; 
$pass = "R0H1A9X3"; 
$db   = "it67040233123"; 

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

// --- ส่วนใหม่: ระบบลบรูปภาพประกอบ ---
if (isset($_GET['delete_img'])) {
    $img_id = intval($_GET['delete_img']);
    // ดึงชื่อไฟล์มาก่อนลบ
    $res = $conn->query("SELECT image_url FROM cat_images WHERE id = $img_id");
    if ($img_data = $res->fetch_assoc()) {
        $file_path = "../uploads/" . $img_data['image_url'];
        if (file_exists($file_path)) { unlink($file_path); } // ลบไฟล์จริง
        $conn->query("DELETE FROM cat_images WHERE id = $img_id"); // ลบใน DB
    }
    echo "success"; exit; // ตอบกลับ AJAX
}

// 2. ส่วนประมวลผลการอัปเดตข้อมูล
if(isset($_POST['update'])) {
    $name_th           = mysqli_real_escape_string($conn, $_POST['name_th']);
    $name_en           = mysqli_real_escape_string($conn, $_POST['name_en']);
    $description       = mysqli_real_escape_string($conn, $_POST['description']);
    $characteristics   = mysqli_real_escape_string($conn, $_POST['characteristics']);
    $care_instructions = mysqli_real_escape_string($conn, $_POST['care_instructions']);
    
    $sql_update = "UPDATE CatBreeds SET name_th=?, name_en=?, description=?, characteristics=?, care_instructions=? WHERE id=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssssi", $name_th, $name_en, $description, $characteristics, $care_instructions, $id);
    
    if($stmt->execute()) {
        if(!empty($_FILES['images']['name'][0])) {
            $upload_path = "../uploads/";
            foreach($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] == 0) {
                    $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                    $filename = "cat_" . time() . "_" . $key . "." . $ext;
                    if(move_uploaded_file($tmp_name, $upload_path . $filename)) {
                        $conn->query("INSERT INTO cat_images (cat_id, image_url) VALUES ($id, '$filename')");
                    }
                }
            }
        }
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ!'); window.location.href='index.php';</script>";
        exit();
    }
}

// 3. ดึงข้อมูลเดิม
$cat = $conn->query("SELECT * FROM CatBreeds WHERE id = $id")->fetch_assoc();
$images = $conn->query("SELECT * FROM cat_images WHERE cat_id = $id");

if (file_exists('../includes/header.php')) { include '../includes/header.php'; }
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7f6; padding-top: 30px; }
        .card { border-radius: 15px; border: none; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .btn-gradient { background: linear-gradient(45deg, #6f42c1, #a155e8); color: white; border: none; }
        
        /* สไตล์กล่องรูปภาพที่มีปุ่มลบ */
        .img-container { position: relative; display: inline-block; }
        .img-preview { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; border: 2px solid #ddd; }
        .btn-delete-img { 
            position: absolute; top: -10px; right: -10px; 
            background: #ff4d4d; color: white; border-radius: 50%; 
            width: 25px; height: 25px; border: none; font-size: 14px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-delete-img:hover { background: #cc0000; }
    </style>
</head>
<body>
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card p-4">
                <h4 class="fw-bold mb-4">📝 แก้ไขข้อมูล: <?php echo $cat['name_th']; ?></h4>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row text-start">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ชื่อสายพันธุ์ (ไทย)</label>
                            <input type="text" name="name_th" class="form-control" value="<?php echo $cat['name_th']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ชื่อสายพันธุ์ (English)</label>
                            <input type="text" name="name_en" class="form-control" value="<?php echo $cat['name_en']; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">รายละเอียด/ประวัติ</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo $cat['description']; ?></textarea>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">ลักษณะเด่น (Characteristics)</label>
                        <textarea name="characteristics" class="form-control" rows="3"><?php echo $cat['characteristics']; ?></textarea>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">การดูแลรักษา (Care Instructions)</label>
                        <textarea name="care_instructions" class="form-control" rows="3"><?php echo $cat['care_instructions']; ?></textarea>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold d-block">รูปภาพปัจจุบันในระบบ (คลิก X เพื่อลบ)</label>
                        <div class="d-flex flex-wrap gap-4 p-3 bg-light rounded shadow-sm">
                            <?php if($images->num_rows > 0): ?>
                                <?php while($img = $images->fetch_assoc()): ?>
                                    <div class="img-container" id="img-box-<?php echo $img['id']; ?>">
                                        <button type="button" class="btn-delete-img" onclick="deleteImage(<?php echo $img['id']; ?>)">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <img src="../uploads/<?php echo $img['image_url']; ?>" class="img-preview">
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <span class="text-muted">ไม่มีรูปภาพในคอลเลกชัน</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold">เพิ่มรูปภาพใหม่ (เลือกได้หลายรูป)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-light px-4">ยกเลิก</a>
                        <button type="submit" name="update" class="btn btn-gradient px-5 fw-bold">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// ฟังก์ชันลบรูปภาพแบบไม่ต้องรีโหลดหน้า
function deleteImage(imgId) {
    if (confirm('คุณต้องการลบรูปภาพนี้ใช่หรือไม่?')) {
        fetch(`cat_edit.php?id=<?php echo $id; ?>&delete_img=${imgId}`)
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                // ลบกล่องรูปภาพออกจากหน้าจอทันที
                const imgBox = document.getElementById(`img-box-${imgId}`);
                imgBox.style.transition = "0.3s";
                imgBox.style.opacity = "0";
                setTimeout(() => imgBox.remove(), 300);
            } else {
                alert('เกิดข้อผิดพลาดในการลบรูปภาพ');
            }
        });
    }
}
</script>
</body>
</html>