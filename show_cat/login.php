<?php
session_start();

// 1. ข้อมูลการเชื่อมต่อฐานข้อมูล (อ้างอิงจาก it67040233123)
$host = "localhost";
$user = "it67040233123"; 
$pass = "R0H1A9X3"; 
$db   = "it67040233123"; 

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    // รับค่าจาก Form และป้องกันการ SQL Injection
    $user_login = mysqli_real_escape_string($conn, $_POST['username']);
    $pass_login = mysqli_real_escape_string($conn, $_POST['password']);

    // 2. Query เช็คจากตาราง admins จริงๆ (ใช้ password เป็น 1234 ตามที่คุณแก้ล่าสุด)
    $sql = "SELECT id, username FROM admins WHERE username = '$user_login' AND password = '$pass_login' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // 3. สร้าง Session เพื่อยืนยันตัวตน
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        
        // 4. ส่งไปหน้า admin/index.php โดยใช้ PHP Header
        header("Location: admin/index.php");
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Cat System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; height: 100vh; display: flex; align-items: center; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-primary { background: #6610f2; border: none; }
        .btn-primary:hover { background: #520dc2; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4">
                <h3 class="text-center mb-4 fw-bold">ADMIN LOGIN</h3>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger py-2 small text-center"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100 py-2 fw-bold">เข้าสู่ระบบ</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>