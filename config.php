<?php
// config.php
// Store all your database credentials and API keys here.

// Database Configuration
define('DB_HOST', 'localhost'); 
define('DB_NAME', 'career_tool'); 
define('DB_USER', 'root'); 
define('DB_PASS', '');

// --- Hostinger Database Configuration ---
// define('DB_HOST', 'mysql.hostinger.com'); 
// define('DB_NAME', 'u230344840_careertool'); 
// define('DB_USER', 'u230344840_career'); 
// define('DB_PASS', 'Digitifyu@12');

// Gemini API Configuration
define('GEMINI_API_KEY', 'AIzaSyAHV-KoDAbMtfdDRrlQ5rWAVC7RDT_hXsY'); // <-- IMPORTANT: REPLACE THIS
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=' . GEMINI_API_KEY);


// YouTube API Configuration
define('YOUTUBE_API_KEY', 'AIzaSyDFtw5c4deLU5BehKkydTzPzjsnZFW0qcI'); // <-- IMPORTANT: REPLACE THIS
define('YOUTUBE_API_URL', 'https://www.googleapis.com/youtube/v3/search');
?>