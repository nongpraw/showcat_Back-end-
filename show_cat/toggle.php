<?php
include "db_connect.php";
$id = $_GET['id'];
$conn->query("UPDATE CatBreeds SET is_visible = 1 - is_visible WHERE id=$id");
header("Location: admin_index.php");
