<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to home page if user is already logged in
    header("Location: home.php");
    exit();
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Simulating database query (replace with actual database connection)
    $db_user = "your_database_username";
    $db_pass = "your_database_password";
    $db_name = "your_database_name";

    $conn = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $result = $stmt->execute();

    if ($result && $stmt->rowCount() > 0) {
        // User authenticated successfully
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Redirect to home page
        header("Location: home.php");
        exit();
    }

    // Handle incorrect credentials
    $error_message = "Incorrect username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Arellano University</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="Arellano University Logo" class="logo">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Login</h2>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <div>
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember username</label>
            </div>
            <button type="submit">Log in</button>
        </form>
        <a href="forgot-password.php">Forgotten your username or password?</a>
        <?php if (isset($error_message)) { echo "<p class='error'>" . $error_message . "</p>"; } ?>
    </div>
</body>
</html>