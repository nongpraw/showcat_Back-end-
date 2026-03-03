<?php
session_start();

// 1. เชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "it67040233123"; 
$pass = "R0H1A9X3"; 
$db   = "it67040233123"; 

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

// ตรวจสอบสิทธิ์ (ถ้าไม่ได้ login ให้ไปหน้า login ที่อยู่นอกโฟลเดอร์ admin)
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// 2. ส่วนการอัปเดตข้อมูล
if(isset($_POST['update'])) {
    $fullname   = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    $address    = mysqli_real_escape_string($conn, $_POST['address']);
    $education  = mysqli_real_escape_string($conn, $_POST['education']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $major      = mysqli_real_escape_string($conn, $_POST['major']);
    
    $stmt = $conn->prepare("UPDATE admins SET fullname=?, email=?, phone=?, address=?, education=?, student_id=?, major=? WHERE id=?");
    $stmt->bind_param("sssssssi", $fullname, $email, $phone, $address, $education, $student_id, $major, $admin_id);
    
    if($stmt->execute()) {
        $success = "อัปเดตข้อมูลสำเร็จแล้ว!";
    }
}

// 3. ดึงข้อมูลปัจจุบันมาแสดง
$res = $conn->query("SELECT * FROM admins WHERE id = $admin_id");
$admin = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7f6; font-family: 'Sarabun', sans-serif; }
        .card { border-radius: 20px; border: none; }
        .form-control { border-radius: 10px; padding: 10px; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-lg">
                <div class="card-header py-4 text-white text-center" style="background: linear-gradient(45deg, #FF00CC, #3333FF);">
                    <h3 class="fw-bold mb-0">📝 แก้ไขข้อมูลโปรไฟล์ Admin</h3>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <?php if(isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="text-center mb-4">
                            <div class="badge bg-light text-primary p-2 rounded-pill px-4 border">
                                <i class="bi bi-person-circle"></i> Username: <strong><?php echo htmlspecialchars($admin['username']); ?></strong>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3 text-secondary border-bottom pb-2">ข้อมูลส่วนตัว</h5>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">ชื่อ-นามสกุล</label>
                                    <input type="text" name="fullname" class="form-control shadow-sm" value="<?php echo htmlspecialchars($admin['fullname'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">อีเมล</label>
                                    <input type="email" name="email" class="form-control shadow-sm" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">รหัสผ่าน</label>
                                    <input type="password" name="password" class="form-control shadow-sm" value="<?php echo htmlspecialchars($admin['password'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">เบอร์โทรศัพท์</label>
                                    <input type="text" name="phone" class="form-control shadow-sm" value="<?php echo htmlspecialchars($admin['phone'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">ที่อยู่</label>
                                    <textarea name="address" class="form-control shadow-sm" rows="3"><?php echo htmlspecialchars($admin['address'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3 text-secondary border-bottom pb-2">ข้อมูลการศึกษา</h5>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-danger">รหัสนักศึกษา</label>
                                    <input type="text" name="student_id" class="form-control shadow-sm border-danger" value="<?php echo htmlspecialchars($admin['student_id'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">คณะ/สาขาวิชา</label>
                                    <input type="text" name="major" class="form-control shadow-sm" value="<?php echo htmlspecialchars($admin['major'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">ประวัติการศึกษา</label>
                                    <textarea name="education" class="form-control shadow-sm" rows="5"><?php echo htmlspecialchars($admin['education'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex gap-2">
                            <button type="submit" name="update" class="btn btn-primary btn-lg flex-grow-1 fw-bold shadow-sm" style="border-radius: 12px;">
                                <i class="bi bi-save me-2"></i> บันทึกข้อมูล
                            </button>
                            <a href="index.php" class="btn btn-light btn-lg border px-4" style="border-radius: 12px;">กลับ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>