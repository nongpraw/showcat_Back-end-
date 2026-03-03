<?php
session_start();

// 1. เชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "it67040233123"; 
$pass = "R0H1A9X3"; 
$db   = "it67040233123"; 

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

// ตรวจสอบสิทธิ์ (ถ้าไม่ได้ login ให้ไปหน้า login)
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// 2. ดึงข้อมูลปัจจุบันมาแสดง (ข้อมูลจะอัปเดตตามฐานข้อมูลทันที)
$res = $conn->query("SELECT * FROM admins WHERE id = $admin_id");
$admin = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile - View Only</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background: #f4f7f6; font-family: 'Sarabun', sans-serif; }
        .card { border-radius: 20px; border: none; overflow: hidden; }
        .profile-header { background: linear-gradient(45deg, #FF00CC, #3333FF); padding: 40px 20px; color: white; }
        .info-label { color: #6c757d; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; }
        .info-value { 
            background-color: #ffffff; 
            padding: 12px 15px; 
            border-radius: 10px; 
            border: 1px solid #e9ecef; 
            color: #212529;
            margin-bottom: 20px;
            min-height: 48px;
            display: flex;
            align-items: center;
        }
        .info-value-text { white-space: pre-line; align-items: flex-start; padding-top: 12px; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <div class="card shadow-lg">
                <div class="profile-header text-center">
                    <i class="bi bi-person-circle display-4 mb-2"></i>
                    <h3 class="fw-bold mb-0">ข้อมูลโปรไฟล์ผู้ดูแลระบบ</h3>
                    <div class="badge bg-light text-primary mt-3 px-4 py-2 rounded-pill border">
                        <i class="bi bi-shield-check me-1"></i> Username: <strong><?php echo htmlspecialchars($admin['username']); ?></strong>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-4 text-secondary border-bottom pb-2">
                                <i class="bi bi-person-vcard me-2"></i>ข้อมูลส่วนตัว
                            </h5>
                            
                            <label class="info-label">ชื่อ-นามสกุล</label>
                            <div class="info-value"><?php echo htmlspecialchars($admin['fullname'] ?: '-'); ?></div>

                            <label class="info-label">อีเมล</label>
                            <div class="info-value"><?php echo htmlspecialchars($admin['email'] ?: '-'); ?></div>

                            <label class="info-label">เบersโทรศัพท์</label>
                            <div class="info-value"><?php echo htmlspecialchars($admin['phone'] ?: '-'); ?></div>

                            <label class="info-label">ที่อยู่</label>
                            <div class="info-value info-value-text"><?php echo htmlspecialchars($admin['address'] ?: '-'); ?></div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="fw-bold mb-4 text-secondary border-bottom pb-2">
                                <i class="bi bi-mortarboard me-2"></i>ข้อมูลการศึกษา
                            </h5>

                            <label class="info-label text-danger">รหัสนักศึกษา</label>
                            <div class="info-value border-danger-subtle text-danger fw-bold"><?php echo htmlspecialchars($admin['student_id'] ?: '-'); ?></div>

                            <label class="info-label">คณะ/สาขาวิชา</label>
                            <div class="info-value"><?php echo htmlspecialchars($admin['major'] ?: '-'); ?></div>

                            <label class="info-label">ประวัติการศึกษา</label>
                            <div class="info-value info-value-text"><?php echo htmlspecialchars($admin['education'] ?: '-'); ?></div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 justify-content-center">
                        <a href="view.php" class="btn btn-light btn-lg border px-5 shadow-sm" style="border-radius: 12px;">
                            <i class="bi bi-arrow-left me-2"></i>กลับหน้าหลัก
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>