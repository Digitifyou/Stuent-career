<?php
session_start();
require 'config.php'; // For navbar

// Check if user is logged in for the navbar
$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['username'] : '';

// Check if the result is in the session
if (!isset($_SESSION['career_result'])) {
    // If no result, redirect back to the tool page
    header('Location: tool.php');
    exit;
}

// --- Get the structured data from the session ---
$result_data = $_SESSION['career_result'];
$youtube_courses = isset($_SESSION['youtube_courses']) ? $_SESSION['youtube_courses'] : [];
$job_links = isset($_SESSION['job_links']) ? $_SESSION['job_links'] : [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Career Blueprint</title>
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
            <?php if ($is_logged_in): ?>
                <div class="nav-links">
                    <a href="tool.php">Dashboard</a>
                    <a href="logout.php" class="cta-button-nav">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Result Content -->
    <div class="page-content">
        <div class="container">

            <!-- Blueprint Header -->
            <?php if (isset($result_data['personalizedBlueprint'])): ?>
                <div class="blueprint-header">
                    <h1><?php echo htmlspecialchars($result_data['personalizedBlueprint']['title']); ?></h1>
                    <p class="subtitle"><?php echo htmlspecialchars($result_data['personalizedBlueprint']['intro']); ?></p>
                </div>
            <?php endif; ?>

            <!-- Main Content Grid -->
            <div class="layout-grid">
                
                <!-- Main Column -->
                <div class="main-column">
                    <?php if (isset($result_data['careerPath'])): ?>
                        <section class="report-section">
                            <h3><i class="fas fa-route icon-blue"></i> <?php echo htmlspecialchars($result_data['careerPath']['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($result_data['careerPath']['description'])); ?></p>
                        </section>
                    <?php endif; ?>

                    <?php if (isset($result_data['whyFit'])): ?>
                        <section class="report-section">
                            <h3><i class="fas fa-check-circle icon-green"></i> <?php echo htmlspecialchars($result_data['whyFit']['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($result_data['whyFit']['explanation'])); ?></p>
                        </section>
                    <?php endif; ?>

                    <?php if (isset($result_data['actionableSteps'])): ?>
                        <section class="report-section">
                            <h3><i class="fas fa-tasks icon-purple"></i> <?php echo htmlspecialchars($result_data['actionableSteps']['title']); ?></h3>
                            <ul class="steps-list">
                                <?php foreach ($result_data['actionableSteps']['steps'] as $step): ?>
                                    <li>
                                        <!-- This div is necessary for the card styling -->
                                        <div>
                                            <strong><?php echo htmlspecialchars($step['title']); ?></strong>
                                            <p><?php echo nl2br(htmlspecialchars($step['description'])); ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                </div>

                <!-- Sidebar Column -->
                <div class="sidebar-column">
                    <div class="action-buttons-container">
                        <a href="download.php" class="cta-button download-button"><i class="fas fa-file-pdf"></i> Download as PDF</a>
                        <a href="tool.php" class="cta-button secondary-button"><i class="fas fa-plus"></i> Build New Blueprint</a>
                    </div>

                    <?php if (isset($result_data['alternativePath'])): ?>
                        <section class="report-section">
                            <h3><i class="fas fa-random icon-orange"></i> <?php echo htmlspecialchars($result_data['alternativePath']['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($result_data['alternativePath']['description'])); ?></p>
                        </section>
                    <?php endif; ?>

                    <?php if (isset($result_data['locationAdvice'])): ?>
                        <section class="report-section">
                            <h3><i class="fas fa-map-marker-alt icon-red"></i> <?php echo htmlspecialchars($result_data['locationAdvice']['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($result_data['locationAdvice']['advice'])); ?></p>
                        </section>
                    <?php endif; ?>
                </div>

            </div> <!-- /layout-grid -->

            <!-- --- YouTube Video Section --- -->
            <?php if (!empty($youtube_courses)): ?>
                <section class="report-section full-width-section">
                    <h2 class="section-heading">Recommended Courses</h2>
                    <div class="video-container">
                        <?php foreach ($youtube_courses as $video): ?>
                            <div class="video-wrapper">
                                <iframe 
                                    src="https://www.youtube.com/embed/<?php echo htmlspecialchars($video['id']); ?>" 
                                    title="<?php echo htmlspecialchars($video['title']); ?>" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
            <!-- --- End of YouTube Section --- -->

            <!-- --- Live Job Links Section --- -->
            <?php if (!empty($job_links)): ?>
                <section class="job-links-section full-width-section">
                    <h2 class="section-heading">Live Job Postings</h2>
                    <p class="job-links-intro">See who's hiring right now for roles like yours.</p>
                    <div class="job-links-container">
                        <a href="<?php echo htmlspecialchars($job_links['linkedin']); ?>" target="_blank" class="job-link linkedin">
                            <i class="fab fa-linkedin"></i> Search on LinkedIn
                        </a>
                        <a href="<?php echo htmlspecialchars($job_links['indeed']); ?>" target="_blank" class="job-link indeed">
                            <i class="fas fa-briefcase"></i> Search on Indeed
                        </a>
                        <a href="<?php echo htmlspecialchars($job_links['naukri']); ?>" target="_blank" class="job-link naukri">
                            <i class="fas fa-building"></i> Search on Naukri
                        </a>
                    </div>
                </section>
            <?php endif; ?>
            <!-- --- End of Job Links Section --- -->

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

</body>
</html>