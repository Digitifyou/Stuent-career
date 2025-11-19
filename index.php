<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Career Architect - Find Your Future Path</title>
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
                <a href="#home">Home</a>
                <a href="#features">Features</a>
                <a href="#testimonials">Testimonials</a>
                
                <?php if ($is_logged_in): ?>
                    <a href="tool.php">Dashboard</a>
                    <a href="logout.php" class="cta-button-nav">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-login-btn">Login</a>
                    <a href="register.php" class="cta-button-nav">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main id="home">
        <section class="hero">
            <div class="container hero-content">
                <div class="hero-text">
                    <h1>Find Your Future Career Path, Today.</h1>
                    <p class="subtitle">Stop guessing. Start planning. Our AI-powered tool builds a personalized career blueprint just for you, complete with actionable steps, recommended courses, and live job links.</p>
                    <div class="cta-buttons">
                        <?php if ($is_logged_in): ?>
                            <a href="tool.php" class="cta-button">Go to Your Dashboard</a>
                        <?php else: ?>
                            <a href="register.php" class="cta-button">Get Started for Free</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hero-graphic">
                    <img src="./successful-student.png" alt="successful-student">
                </div>
            </div>
        </section>
    </main>

    <section class="why-section" id="features">
        <div class="container">
            <div class="section-header">
                <h2>Why AI Career Architect</h2>
                <p>A short description of why this is the best platform to plan your career.</p>
            </div>
            
            <div class="steps-diagram">
                <div class="step">
                    <div class="step-circle">1</div>
                    <p>Input Your Profile</p>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-circle">2</div>
                    <p>Get AI Blueprint</p>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-circle">3</div>
                    <p>Start Your Journey</p>
                </div>
            </div>

            <div class="features-grid-template">
                
                
                <div class="feature-card-template feature-card-left">
                    <h3>AI-Powered Blueprint</h3>
                    <p>Get a structured, step-by-step career plan based on your unique skills, interests, and qualifications.</p>
                </div>
                
                <div class="feature-card-template feature-card-right">
                    <h3>Curated Courses</h3>
                    <p>We'll find the best YouTube courses to help you learn the exact skills you need for your dream job.</p>
                </div>

                <div class="feature-card-template feature-card-full">
                    <h3>Live Job Links</h3>
                    <p>Instantly search for your dream job in your preferred location on LinkedIn, Indeed, and Naukri.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="ai-prompt-section">
        <div class="container">
            <div class="section-header">
                <h2>Ask AI, Get Instant Help</h2>
                <p>Use our AI assistant to get answers to your career questions instantly.</p>
            </div>
            <div class="ai-prompt-box">
                <input type="text" placeholder="Ask Any Question">
                <?php if ($is_logged_in): ?>
                    <a href="tool.php" class="cta-button"><i class="fas fa-paper-plane"></i></a>
                <?php else: ?>  
                    <a href="register.php" class="cta-button"><i class="fas fa-paper-plane"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>What Our Learners Say</h2>
                <p>Hear directly from our users about their progress and experience.</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p class="quote">"This is an amazing platform. I've learned so much in such a short time. Highly recommended!"</p>
                    <div class="user-profile">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <div class="user-info">
                            <strong>Learner One</strong>
                            <div class="rating">★★★★★</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card primary">
                    <p class="quote">"The AI helper is a game-changer. It's like having a personal tutor 24/7. I finally understand what steps to take."</p>
                    <div class="user-profile">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <div class="user-info">
                            <strong>Learner Two</strong>
                            <div class="rating">★★★★★</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <p class="quote">"I love the goal-oriented paths. I'm preparing for a career change and the real-life job links are perfect."</p>
                    <div class="user-profile">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <div class="user-info">
                            <strong>Learner Three</strong>
                            <div class="rating">★★★★★</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-nav">
                <button>&larr;</button>
                <button>&rarr;</button>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">AI Career Architect</div>
                <div class="footer-links">
                    <a href="#home">Home</a>
                    <a href="#features">Features</a>
                    <a href="#testimonials">Testimonials</a>
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