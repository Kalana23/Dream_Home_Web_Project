<?php
include 'config.php';
$data = json_decode(file_get_contents("php://input"));

if ($data && isset($_GET['id'])) {
    $id = $_GET['id'];
    $name = $conn->real_escape_string($data->name);
    $phone = $conn->real_escape_string($data->phone);
    $spec = $conn->real_escape_string($data->specialization);
    $price = $conn->real_escape_string($data->price); // Price එක ලබා ගැනීම

    $sql = "UPDATE engineers SET name='$name', phone='$phone', specialization='$spec', price='$price' WHERE id='$id'";
    
    if ($conn->query($sql)) {
        // අලුත් දත්ත නැවත එවීම (Frontend එක Update කරන්න)
        $res = $conn->query("SELECT * FROM engineers WHERE id='$id'");
        $updatedUser = $res->fetch_assoc();
        $updatedUser['type'] = 'engineer';
        echo json_encode(["message" => "Profile Updated Successfully", "user" => $updatedUser]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}
?>