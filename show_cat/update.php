<?php
include "db_connect.php";

$id = $_POST['id'];
$image_sql = "";

if (!empty($_FILES['image']['name'])) {
    $image = time()."_".$_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);
    $image_sql = ", image_url='$image'";
}

$sql = "
UPDATE CatBreeds SET
name_th='{$_POST['name_th']}',
name_en='{$_POST['name_en']}',
description='{$_POST['description']}',
characteristics='{$_POST['characteristics']}',
care_instructions='{$_POST['care_instructions']}',
is_visible={$_POST['is_visible']}
$image_sql
WHERE id=$id
";
$conn->query($sql);

header("Location: admin_index.php");
