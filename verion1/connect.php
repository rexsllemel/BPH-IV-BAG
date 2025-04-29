<?php
// Database connection
$servername = "localhost";
$username = "ivbag";
$password = "@AMAcapstone#123";
$dbname = "ivbag_data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch data from bag_info table
$sql = "SELECT id, name, active_status, ivbag_level, backflow, room, ipaddress, macaddress, ivbag_type FROM bag_info";
$result = $conn->query($sql);

// Fetch data from iv_node table
$sql_nodes = "SELECT id, ipaddress, macaddress, color FROM iv_nodes";
$result_nodes = $conn->query($sql_nodes);
