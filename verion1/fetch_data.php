<?php
header('Content-Type: application/json');
include "connect.php";

// Function to ping an IP address
function ping($ip) {
    // Execute the ping command and capture both output and return status
    $pingResult = shell_exec(sprintf('ping -n 1 -w 1000 %s 2>&1', escapeshellarg($ip))); // Redirect errors to output
    // error_log("Ping command executed for IP $ip. Result: $pingResult"); // Log the full result for debugging

    // Check if the output contains 'TTL' (indicating a successful ping)
    if (strpos($pingResult, 'TTL') !== false) {
        return true;
    }

    // Log if the ping failed
    // error_log("Ping failed for IP $ip. Check network or firewall settings.");
    return false;
}

// Fetch data from `bag_info`
$sql = "SELECT id, name, active_status, ivbag_level, backflow, room, ipaddress, macaddress, ivbag_type FROM bag_info";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['ip_active'] = ping($row['ipaddress']) ? true : false; // Add IP active status
        $data[] = $row;
    }
}
if (isset($_GET['fetch_updates'])) {
    $sql = "SELECT id, name, active_status, ivbag_level, backflow, room, ipaddress, macaddress, ivbag_type FROM bag_info";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['ip_active'] = ping($row['ipaddress']) ? true : false; // Add IP active status
            $data[] = $row;
        }
    }
    echo json_encode($data);
    $conn->close();
    exit;
}
// Return JSON response
echo json_encode($data);
$conn->close();