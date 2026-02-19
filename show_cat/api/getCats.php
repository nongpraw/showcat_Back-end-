<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "../db_connect.php";

$sql = "SELECT * FROM CatBreeds ORDER BY id DESC";
$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
