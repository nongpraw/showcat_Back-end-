<?php
session_start(); // เริ่มต้น Session เพื่อเช็คสถานะการ Login
include "db_connect.php";
$result = $conn->query("SELECT * FROM CatBreeds WHERE is_visible = 1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Cat Breeds</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body{ background: linear-gradient(120deg,#f6f9ff,#eef2ff); font-family: 'Segoe UI', sans-serif; }
    .header{ text-align:center; padding:40px 20px; }
    .card{ border:none; border-radius:16px; overflow:hidden; transition:0.3s; }
    .card:hover{ transform:translateY(-5px); box-shadow:0 15px 30px rgba(0,0,0,.12); }
    .card img{ height:220px; object-fit:cover; }
    .btn-custom{ background:#4f46e5; color:#fff; border-radius:30px; }
</style>
</head>
<body>

<div class="container mt-3">
    <div class="text-end">
        <?php if(isset($_SESSION['user_id']) || isset($_SESSION['admin_login'])): ?>
            <div class="btn-group">
                <a href="admin/admin_index.php" class="btn btn-primary">⚙️ Management</a>
                <a href="admin/logout.php" class="btn btn-danger" onclick="return confirm('Do you want to logout?')">Logout</a>
            </div>
        <?php else: ?>
            <a href="admin/login.php" class="btn btn-outline-primary">🔐 Login (Admin)</a>
        <?php endif; ?>
    </div>
</div>

<div class="header">
    <h1>Cat Breeds</h1>
    <p>Discover popular cat breeds and their unique characteristics</p>
</div>

<div class="container pb-5">
    <div class="row">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="uploads/<?= htmlspecialchars($row['image_url']) ?>" onerror="this.src='https://via.placeholder.com/400x250?text=No+Image'">
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold"><?= htmlspecialchars($row['name_th']) ?></h5>
                    <p class="text-muted small"><?= mb_substr($row['description'],0,90) ?>...</p>
                    <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-custom mt-auto">View details</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</div>
</body>
</html>