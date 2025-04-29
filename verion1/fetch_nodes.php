<?php
include "connect.php";

if ($result_nodes->num_rows > 0) {
    while ($row = $result_nodes->fetch_assoc()) {
        echo '<div class="new-bag-info" style="background-color: ' . htmlspecialchars($row['color']) . ';" 
              data-ipaddress="' . htmlspecialchars($row['ipaddress']) . '" 
              data-macaddress="' . htmlspecialchars($row['macaddress']) . '">';
        echo '<p>ID: ' . htmlspecialchars($row['id']) . '</p>';
        echo '<p>IP Address: ' . htmlspecialchars($row['ipaddress']) . '</p>';
        echo '<p>MAC Address: ' . htmlspecialchars($row['macaddress']) . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>No nodes found.</p>';
}
?>
