<?php
header('Content-Type: application/json');
include "connect.php";

// Function to ping an IP address
// function ping($ip) {
//     // Execute the ping command and capture both output and return status
//     $pingResult = shell_exec(sprintf('ping -n 1 -w 1000 %s 2>&1', escapeshellarg($ip))); // Redirect errors to output
//     // error_log("Ping command executed for IP $ip. Result: $pingResult"); // Log the full result for debugging

//     // Check if the output contains 'TTL' (indicating a successful ping)
//     if (strpos($pingResult, 'TTL') !== false) {
//         return true;
//     }

//     // Log if the ping failed
//     // error_log("Ping failed for IP $ip. Check network or firewall settings.");
//     return false;
// }

// Function to fetch data from Firebase
function fetchFromFirebase($endpoint) {
    $firebaseUrl = FIREBASE_HOST . $endpoint . ".json?auth=" . FIREBASE_AUTH;

    // Debugging: Log the Firebase URL
    error_log("Fetching data from Firebase URL: " . $firebaseUrl);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $firebaseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Debugging: Log the raw Firebase response
    error_log("Raw Firebase response: " . $response);

    if ($response === false) {
        return ["error" => "Failed to fetch data from Firebase"];
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        return ["error" => "Invalid JSON response from Firebase"];
    }

    return $data;
}

// Fetch data from Firebase
$data = fetchFromFirebase("bag_info");

// Debugging: Log the fetched data
if (empty($data)) {
    error_log("No data found in Firebase under /bag_info.");
} else {
    error_log("Fetched data from Firebase: " . json_encode($data));
}

// Ensure data is valid
if (!is_array($data)) {
    $data = []; // Default to an empty array if data is null or invalid
}

// Filter required fields
$filteredData = [];
foreach ($data as $mac => $info) {
    error_log("Processing macaddress: " . $mac); // Debugging: Log macaddress
    $filteredData[] = [
        "name" => $info['name'] ?? null, 
        "lastname" => $info['last_name'] ?? null, 
        "active_status" => $info['active_status'] ?? null, 
        "ivbag_level" => $info['ivbag_level'] ?? null,
        "backflow" => $info['backflow'] ?? null,
        "room" => $info['room'] ?? null,
        "ipAddress" => $info['ipAddress'] ?? null,
        "macaddress" => $mac, // Ensure macaddress is included
        "ivbag_type" => $info['ivbag_type'] ?? null,
        "contact" => $info['contact'] ?? null,
        "dateAdmitted" => $info['date_admitted'] ?? null,
        "illness" => $info['illness'] ?? null,
        "watcher" => $info['watcher'] ?? null,
    ];
}

// Add IP active status
// foreach ($filteredData as &$row) {
//     $row['ip_active'] = ping($row['ipAddress']) ? true : false;
// }

if (isset($_GET['fetch_updates'])) {
    $data = fetchFromFirebase("bag_info");

    // Ensure data is valid
    if (!is_array($data)) {
        $data = []; // Default to an empty array if data is null or invalid
    }

    $filteredData = [];
    foreach ($data as $mac => $info) {
        error_log("Processing macaddress: " . $mac); // Debugging: Log macaddress
        $filteredData[] = [
            "name" => $info['name'] ?? null,
            "active_status" => $info['active_status'] ?? null,
            "ivbag_level" => $info['ivbag_level'] ?? null,
            "backflow" => $info['backflow'] ?? null,
            "room" => $info['room'] ?? null,
            "ipAddress" => $info['ipAddress'] ?? null,
            "macaddress" => $mac, // Ensure macaddress is included
            "ivbag_type" => $info['ivbag_type'] ?? null,
        ];
    }
    // foreach ($filteredData as &$row) {
    //     $row['ip_active'] = ping($row['ipAddress']) ? true : false;
    // }
    echo json_encode($filteredData);
    exit;
}

// Return JSON response
echo json_encode($filteredData);
