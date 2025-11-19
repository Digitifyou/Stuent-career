<?php
session_start();
require 'db.php';
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']); // <-- NEW
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($phone)) { // <-- UPDATED
        $error = "Username, Phone Number, and Password are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if username already exists
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Username already taken.";
            } else {
                // Create new user
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                // <-- UPDATED SQL QUERY -->
                $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, phone) VALUES (?, ?, ?)");
                $stmt->execute([$username, $password_hash, $phone]); // <-- UPDATED
                $success = "Registration successful! You can now <a href='login.php'>log in</a>.";
            }
        } catch (PDOException $e) {
            // Check for duplicate phone number if you have a unique constraint
            if ($e->errorInfo[1] == 1062) {
                 $error = "That phone number is already in use.";
            } else {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Career Architect - Register</title>
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
        <form action="register.php" method="POST" class="card">
            <h2>Create Account</h2>
            <p>Join AI Career Architect</p>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="input-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="cta-button">Register</button>
            <div class="form-link">
                Already have an account? <a href="login.php">Login here</a>
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