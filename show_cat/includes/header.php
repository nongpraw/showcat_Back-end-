<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f4f7fe; }
        .navbar { background: linear-gradient(135deg, #4f46e5 0%, #e11d48 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); transition: 0.3s; }
        .btn-gradient { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; border: none; }
        .btn-gradient:hover { opacity: 0.9; color: white; }
        .main-content { padding: 40px 0; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php">CAT SYSTEM</a>
        <div class="navbar-nav ms-auto">
            <?php 
            // เริ่มการตรวจสอบเงื่อนไขหน้าปัจจุบัน
            $current_page = basename($_SERVER['PHP_SELF']); 
            
            // เช็ค Session (ปรับชื่อตัวแปร Session ให้ตรงกับที่คุณใช้ เช่น admin_id หรือ username)
            if(isset($_SESSION['admin_id']) || isset($_SESSION['username'])): ?>
                
             
                <a class="nav-link text-white" href="profile.php">ผู้จัดทำ</a>
                
                <?php 
                // เงื่อนไข: ถ้าไม่ใช่หน้า view.php ให้แสดงปุ่มออกจากระบบ
                if ($current_page !== 'view.php'): 
                    // เช็ค Path ของไฟล์ logout
                    $logout_link = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../logout.php' : 'logout.php';
                ?>
                    <a class="btn btn-outline-light btn-sm ms-3" href="<?php echo $logout_link; ?>">ออกจากระบบ</a>
                <?php endif; ?>

            <?php else: ?>
                <a class="btn btn-outline-light btn-sm" href="login.php">เข้าสู่ระบบ Admin</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container main-content">