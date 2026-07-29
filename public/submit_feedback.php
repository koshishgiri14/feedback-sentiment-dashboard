<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../scripts/db.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$rating = isset($data['rating']) ? intval($data['rating']) : null;
$comment = isset($data['comment']) ? trim($data['comment']) : '';

if ($rating === null || $rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Rating must be a number between 1 and 5']);
    exit;

}

if (empty($comment)) {
    http_response_code(400);
    echo json_encode(['error' => 'Comment cannot be empty']);
    exit;
}

$conn = get_db_connection();

$stmt = $conn->prepare('INSERT INTO feedback (rating, comment_text) VALUES (?, ?)');
$stmt->bind_param('is', $rating, $comment);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save feedback: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
