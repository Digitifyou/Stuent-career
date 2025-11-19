<?php
// test_api.php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'];

echo "Attempting to connect to Gemini API with the latest model...\n\n";

if (!$apiKey) {
    die("ERROR: Gemini API key not found in .env file.\n");
}

$testPrompt = "Hello! In one sentence, what is the purpose of a career coach?";

try {
    // --- TYPO FIXED ON THIS LINE ---
    $response = Gemini::client($apiKey)
        ->generativeModel('gemini-1.5-pro-latest')
        ->generateContent($testPrompt);

    echo "✅ SUCCESS! \n\n";
    echo "API Response:\n";
    echo "-----------------\n";
    echo $response->text();
    echo "\n-----------------\n";

} catch (Exception $e) {
    echo "❌ ERROR! \n\n";
    echo "Failed to communicate with Gemini API: " . $e->getMessage();
    echo "\n";
}
?>