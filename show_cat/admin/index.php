<?php
session_start();
// 1. เชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "it67040233123"; 
$pass = "R0H1A9X3"; 
$db   = "it67040233123"; 

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

// 2. ตรวจสอบ Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cat System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Sarabun', sans-serif; }
        .navbar { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card { border-radius: 15px; border: none; }
        .btn-view-main { background-color: #00cae3; color: white; border-radius: 50px; border: none; }
        .btn-view-main:hover { background-color: #00b3c9; color: white; }
        .img-preview { width: 70px; height: 50px; object-fit: cover; border-radius: 8px; }
        .table thead th { border: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php">CAT ADMIN</a>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="me-2 text-muted small d-none d-md-inline">สวัสดี, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="../view.php" class="btn btn-view-main btn-sm px-3" target="_blank">
                <i class="bi bi-globe me-1"></i> ดูหน้าเว็บหลัก
            </a>
            <a href="profile.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="bi bi-person-circle me-1"></i> โปรไฟล์
            </a>
            <a href="../logout.php" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('ยืนยันการออกจากระบบ?')">
                <i class="bi bi-box-arrow-right me-1"></i> ออกจากระบบ
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white shadow-sm rounded-4">
        <h2 class="fw-bold text-dark m-0">ระบบจัดการข้อมูลสายพันธุ์แมว</h2>
        <a href="cat_add.php" class="btn btn-primary px-4 py-2 fw-bold" style="background: linear-gradient(45deg, #FF00CC, #3333FF); border: none; border-radius: 10px;">
            <i class="bi bi-plus-circle me-1"></i> เพิ่มแมวตัวใหม่
        </a>
    </div>

    <div class="card shadow-lg overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-white">
                        <tr style="background: #333;">
                            <th class="p-3 text-center">#</th>
                            <th>ชื่อสายพันธุ์ (ไทย / EN)</th>
                            <th>รายละเอียดโดยย่อ</th>
                            <th class="text-center">รูปภาพหน้าปก</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">จัดการข้อมูล</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM CatBreeds ORDER BY id DESC";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $preview_img = (!empty($row['image_url'])) ? "../uploads/" . $row['image_url'] : "https://via.placeholder.com/80x60?text=No+Img";
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-muted"><?php echo $row['id']; ?></td>
                            <td>
                                <a href="../view.php?id=<?php echo $row['id']; ?>" target="_blank" class="text-decoration-none">
                                    <div class="fw-bold text-primary"><?php echo htmlspecialchars($row['name_th']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['name_en']); ?></small>
                                </a>
                            </td>
                            <td>
                                <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="small text-muted">
                                    <?php echo strip_tags($row['description']); ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <img src="<?php echo $preview_img; ?>" class="img-preview shadow-sm border">
                            </td>
                            <td class="text-center">
                                <?php if (isset($row['is_visible']) && $row['is_visible'] == 1): ?>
                                    <a href="toggle_visibility.php?id=<?php echo $row['id']; ?>&status=0" 
                                       class="btn btn-success btn-sm rounded-pill px-3 shadow-sm"
                                       onclick="return confirm('ต้องการปิดการแสดงผลสายพันธุ์นี้ใช่หรือไม่?')">
                                        <i class="bi bi-eye-fill"></i> แสดงอยู่
                                    </a>
                                <?php else: ?>
                                    <a href="toggle_visibility.php?id=<?php echo $row['id']; ?>&status=1" 
                                       class="btn btn-secondary btn-sm rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-eye-slash-fill"></i> ปิดไว้
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group gap-1">
                                    <a href="cat_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm text-white">
                                        <i class="bi bi-pencil-square"></i> แก้ไข
                                    </a>
                                    <a href="cat_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูลนี้?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } } else {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>ไม่มีข้อมูลสายพันธุ์แมว</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>