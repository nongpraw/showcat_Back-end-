<?php
include "db_connect.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = $conn->query("SELECT * FROM CatBreeds WHERE id=$id AND is_visible=1");

if ($result->num_rows === 0) {
    echo "<h3 style='text-align:center'>Data not found</h3>";
    exit;
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($row['name_th']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f5f7fb;
    font-family:'Segoe UI',Tahoma,sans-serif;
}

.hero{
    max-height:420px;
    overflow:hidden;
    border-radius:20px;
}

.hero img{
    width:100%;
    height:420px;
    object-fit:cover;
}

.section-title{
    font-weight:700;
    color:#2c3e50;
}

.card{
    border:none;
    border-radius:16px;
}
</style>
</head>

<body>

<div class="container py-5">

<a href="index.php" class="btn btn-outline-secondary mb-4">
    Back to home
</a>

<div class="hero mb-4 shadow">
    <img src="uploads/<?= htmlspecialchars($row['image_url']) ?>"
         onerror="this.src='https://via.placeholder.com/800x400?text=No+Image'">
</div>

<div class="card shadow p-4">
    <h2 class="section-title"><?= htmlspecialchars($row['name_th']) ?></h2>
    <h5 class="text-muted mb-4"><?= htmlspecialchars($row['name_en']) ?></h5>

    <div class="mb-3">
        <strong>Description</strong>
        <p class="text-muted"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
    </div>

    <div class="mb-3">
        <strong>Characteristics</strong>
        <p class="text-muted"><?= nl2br(htmlspecialchars($row['characteristics'])) ?></p>
    </div>

    <div>
        <strong>Care instructions</strong>
        <p class="text-muted"><?= nl2br(htmlspecialchars($row['care_instructions'])) ?></p>
    </div>
</div>

</div>

</body>
</html>
