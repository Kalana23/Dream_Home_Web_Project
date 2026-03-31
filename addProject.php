<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $engId = $_POST['engineerId'];
    $title = $_POST['title'];
    $desc = $_POST['description'];

    // ෆොටෝ එකක් Upload කර ඇත්දැයි බැලීම
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/';
        
        // uploads ෆෝල්ඩරය නැත්නම් එය සාදන්න
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // ෆොටෝ එකට අලුත් නමක් දීම
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        // අවසර ලත් ෆයිල් වර්ග
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $sql = "INSERT INTO projects (engineer_id, title, description, image_url) VALUES ('$engId', '$title', '$desc', '$targetPath')";
                if ($conn->query($sql)) {
                    echo json_encode(["message" => "Project Published Successfully!"]);
                } else {
                    echo json_encode(["message" => "Database Error: " . $conn->error]);
                }
            } else {
                echo json_encode(["message" => "Error uploading the file."]);
            }
        } else {
            echo json_encode(["message" => "Only JPG, JPEG, PNG, GIF & WEBP files are allowed."]);
        }
    } else {
        echo json_encode(["message" => "Please select an image file."]);
    }
} else {
    echo json_encode(["message" => "Invalid Request"]);
}
?>