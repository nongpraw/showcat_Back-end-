<?php include "includes/header.php"; ?>

<h2 class="mb-4">Admin Login</h2>

<form method="post" action="login_check.php" style="max-width:400px;">
    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Login
    </button>
</form>

<?php include "includes/footer.php"; ?>
