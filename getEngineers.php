<?php
include 'config.php';
$res = $conn->query("SELECT * FROM engineers");
$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode(["engineers" => $data]);
?>