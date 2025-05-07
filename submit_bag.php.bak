<?php
include "connect.php";

header('Content-Type: application/json');

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $ivbag_type = $_POST['ivbag_type'];
    $room = $_POST['room'];
    $ipaddress = $_POST['ipaddress'];
    $macaddress = $_POST['macaddress'];

    $data = [
        "name" => $name,
        "ivbag_type" => $ivbag_type,
        "room" => $room,
        "ipaddress" => $ipaddress,
        "macaddress" => $macaddress
    ];

    $url = FIREBASE_HOST . "/bag_info/" . $macaddress . ".json?auth=" . FIREBASE_AUTH;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch); // Capture cURL errors
    curl_close($ch);

    // Check if the response is valid JSON
    $decoded_response = json_decode($response, true);

    if ($http_code === 200 && $decoded_response !== null) {
        echo json_encode(["success" => true, "message" => "New record created successfully."]);
    } else {
        // Log the error details for debugging
        echo json_encode([
            "success" => false,
            "error" => "Failed to create record in Firebase.",
            "http_code" => $http_code,
            "response" => $response, // Raw response for debugging
            "decoded_response" => $decoded_response, // Decoded response (if any)
            "curl_error" => $curl_error
        ]);
    }
} else {
    echo json_encode([
        "success" => false, 
        "error" => "Invalid request method.",
        "method" => $_SERVER["REQUEST_METHOD"] // Debugging: log the request method
    ]);
}
?>
