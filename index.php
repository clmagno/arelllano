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
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['password']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $remember_email = isset($_POST['remember_email']) ? true : false;

    // Prepare secure query for users table
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if($row !== false){
        // Login successful - User found
        setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
        header('location:home.php');
        exit;
    } else {
        // Try tutor login
        $stmt = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
        $stmt->execute([$email, $pass]);
        $tutor_row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($tutor_row !== false){
            // Tutor found and password is correct
            setcookie('tutor_id', $tutor_row['id'], time() + 60*60*24*30, '/');
            header('location:admin/dashboard.php');
            exit;
        } else {
            $message = 'Incorrect email or password';
        }
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
                <!-- <div class="mb-3 form-check">
                    <input type="checkbox" name="remember_email" id="remember_email" class="form-check-input">
                    <label class="form-check-label" for="remember_email">Remember me</label>
                </div> -->
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-dark w-100">Log in</button>
                </div>
            </form>
            <style>
    .cookie-note {
        font-size: 0.875em;
        font-style: italic;
        color: #6c757d;
    }
</style>

<div class="text-center mt-2">
    <p class="cookie-note">Cookies must be enabled in your browser.</p>
</div>
            <div class="text-center mt-2">
                <span>Don't have an account?</span> <a href="register.php" class="fw-bold text-decoration-none">Register Now</a>
            </div>
        </div>
    </div>
</body>
</html>
