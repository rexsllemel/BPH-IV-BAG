<?php
include "connect.php";

$firebase_url = FIREBASE_HOST . "/iv_nodes.json?auth=" . FIREBASE_AUTH;
$response = file_get_contents($firebase_url);
$nodes = json_decode($response, true);

if ($nodes) {
    foreach ($nodes as $macAddress => $node) {
        echo '<div class="new-bag-info" style="background-color: ' . htmlspecialchars($node['color']) . ';" 
              data-ipaddress="' . htmlspecialchars($node['ipaddress']) . '" 
              data-macaddress="' . htmlspecialchars($macAddress) . '">';
        // echo '<p>ID: ' . htmlspecialchars($node['id']) . '</p>';
        echo '<p>IP Address: ' . htmlspecialchars($node['ipaddress']) . '</p>';
        echo '<p>MAC Address: ' . htmlspecialchars($macAddress) . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>No new nodes found.</p>';
}
