<?php
session_start();

// Replace with your Firebase Web API Key
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
<html>
<head><title>Login</title></head>
<body>
<h2>Firebase Login</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
