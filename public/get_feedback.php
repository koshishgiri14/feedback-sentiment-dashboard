<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../scripts/db.php';

$conn = get_db_connection();

$result = $conn->query('SELECT id, rating, comment_text, sentiment_score, sentiment_label, date_submitted FROM feedback ORDER BY date_submitted ASC');

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);

$conn->close();