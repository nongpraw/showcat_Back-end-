<?php
// 1. เปิดการแสดง Error สำหรับ Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. เชื่อมต่อฐานข้อมูล (เช็ค Path ไฟล์ db_connect.php)
if (file_exists("db_connect.php")) {
    include "db_connect.php";
} elseif (file_exists("../db_connect.php")) {
    include "../db_connect.php";
} else {
    die("Error: ไม่พบไฟล์ db_connect.php กรุณาตรวจสอบตำแหน่งไฟล์");
}

// 3. ตรวจสอบตัวแปรการเชื่อมต่อ
if (!isset($conn) || $conn->connect_error) {
    die("Database Connection Error หรือชื่อตัวแปร \$conn ไม่ถูกต้อง");
}

// 4. รับค่า ID และดึงข้อมูลจากตาราง cats
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = $conn->query("SELECT * FROM cats WHERE id=$id"); // แก้ชื่อตารางเป็น cats แล้ว

if (!$result) {
    die("Query Error: " . $conn->error);
}

if ($result->num_rows === 0) {
    echo "<div style='text-align:center; margin-top:50px;'><h3>ไม่พบข้อมูลแมว ID: $id ในตาราง cats</h3><a href='admin_index.php'>กลับหน้าหลัก</a></div>";
    exit;
}

$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cat - <?= htmlspecialchars($row['name_th'] ?? 'Unknown') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(120deg,#e0e7ff,#fef3c7); font-family:'Segoe UI',Tahoma,sans-serif; min-height: 100vh; }
        .edit-card { border:none; border-radius:22px; box-shadow:0 20px 40px rgba(0,0,0,.15); overflow:hidden; background: #fff; }
        .edit-header { background: linear-gradient(90deg,#6366f1,#22c55e); color:#fff; padding:25px 30px; }
        .preview-img { width:100%; height:260px; object-fit:cover; border-radius:16px; box-shadow:0 10px 20px rgba(0,0,0,.2); }
        .form-control, .form-select { border-radius:14px; padding:12px 14px; }
        .form-label { font-weight:600; color:#374151; }
        .btn-save { background:#6366f1; color:#fff; border-radius:30px; padding:12px 30px; border: none; transition: 0.3s; cursor: pointer; }
        .btn-save:hover { background:#4f46e5; transform: translateY(-2px); color: #fff; }
        .btn-cancel { border-radius:30px; padding:12px 30px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card edit-card">
                <div class="edit-header">
                    <h3 class="mb-0">แก้ไขข้อมูลสายพันธุ์แมว (Table: cats)</h3>
                    <small>รหัสข้อมูล: #<?= $id ?></small>
                </div>

                <div class="card-body p-4">
                    <div class="mb-4 text-center">
                        <?php 
                            $img_name = isset($row['image_url']) ? $row['image_url'] : '';
                            $img_path = !empty($img_name) ? "uploads/" . htmlspecialchars($img_name) : "https://via.placeholder.com/600x300?text=No+Image";
                        ?>
                        <img src="<?= $img_path ?>" class="preview-img" id="imgPreview" onerror="this.src='https://via.placeholder.com/600x300?text=No+Image'">
                    </div>

                    <form action="update.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อ (ภาษาไทย)</label>
                                <input type="text" name="name_th" class="form-control" value="<?= htmlspecialchars($row['name_th'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อ (English)</label>
                                <input type="text" name="name_en" class="form-control" value="<?= htmlspecialchars($row['name_en'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">คำอธิบายสายพันธุ์</label>
                            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ลักษณะทั่วไป</label>
                            <textarea name="characteristics" class="form-control" rows="2"><?= htmlspecialchars($row['characteristics'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">คำแนะนำการดูแล</label>
                            <textarea name="care_instructions" class="form-control" rows="2"><?= htmlspecialchars($row['care_instructions'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เปลี่ยนรูปภาพ</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">สถานะการแสดงผล</label>
                            <select name="is_visible" class="form-select">
                                <?php $visible = isset($row['is_visible']) ? $row['is_visible'] : 1; ?>
                                <option value="1" <?= $visible == 1 ? 'selected' : '' ?>>แสดง (Active)</option>
                                <option value="0" <?= $visible == 0 ? 'selected' : '' ?>>ซ่อน (Hidden)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-4">
                            <a href="admin_index.php" class="btn btn-outline-secondary btn-cancel">ยกเลิก</a>
                            <button type="submit" name="update" class="btn btn-save">บันทึกการแก้ไข</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>