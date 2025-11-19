<?php
session_start();
require 'config.php';
require 'db.php'; // <-- Make sure db.php is included

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'You must be logged in.']);
    exit;
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// Get and sanitize form data
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
$qualification = filter_input(INPUT_POST, 'qualification', FILTER_SANITIZE_STRING);
$excites = filter_input(INPUT_POST, 'excites', FILTER_SANITIZE_STRING);
$dream_job = filter_input(INPUT_POST, 'dream_job', FILTER_SANITIZE_STRING);
$location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);

if (empty($name) || empty($age) || empty($qualification) || empty($excites) || empty($dream_job) || empty($location)) {
     header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

// --- NEW: Store user inputs in the database ---
try {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("INSERT INTO career_submissions (user_id, name, age, qualification, excites, dream_job, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $name, $age, $qualification, $excites, $dream_job, $location]);
} catch (PDOException $e) {
    // Log this error but don't block the API call
    // In a real app, you'd log this to a file
    error_log("Database error storing submission: " . $e->getMessage());
}
// --- END: Store user inputs ---


// --- Construct the Prompt for Gemini API ---
// This system prompt defines the AI's role.
$system_prompt = "You are 'AI Career Architect', a helpful and encouraging career counselor.
You will be given a student's details and you MUST respond ONLY with a JSON object matching the requested schema.
Do not add any extra text or markdown formatting outside of the JSON structure.
Be positive, professional, and make the content actionable and inspiring.";

// The user query provides the details.
$user_query = "
Here are my details:
- Name: $name
- Age: $age
- Highest Qualification: $qualification
- What excites me: $excites
- My Dream Job Title: $dream_job
- Preferred Location: $location

Please provide my personalized career blueprint in the requested JSON format.
";

// --- NEW: Define the JSON Schema for the response ---
$json_schema = [
    'type' => 'OBJECT',
    'properties' => [
        'personalizedBlueprint' => [
            'type' => 'OBJECT',
            'properties' => [
                'title' => ['type' => 'STRING'],
                'intro' => ['type' => 'STRING']
            ]
        ],
        'careerPath' => [
            'type' => 'OBJECT',
            'properties' => [
                'title' => ['type' => 'STRING'],
                'description' => ['type' => 'STRING']
            ]
        ],
        'whyFit' => [
            'type' => 'OBJECT',
            'properties' => [
                'title' => ['type' => 'STRING'],
                'explanation' => ['type' => 'STRING']
            ]
        ],
        'actionableSteps' => [
            'type' => 'OBJECT',
            'properties' => [
                'title' => ['type' => 'STRING'],
                'steps' => [
                    'type' => 'ARRAY',
                    'items' => [
                        'type' => 'OBJECT',
                        'properties' => [
                            'title' => ['type' => 'STRING'],
                            'description' => ['type' => 'STRING']
                        ]
                    ]
                ]
            ]
        ],
        'alternativePath' => [
            'type' => 'OBJECT',
            'properties' => [
                'title' => ['type' => 'STRING'],
                'description' => ['type' => 'STRING']
            ]
        ],
        'locationAdvice' => [
            'type' => 'OBJECT',
            'properties' => [
                'title' => ['type' => 'STRING'],
                'advice' => ['type' => 'STRING']
            ]
        ]
    ]
];

// --- Prepare the Gemini API Payload ---
$payload = [
    'contents' => [
        [
            'parts' => [
                ['text' => $user_query]
            ]
        ]
    ],
    'systemInstruction' => [
        'parts' => [
            ['text' => $system_prompt]
        ]
    ],
    // --- NEW: Add Generation Config to enforce JSON ---
    'generationConfig' => [
        'responseMimeType' => 'application/json',
        'responseSchema' => $json_schema
    ]
];

$jsonData = json_encode($payload);

// --- Use cURL to call the Gemini API ---
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, GEMINI_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
]);
// Add exponential backoff/retry logic here in a real application
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// --- Process the Response ---
if ($http_code == 200) {
    $result = json_decode($response, true);
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        
        // The 'text' field now contains a JSON string. We need to decode it.
        $generated_json_string = $result['candidates'][0]['content']['parts'][0]['text'];
        $structured_data = json_decode($generated_json_string, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // The API didn't return valid JSON
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'API returned invalid JSON.', 'api_response' => $generated_json_string]);
            exit;
        }

        // --- NEW: Call YouTube API ---
        $youtube_query = $dream_job . " " . $excites . " tutorial for beginners";
        $yt_params = [
            'part' => 'snippet',
            'q' => $youtube_query,
            'type' => 'video',
            'videoEmbeddable' => 'true',
            'maxResults' => 2, // Get 2 videos
            'key' => YOUTUBE_API_KEY
        ];
        
        $yt_url = YOUTUBE_API_URL . '?' . http_build_query($yt_params);
        
        $yt_ch = curl_init();
        curl_setopt($yt_ch, CURLOPT_URL, $yt_url);
        curl_setopt($yt_ch, CURLOPT_RETURNTRANSFER, 1);
        // Note: No POST fields, this is a GET request
        $yt_response = curl_exec($yt_ch);
        $yt_http_code = curl_getinfo($yt_ch, CURLINFO_HTTP_CODE);
        curl_close($yt_ch);
        
        $videos = [];
        if ($yt_http_code == 200) {
            $yt_result = json_decode($yt_response, true);
            if (isset($yt_result['items'])) {
                foreach ($yt_result['items'] as $item) {
                    $videos[] = [
                        'id' => $item['id']['videoId'],
                        'title' => $item['snippet']['title']
                    ];
                }
            }
        }
        // --- End of YouTube Call ---

        // Store the successful structured data in the session
        $_SESSION['career_result'] = $structured_data; // <-- Store the array, not Markdown
        $_SESSION['youtube_courses'] = $videos; // Store video data
        
        // --- NEW: Generate Live Job Links ---
        $job_query = urlencode($dream_job);
        $loc_query = urlencode($location);
        
        $_SESSION['job_links'] = [
            'linkedin' => "https://www.linkedin.com/jobs/search/?keywords={$job_query}&location={$loc_query}",
            'indeed' => "https://www.indeed.com/jobs?q={$job_query}&l={$loc_query}",
            'naukri' => "https://www.naukri.com/{$job_query}-jobs-in-{$loc_query}"
        ];
        // --- End of Job Links ---

        // Send a success response back to the JavaScript
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
        
    } else {
        // API returned 200 but content was not as expected
        header('HTTP/1.1 500 Internal Server Error');
        $error_details = isset($result['error']['message']) ? $result['error']['message'] : 'Invalid API response structure.';
        echo json_encode(['error' => $error_details, 'api_response' => $result]);
        exit;
    }
} else {
    // API call failed (e.g., 400, 401, 500)
    header('HTTP/1.1 ' . $http_code);
    echo json_encode(['error' => 'API call failed.', 'http_code' => $http_code, 'api_response' => $response]);
    exit;
}
?>

