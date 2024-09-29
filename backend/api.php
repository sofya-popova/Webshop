<?php
// api.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin

require_once 'config.php';

$apiKey = $_SERVER['HTTP_API_KEY'] ?? ''; // Get API key from header
if ($apiKey !== API_KEY) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// URL of the e-commerce site to scrape
$url = 'https://okidoki.ee';

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Parse HTML response
$dom = new DOMDocument();
@$dom->loadHTML($response); // Suppress errors due to malformed HTML

// Extract categories
$categories = [];
$links = $dom->getElementsByTagName('a'); // Adjust this selector based on the actual HTML structure

foreach ($links as $link) {
    $category = trim($link->textContent);
    if (!empty($category) && !in_array($category, $categories)) {
        $categories[] = $category; // Collect unique categories
    }
}

// Return categories as JSON
echo json_encode($categories);
