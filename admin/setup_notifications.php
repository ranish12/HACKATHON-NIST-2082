<?php
// Run this to add notifications table and user status to an existing Sambodhan database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sambodhan";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add is_banned column to users table
$sql = "ALTER TABLE users ADD COLUMN IF NOT EXISTS is_banned BOOLEAN DEFAULT 0";
if ($conn->query($sql) === TRUE) {
    echo "Column 'is_banned' added to users table or already exists.<br>";
} else {
    echo "Error adding is_banned column: " . $conn->error . "<br>";
}

// Create notifications table
$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'notifications' created or already exists.<br>";
} else {
    echo "Error creating notifications table: " . $conn->error . "<br>";
}

$conn->close();
echo "<br>Setup complete. You can now delete this file.";
?> 