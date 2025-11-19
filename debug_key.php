<?php
// debug_key.php
// This script checks EXACTLY what is being loaded from your .env file.

require 'vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Attempt to get the key
    $apiKey = $_ENV['GEMINI_API_KEY'] ?? null;

    echo "--- Key Debugger ---\n\n";

    if ($apiKey === null) {
        echo "RESULT: ❌ FAILED to load the key. The GEMINI_API_KEY variable was not found.\n";
    } else {
        echo "RESULT: ✅ Key loaded successfully.\n\n";
        // The single quotes around the key are important to see hidden spaces
        echo "Loaded Key: '" . $apiKey . "'\n\n";
        echo "Key Length: " . strlen($apiKey) . " characters\n";
    }

} catch (Exception $e) {
    echo "RESULT: ❌ An error occurred while trying to read the .env file.\n";
    echo "Error Message: " . $e->getMessage() . "\n";
}
?>