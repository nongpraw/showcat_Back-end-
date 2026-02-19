<?php
session_start(); // ✅ เรียกครั้งเดียวพอ

error_reporting(E_ALL);
ini_set('display_errors', 1);

// กันคนที่ยังไม่ login
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

include "../db_connect.php";

// ดึงข้อมูลแมว
$result = $conn->query("SELECT * FROM cats ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Admin Panel - ระบบจัดการข้อมูลแมว</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(120deg,#eef2ff,#f8fafc);
        font-family:'Segoe UI',Tahoma,sans-serif;
    }
    .admin-header {
        background: linear-gradient(90deg,#4338ca,#6366f1);
        color:#fff;
        padding:30px;
        border-radius:20px;
        margin-bottom:30px;
        box-shadow: 0 4px 15px rgba(67, 56, 202, 0.2);
    }
    .card {
        border:none;
        border-radius:18px;
        box-shadow:0 10px 25px rgba(0,0,0,.08);
    }
    .table thead { background:#f1f5f9; }
    .table th { color:#334155; font-weight:600; border:none; }
    .table td { vertical-align:middle; border-bottom: 1px solid #f1f5f9; }
    .table img {
        width:90px;
        height:65px;
        object-fit:cover;
        border-radius:10px;
    }

    .badge-active { background:#22c55e; color:#fff; padding:6px 12px; border-radius:8px; }
    .badge-hidden { background:#64748b; color:#fff; padding:6px 12px; border-radius:8px; }

    .btn-add {
        background:#22c55e;
        color:#fff;
        border-radius:30px;
        padding:10px 20px;
    }
    .btn-custom-outline {
        background:rgba(255,255,255,.2);
        color:#fff;
        border:1px solid rgba(255,255,255,.4);
        border-radius:30px;
        padding:10px 20px;
        text-decoration:none;
    }
    .btn-logout-red {
        background:#ef4444;
        color:#fff;
        border-radius:30px;
        padding:10px 20px;
        text-decoration:none;
    }
</style>
</head>

<body>
<div class="container py-4">

    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">Admin Panel</h2>
            <p class="mb-0 opacity-75">Manage cat information and visibility</p>
        </div>
        <div class="d-flex gap-2">
            <a href="form.php" class="btn btn-add">+ Add new cat</a>
            <a href="../index.php" class="btn btn-custom-outline">Home</a>

            <a href="logout.php"
               class="btn btn-logout-red"
               onclick="return confirm('Do you want to logout?')">
               Logout
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th class="text-start">Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $row['id'] ?></td>
                            <td>
                                <?php
                                $img = !empty($row['image1'])
                                    ? "uploads/".$row['image1']
                                    : "https://via.placeholder.com/90x65";
                                ?>
                                <img src="<?= htmlspecialchars($img) ?>">
                            </td>
                            <td class="text-start"><?= htmlspecialchars($row['name'] ?? '-') ?></td>
                            <td>
                                <?= ($row['is_visible'] ?? 0) ? '<span class="badge-active">Active</span>' : '<span class="badge-hidden">Hidden</span>' ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm text-white">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this cat?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No data</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</body>
</html>
