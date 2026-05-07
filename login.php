<?php
include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email) && !empty($data->password)) {
        $email = $conn->real_escape_string($data->email);
        $password = $conn->real_escape_string($data->password);

        // 1. මුලින්ම Engineers table එකේ බලනවා
        $sqlEng = "SELECT * FROM engineers WHERE email='$email' AND password='$password'";
        $resultEng = $conn->query($sqlEng);

        if ($resultEng->num_rows > 0) {
            $user = $resultEng->fetch_assoc();
            $user['type'] = 'engineer'; // Type එක set කරනවා
            echo json_encode(["message" => "Login successful", "user" => $user]);
        } else {
            // 2. Engineer කෙනෙක් නෙවෙයි නම් Customers table එකේ බලනවා
            $sqlCust = "SELECT * FROM customers WHERE email='$email' AND password='$password'";
            $resultCust = $conn->query($sqlCust);

            if ($resultCust->num_rows > 0) {
                $user = $resultCust->fetch_assoc();
                $user['type'] = 'customer'; // Type එක set කරනවා
                echo json_encode(["message" => "Login successful", "user" => $user]);
            } else {
                echo json_encode(["message" => "Invalid email or password"]);
            }
        }
    } else {
        echo json_encode(["message" => "All fields are required"]);
    }
} else {
    echo json_encode(["message" => "Invalid Request"]);
}
?>