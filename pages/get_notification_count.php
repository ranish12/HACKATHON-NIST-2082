<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) {
    echo json_encode(['count' => 0]);
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Check if notifications table exists
$check_table = $conn->query("SHOW TABLES LIKE 'notifications'");
if ($check_table && $check_table->num_rows > 0) {
    // Get unread notification count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $stmt->close();
} else {
    $count = 0;
}

$conn->close();
echo json_encode(['count' => intval($count)]);
?> 