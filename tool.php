<?php
session_start();

// Protect this page: check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Clear any previous results
unset($_SESSION['career_result']);
unset($_SESSION['youtube_courses']);
unset($_SESSION['job_links']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Career Architect - Tool</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">AI Career Architect</a>
            <div class="nav-links">
                <a href="tool.php">Dashboard</a>
                <a href="logout.php" class="cta-button-nav">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
            
            <div class="blueprint-header">
                <h1>AI Career Architect</h1>
                <p class="subtitle">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Fill out the form to build your blueprint.</p>
            </div>

            <div class="layout-grid">
                <!-- Main Column (The Form) -->
                <div class="main-column">
                    <section class="report-section">
                        <form id="career-form">
                            <h2>Tell Us About Yourself</h2>

                            <div id="form-error" class="error-message" style="display: none;"></div>

                            <div class="form-row">
                                <div class="input-group icon-input">
                                    <label for="name"><i class="fas fa-user"></i> Name</label>
                                    <input type="text" id="name" name="name" placeholder="e.g., Jane Doe" required>
                                </div>
                                <div class="input-group icon-input">
                                    <label for="age"><i class="fas fa-birthday-cake"></i> Age</label>
                                    <input type="number" id="age" name="age" placeholder="e.g., 21" required>
                                </div>
                            </div>

                            <div class="input-group icon-input">
                                <label for="qualification"><i class="fas fa-graduation-cap"></i> Highest Qualification</label>
                                <input type="text" id="qualification" name="qualification" placeholder="e.g., B.Sc. Computer Science" required>
                            </div>
                            
                            <div class="input-group icon-input">
                                <label for="excites"><i class="fas fa-lightbulb"></i> What excites you?</label>
                                <input type="text" id="excites" name="excites" placeholder="e.g., Artificial Intelligence, UI/UX Design" required>
                            </div>
                            
                            <div class="input-group icon-input">
                                <label for="dream_job"><i class="fas fa-briefcase"></i> Your Dream Job Title</label>
                                <input type="text" id="dream_job" name="dream_job" placeholder="e.g., AI Engineer, Product Designer" required>
                            </div>

                            <div class="input-group icon-input">
                                <label for="location"><i class="fas fa-map-marker-alt"></i> Your Preferred Location</label>
                                <input type="text" id="location" name="location" placeholder="e.g., Chennai, or 'Remote'" required>
                            </div>
                            
                            <button type="submit" id="submit-button" class="cta-button">
                                Build My Blueprint
                            </button>
                            <div id="loading-indicator" class="loading" style="display: none;">
                                Building your blueprint...
                            </div>
                        </form>
                    </section>
                </div>

                <!-- Sidebar Column (Info Box) -->
                <div class="sidebar-column">
                    <section class="report-section info-box">
                        <h3><i class="fas fa-info-circle icon-blue"></i> Why We Ask</h3>
                        <p>We use these details to create a truly personalized report.</p>
                        
                        <strong>Qualifications</strong>
                        <p>Helps us suggest the right starting point, whether it's an internship or a senior role.</p>

                        <strong>What Excites You</strong>
                        <p>This is key! We match your passions to specific job roles and industries.</p>

                        <strong>Dream Job Title</strong>
                        <p>This gives us a clear target to build your step-by-step plan towards.</p>

                        <strong>Location</strong>
                        <p>Allows us to find relevant job links and market insights for your preferred area.</p>
                    </section>
                </div>

            </div> <!-- /layout-grid -->
        </div>
    </div>

    <!-- Footer -->
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

    <script src="script.js"></script>
</body>
</html>