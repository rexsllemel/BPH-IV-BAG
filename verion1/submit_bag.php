<?php
include "connect.php";

header('Content-Type: application/json');

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $ivbag_type = $conn->real_escape_string($_POST['ivbag_type']);
    $room = $conn->real_escape_string($_POST['room']);
    $ipaddress = $conn->real_escape_string($_POST['ipaddress']);
    $macaddress = $conn->real_escape_string($_POST['macaddress']);

    $sql = "INSERT INTO bag_info (name, ivbag_type, room, ipaddress, macaddress) 
            VALUES ('$name', '$ivbag_type', '$room', '$ipaddress', '$macaddress')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "New record created successfully."]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>
