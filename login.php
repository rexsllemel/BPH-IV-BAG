<?php
session_start();

$API_KEY = "AIzaSyCyZ87V_lupJ1zMjUstRD68wNwn8QXdCs4";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $postData = json_encode([
        "email" => $email,
        "password" => $password,
        "returnSecureToken" => true
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=$API_KEY",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        $error = "cURL error: $err";
    } else {
        $data = json_decode($response, true);
        if (isset($data['idToken'])) {
            $_SESSION['idToken'] = $data['idToken'];
            $_SESSION['email'] = $data['email'];
            header("Location: index.php");
            exit;
        } else {
            $error = $data['error']['message'] ?? "Unknown error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
       body {
            margin: 0;
            height: 100vh;
            background: url('./images/banner.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            font-family: Arial, sans-serif;
        }

        .glass-form {
            background: rgba(9, 107, 60, 0.02); /* greenish tint */
            border: 1px solid rgba(9, 107, 60, 0.24);
            border-radius: 16px;
            padding: 40px;
            width: 300px;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            box-shadow: 0 8px 32px 0 rgba(9, 107, 60, 0.4);
            color: white;
            margin-top: 50vh; /* form placed lower than center */
        }


        .glass-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .glass-form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .glass-form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            outline: none;
        }

        .glass-form button {
            width: 50%;
            padding: 10px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            background-color: #096b3c;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .glass-form button:hover {
            background-color: #0b844d;
        }

        .error {
            color: #ff6b6b;
            margin-bottom: 10px;
            text-align: center;
        }
        .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* margin-bottom: 5px; */
        }

        .logo-left, .logo-right {
            width: 72px;
            height: auto;
            margin: -10px;
        }
        .button-container {
            display: flex;
            justify-content: center;
        }

    </style>
</head>
<body>
    <form method="POST" class="glass-form">
        <div class="form-header">
            <img src="./images/logo200.png" class="logo-left" alt="Left Logo">
            <img src="./images/landi.png" class="logo-right" alt="Right Logo">
        </div>
        <h2>Welcome</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <div class="button-container">
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
