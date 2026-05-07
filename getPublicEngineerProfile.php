<?php
include 'config.php';

if (isset($_GET['engineerId'])) {
    $id = $conn->real_escape_string($_GET['engineerId']);
    
    // Engineer ගේ විස්තර ගන්න (experience සහ education එක්ක)
    $engRes = $conn->query("SELECT id, name, email, phone, address, specialization, experience, education FROM engineers WHERE id='$id'");
    
    if ($engRes->num_rows > 0) {
        $engineer = $engRes->fetch_assoc();

        // Project විස්තර ගන්න
        $projRes = $conn->query("SELECT * FROM projects WHERE engineer_id='$id'");
        $projects = [];
        while ($row = $projRes->fetch_assoc()) {
            $projects[] = $row;
        }
        
        $engineer['projects'] = $projects;
        echo json_encode(["engineer" => $engineer]);
    } else {
        echo json_encode(["message" => "Engineer not found"]);
    }
}
?>