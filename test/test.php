<!-- filepath: e:\Documents\ACLC\capstone\BPH IV BAG\index.php -->
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ivbag_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from bag_info table
$sql = "SELECT id, name, active_status, ivbag_level, backflow, room, ipaddress FROM bag_info";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles2.css">
    <script type="text/javascript" src="script.js" defer></script>
</head>
<body>
    <nav id="sidebar">
        <ul>
            <li>
                <span class="logo">IV Bag Information</span>
                <button onclick=toggleSidebar() id="toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"/></svg>
                </button>
            </li>
            <li class="active">
                <a href="index.html">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="dashboard.html">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <button onclick=toggleSubMenu(this) class="dropdown-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-320h80v-80h80v-80h-80v-80h-80v80h-80v80h80v80ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h240l80 80h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm0 0v-480 480Z"/></svg>
                <span>Create</span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>
                </button>
                <ul class="sub-menu">
                    <div>
                        <li>
                            <a href="#">Folder</a>
                        </li>
                        <li>
                            <a href="#">Document</a>
                        </li>
                        <li>
                            <a href="#">Project</a>
                        </li>
                    </div>
                </ul>
            </li>
            <li>
                <button onclick=toggleSubMenu(this) class="dropdown-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M222-200 80-342l56-56 85 85 170-170 56 57-225 226Zm0-320L80-662l56-56 85 85 170-170 56 57-225 226Zm298 240v-80h360v80H520Zm0-320v-80h360v80H520Z"/></svg>
                <span>Todo-Lists</span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>
                </button>
                <ul class="sub-menu">
                    <div>
                        <li>
                            <a href="#">Work</a>
                        </li>
                        <li>
                            <a href="#">Private</a>
                        </li>
                        <li>
                            <a href="#">Coding</a>
                        </li>
                        <li>
                            <a href="#">Gardening</a>
                        </li>
                        <li>
                            <a href="#">School</a>
                        </li>
                    </div>
                </ul>
            </li>
            <li>
                <a href="calendar.html">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"/></svg>
                    <span>Calendar</span>
                </a>
            </li>
            <li>
                <a href="profile.html">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </nav>
    <main>
        <h2>IV Bag Information</h2>
        <br>
        <div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Determine the circle color based on ivbag_level
            $ivbag_level = floatval($row['ivbag_level']);
            $backflow_id = floatval($row['backflow']);
            if ($ivbag_level > 50) {
                $bag_circle_color = "green";
            } elseif ($ivbag_level >= 15 && $ivbag_level <= 49) {
                $bag_circle_color = "yellow";
            } else {
                $bag_circle_color = "red";
            }
            if ($backflow_id == 1) {
                $bloodid_circle = "red";
            } else {
                $bloodid_circle = "green";
            }


            echo "<div class='bag-info'>";
            echo "<p>" . htmlspecialchars($row['name']) . "</p>";
            echo "<div class='bag-info-details'>";
            echo "<p><img src='icons/iv-bag.png' alt='blood backflow Icon' style='width: 24px; height: 24px;'> <br>" . " <svg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><circle cx='12' cy='12' r='10' stroke='black' stroke-width='2' fill='{$bag_circle_color}' /></svg> " . "</p>";
            echo "<p><img src='icons/blood.png' alt='blood backflow Icon' style='width: 24px; height: 24px;'> <br>" . " <svg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><circle cx='12' cy='12' r='10' stroke='black' stroke-width='2' fill='{$bloodid_circle}' /></svg> " . "</p>";
            echo "</div>";
            echo "<div>";
            echo "<p>" . htmlspecialchars($row['room']) . "</p>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No data found in the database.</p>";
    }
    ?>
</div>

    </main>
</body>
</html>
<?php
$conn->close();
?>