<?php
session_start();
$_SESSION = array(); // ล้างข้อมูล Session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
session_destroy(); // ทำลาย Session
header("Location: ../index.php"); // ส่งกลับไปหน้าแรก
exit();
?>