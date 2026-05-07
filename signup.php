<?php
include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email) && !empty($data->password) && !empty($data->name) && !empty($data->type)) {
        
        $email = $conn->real_escape_string($data->email);
        $password = $conn->real_escape_string($data->password); // Passwords should normally be hashed, kept simple as per your request
        $name = $conn->real_escape_string($data->name);
        $phone = $conn->real_escape_string($data->phone);
        $address = $conn->real_escape_string($data->address);
        $type = $conn->real_escape_string($data->type);
        
        // 1. කලින් මේ Email එක Engineers ලා අතර තියෙනවද බලනවා
        $checkEng = $conn->query("SELECT * FROM engineers WHERE email='$email'");
        // 2. කලින් මේ Email එක Customers ලා අතර තියෙනවද බලනවා
        $checkCust = $conn->query("SELECT * FROM customers WHERE email='$email'");

        if ($checkEng->num_rows > 0 || $checkCust->num_rows > 0) {
            echo json_encode(["message" => "Email already exists!"]);
        } else {
            $sql = "";
            
            if ($type === 'engineer') {
                // Engineer කෙනෙක් නම් specialization එක ගන්නවා
                $specialization = isset($data->specialization) ? $conn->real_escape_string($data->specialization) : '';
                // engineers table එකට දානවා
                $sql = "INSERT INTO engineers (name, email, password, phone, address, specialization, price) VALUES ('$name', '$email', '$password', '$phone', '$address', '$specialization', 0)";
            } else {
                // Customer කෙනෙක් නම් customers table එකට දානවා
                $sql = "INSERT INTO customers (name, email, password, phone, address) VALUES ('$name', '$email', '$password', '$phone', '$address')";
            }

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Registration successful!"]);
            } else {
                echo json_encode(["message" => "Database Error: " . $conn->error]);
            }
        }
    } else {
        echo json_encode(["message" => "All fields are required!"]);
    }
} else {
    echo json_encode(["message" => "Invalid Request"]);
}
?>