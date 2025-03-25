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
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body style="padding-left: 0;">
<?php
if(isset($message)){
    foreach($message as $msg){
        echo '
        <div class="alert alert-danger" role="alert">
            <span>' . htmlspecialchars($msg) . '</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }
}
?>
<section class="form-container">
    <form class="register" action="" method="post" enctype="multipart/form-data">
        <h3 class="mb-3">Register New</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Your Name <span>*</span></label>
                <input type="text" name="name" placeholder="Enter Your Name" maxlength="50" required class="form-control">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Your Email <span>*</span></label>
                <input type="email" name="email" placeholder="Enter Your Email" maxlength="20" required class="form-control">
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Your Password <span>*</span></label>
                <input type="password" name="pass" placeholder="Enter Your Password" maxlength="20" required class="form-control">
            </div>
            <div class="col-md-6">
                <label for="cpass" class="form-label">Confirm Password <span>*</span></label>
                <input type="password" name="cpass" placeholder="Confirm Your Password" maxlength="20" required class="form-control">
            </div>
            <div class="col-md-12">
                <label for="image" class="form-label">Select Pic <span>*</span></label>
                <input type="file" name="image" accept="image/*" required class="form-control">
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <p class="link">Already have an account? <a href="login.php" class="text-decoration-none">Login Now</a></p>
        </div>
        <div class="col-md-12 mt-3">
            <button type="submit" name="submit" class="btn btn-dark w-100">Register Now</button>
        </div>
    </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>