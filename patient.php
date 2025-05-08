<?php
// DEBUG: Uncomment the following line to see the full request URI
// echo $_SERVER['REQUEST_URI']; exit;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Match /patient.php/ABCDEF123456
if (!isset($_GET['mac']) && preg_match('#/patient\.php/([A-F0-9]+)$#i', $uri, $matches)) {
    $_GET['mac'] = $matches[1];
}

// Get the MAC address from the URL or query string
$mac = $_GET['mac'] ?? '';

if (!$mac) {
    die("No MAC address provided.");
}

// Firebase Realtime Database URL (adjust this to your actual Firebase URL)
$firebase_url = "https://ivbag-c6fd2-default-rtdb.firebaseio.com/bag_info/{$mac}.json";

// Fetch data from Firebase
$response = file_get_contents($firebase_url);

if ($response === false) {
    die("Failed to connect to Firebase.");
}

$data = json_decode($response, true);

if (empty($data)) {
    die("No data found for MAC address: " . htmlspecialchars($mac));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3>Patient Information</h3>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?> <?= htmlspecialchars($data['last_name']) ?></p>
            <p><strong>IV Bag Level:</strong> <?= htmlspecialchars($data['ivbag_level']) ?>%</p>
            <p><strong>Blood Backflow:</strong> <?= $data['backflow'] ? 'Yes' : 'No' ?></p>
            <p><strong>IV Bag Type:</strong> <?= htmlspecialchars($data['ivbag_type']) ?></p>
            <p><strong>Room:</strong> <?= htmlspecialchars($data['room']) ?></p>
            <p><strong>Watcher:</strong> <?= htmlspecialchars($data['watcher']) ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($data['contact']) ?></p>
            <p><strong>Illness:</strong> <?= htmlspecialchars($data['illness']) ?></p>
            <p><strong>Sensor Status:</strong> <?= $data['active_status'] ? 'Active' : 'Inactive' ?></p>
            <p><strong>Date Admitted:</strong> <?= htmlspecialchars($data['date_admitted']) ?></p>
        </div>
    </div>
</div>

</body>
</html>
