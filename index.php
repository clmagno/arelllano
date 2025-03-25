<?php
// Start session for user management
session_start();

// Include database connection
include 'components/connect.php';

// Initialize variables
$email = $password = $message = '';
$remember_email = false;

// Check for remember email cookie
if(isset($_COOKIE['email'])){
    $email = $_COOKIE['email'];
}

// Handle form submission
if(isset($_POST['submit'])){
    // Input validation and sanitization
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $remember_email = isset($_POST['remember_email']) ? true : false;

    // Prepare secure query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        
        // Set remember email cookie if checked
        if($remember_email) {
            setcookie('email', $email, time() + 60*60*24*30, '/');
        }
        
        header('location:home.php');
        exit;
    } else {
        $message = 'Incorrect email or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg" style="width: 400px;">
            <div class="text-center">
                <img src="logo.png" alt="University Logo" class="mb-3" style="width: 100px;">
            </div>
            <form action="" method="post">
                <?php if($message): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember_email" id="remember_email" class="form-check-input">
                    <label class="form-check-label" for="remember_email">Remember me</label>
                </div>
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-dark w-100">Log in</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="#">Forgot your password?</a>
            </div>
            <div class="text-center mt-2">
                <span>Don't have an account?</span> <a href="register.php" class="fw-bold text-decoration-none">Register Now</a>
            </div>
        </div>
    </div>
</body>
</html>
