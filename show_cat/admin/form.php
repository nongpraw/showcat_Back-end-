<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Add Cat</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(120deg,#dbeafe,#fef3c7);
    font-family:'Segoe UI',Tahoma,sans-serif;
}
.form-card{
    border:none;
    border-radius:24px;
    box-shadow:0 20px 45px rgba(0,0,0,.15);
    overflow:hidden;
}
.form-header{
    background: linear-gradient(90deg,#3b82f6,#22c55e);
    color:#fff;
    padding:28px 32px;
}
.form-body{ padding:32px; }
.form-label{ font-weight:600; }
.form-control, .form-select{
    border-radius:14px;
    padding:12px 14px;
}
.btn-save{
    background:#3b82f6;
    color:#fff;
    border-radius:30px;
    padding:12px 34px;
}
.btn-save:hover{ background:#2563eb; }
</style>
</head>

<body>
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-8 col-xl-7">

<div class="card form-card">

<div class="form-header">
    <h3>Add New Cat</h3>
    <small>Fill in the information below</small>
</div>

<div class="form-body">

<form action="save.php" method="post" enctype="multipart/form-data">

<div class="mb-3">
    <label class="form-label">Cat Name</label>
    <input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Breed</label>
    <input type="text" name="breed" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3"></textarea>
</div>

<hr>

<div class="mb-3">
    <label class="form-label">Image 1 (Main)</label>
    <input type="file" name="image1" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Image 2</label>
    <input type="file" name="image2" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Image 3</label>
    <input type="file" name="image3" class="form-control">
</div>



<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="is_visible" class="form-select">
        <option value="1">Active</option>
        <option value="0">Hidden</option>
    </select>
</div>

<div class="d-flex justify-content-between">
    <a href="admin_index.php" class="btn btn-outline-secondary">
        Cancel
    </a>
    <button type="submit" class="btn btn-save">
        Save
    </button>
</div>

</form>

</div>
</div>

</div>
</div>
</div>
</body>
</html>
