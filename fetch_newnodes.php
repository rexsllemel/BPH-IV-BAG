<?php
include "connect.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $illness = $_POST['illness'];
        $ivbag_type = $_POST['ivbag_type'];
        $date_admitted = $_POST['date_admitted'];
        $watcher = $_POST['watcher'];
        $contact = $_POST['contact'];
        $room = $_POST['room'];
        $ipaddress = $_POST['ipaddress'];
        $macAddress = $_POST['macaddress'];
        $station = $_POST['station'];

        $firebase_host = "https://ivbag-c6fd2-default-rtdb.firebaseio.com/";
        $firebase_auth = "npATCX3aayuKoQ3vunz9TTlACd7LMajjH2rVepfG";
        $path = "/bag_info/" . $macAddress . ".json";

        // Check if data already exists
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $firebase_host . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $existing_data = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200 && $existing_data !== "null") {
            echo "<script>alert('Data already exists for this MAC Address.');</script>";
        } else {
            $data = json_encode([
                "name" => $name,
                "last_name" => $last_name,
                "illness" => $illness,
                "ivbag_type" => $ivbag_type,
                "date_admitted" => $date_admitted,
                "watcher" => $watcher,
                "contact" => $contact,
                "room" => $room,
                "ipAddress" => $ipaddress,
                "station" => $station,
                "macAddress" => $macAddress
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $firebase_host . $path);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // This is where we specify the PUT method for Firebase
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code === 200) {
                echo "<script>alert('Data successfully sent to Firebase!');</script>";

                // Remove the /iv_nodes/{macAddress} path
                $delete_path = "/config_mode/" . $macAddress . ".json";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $firebase_host . $delete_path);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $delete_response = curl_exec($ch);
                $delete_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($delete_http_code === 200) {
                    echo "<script>alert('Node successfully removed from /iv_nodes.');</script>";
                } else {
                    echo "<script>alert('Failed to remove node from /iv_nodes.');</script>";
                }
            } else {
                echo "<script>alert('Failed to send data to Firebase.');</script>";
            }
        }
    }