<?php
// We can remove the debug lines now
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';
$error = '';

// If user is already logged in, redirect to the tool
if (isset($_SESSION['user_id'])) {
    header("Location: tool.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Password is correct!
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                header("Location: tool.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Career Architect - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">AI Career Architect</a>
            <div class="nav-links">
                <a href="index.php#home">Home</a>
                <a href="index.php#features">Features</a>
                <a href="index.php#testimonials">Testimonials</a>
                <a href="login.php" class="nav-login-btn">Login</a>
                <a href="register.php" class="cta-button-nav">Get Started</a>
            </div>
        </div>
    </nav>

    <div class="form-container">
        <form action="login.php" method="POST" class="card">
            <h2>Login</h2>
            <p>Welcome back to AI Career Architect</p>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="cta-button">Login</button>
            <div class="form-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </form>
    </div>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">AI Career Architect</div>
                <div class="footer-links">
                    <a href="index.php#home">Home</a>
                    <a href="index.php#features">Features</a>
                    <a href="index.php#testimonials">Testimonials</a>
                </div>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date("Y"); ?> AI Career Architect. All Rights Reserved.</p>
                <p><a href="#">Terms & Conditions</a> &bull; <a href="#">Privacy Policy</a></p>
            </div>
        </div>
    </footer>

</body>
</html>