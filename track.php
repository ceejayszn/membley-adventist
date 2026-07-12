<?php
// track.php
require_once 'includes/db.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Read raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

$page = trim($input['page'] ?? '');
$type = trim($input['type'] ?? '');
$value = intval($input['value'] ?? 0);

if (empty($page) || !in_array($type, ['view', 'click', 'time'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid parameters']);
    exit;
}

try {
    // 1. Ensure the page entry exists
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO analytics (page, views, clicks, time_spent) VALUES (:page, 0, 0, 0)");
    $stmt->execute([':page' => $page]);

    // 2. Increment the specified metric
    if ($type === 'view') {
        $stmt = $pdo->prepare("UPDATE analytics SET views = views + 1 WHERE page = :page");
        $stmt->execute([':page' => $page]);
    } elseif ($type === 'click') {
        $stmt = $pdo->prepare("UPDATE analytics SET clicks = clicks + 1 WHERE page = :page");
        $stmt->execute([':page' => $page]);
    } elseif ($type === 'time' && $value > 0) {
        $stmt = $pdo->prepare("UPDATE analytics SET time_spent = time_spent + :value WHERE page = :page");
        $stmt->execute([':page' => $page, ':value' => $value]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
