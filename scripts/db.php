<?php
$DB_HOST = 'localhost';
$DB_NAME = 'feedback_dashboard';
$DB_USER = 'root';
$DB_PASS = '';

function get_db_connection() {
    global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS;

    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
    }
    return $conn;
}