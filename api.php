<?php
// api.php

require 'vendor/autoload.php';

// The incorrect 'use' statement has been removed.
// We will call the Gemini client directly.

header('Content-Type: application/json');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'];

if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'Gemini API key is not configured.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit();
}

$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$degree = $_POST['degree'] ?? '';
$pass_out_year = $_POST['pass_out_year'] ?? '';
$location = $_POST['location'] ?? '';
$interests = $_POST['interests'] ?? '';
$strengths = $_POST['strengths'] ?? '';
$weaknesses = $_POST['weaknesses'] ?? '';
$dreamJob = $_POST['dream_job'] ?? '';
$resume = $_POST['resume'] ?? '';

if (empty($name) || empty($dreamJob) || empty($location) || empty($resume) || empty($degree)) {
    http_response_code(400);
    echo json_encode(['error' => 'Please fill out all required fields.']);
    exit();
}

$prompt = <<<PROMPT
You are an elite career coach and expert recruiter based in India with 15 years of experience. Your task is to create a personalized action plan to help the candidate land their dream job.

**Candidate's Profile:**
- **Name:** $name
- **Age:** $age
- **Degree:** $degree
- **Year of Pass Out:** $pass_out_year
- **Target Location:** $location
- **Desired Job Title:** $dreamJob
- **Self-Reported Areas of Interest:** $interests
- **Self-Reported Strengths:** $strengths
- **Self-Reported Weaknesses:** $weaknesses
PROMPT;


try {
    // This call now works correctly without the incorrect 'use' statement.
    $response = Gemini::client($apiKey)
        ->geminiPro()
        ->generateContent($prompt);

    $plan = $response->text();

    echo json_encode(['plan' => $plan]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to communicate with Gemini API: ' . $e->getMessage()]);
}
?>