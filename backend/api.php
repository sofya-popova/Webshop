<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin

require_once 'config.php';

$apiKey = $_SERVER['HTTP_API_KEY'] ?? ''; // Get API key from header
if ($apiKey !== API_KEY) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// URL of the e-commerce site to scrape
$url = 'https://www.okidoki.ee';

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo json_encode(['error' => 'Failed to fetch website']);
    exit;
}

// Parse HTML response
$dom = new DOMDocument();
@$dom->loadHTML($response); // Suppress errors due to malformed HTML

// Initialize XPath to navigate the DOM
$xpath = new DOMXPath($dom);

// Find all <li> elements that are children of <ul id="hc">
$categoryItems = $xpath->query('//ul[@id="hc"]/li');

// Prepare an array to store the categories
$categories = [];

foreach ($categoryItems as $item) {
    $category = trim($item->getAttribute('class')); // Extract the class name as the category name
    if (!empty($category)) {
        $categories[] = $category;
    }
}

// Return categories as JSON
echo json_encode($categories);
