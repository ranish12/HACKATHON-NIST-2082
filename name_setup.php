<?php
// setup.php: Run this ONCE to set up the Sambodhan database and tables.

$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS sambodhan";
if ($conn->query($sql) === TRUE) {
    echo "Database created or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db("sambodhan");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    is_banned BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created.<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create mood_checkin table
$sql = "CREATE TABLE IF NOT EXISTS mood_checkin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    mood VARCHAR(50),
    sleep_quality VARCHAR(50),
    talk_to_friends VARCHAR(50),
    custom_message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'mood_checkin' created.<br>";
} else {
    echo "Error creating mood_checkin table: " . $conn->error . "<br>";
}

// Create articles table
$sql = "CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'articles' created.<br>";
} else {
    echo "Error creating articles table: " . $conn->error . "<br>";
}

// Create helplines table
$sql = "CREATE TABLE IF NOT EXISTS helplines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(30),
    description TEXT,
    website VARCHAR(255)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'helplines' created.<br>";
} else {
    echo "Error creating helplines table: " . $conn->error . "<br>";
}

// Create journal_entries table
$sql = "CREATE TABLE IF NOT EXISTS journal_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    entry TEXT,
    is_private BOOLEAN DEFAULT 1,
    is_approved BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'journal_entries' created.<br>";
} else {
    echo "Error creating journal_entries table: " . $conn->error . "<br>";
}

// Create contacts table
$sql = "CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'contacts' created.<br>";
} else {
    echo "Error creating contacts table: " . $conn->error . "<br>";
}

// Create journal_likes table
$sql = "CREATE TABLE IF NOT EXISTS journal_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    journal_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (journal_id) REFERENCES journal_entries(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'journal_likes' created.<br>";
} else {
    echo "Error creating journal_likes table: " . $conn->error . "<br>";
}

// Create journal_comments table
$sql = "CREATE TABLE IF NOT EXISTS journal_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    journal_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (journal_id) REFERENCES journal_entries(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'journal_comments' created.<br>";
} else {
    echo "Error creating journal_comments table: " . $conn->error . "<br>";
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
    echo "Table 'notifications' created.<br>";
} else {
    echo "Error creating notifications table: " . $conn->error . "<br>";
}

$conn->close();

echo "<br>Setup complete. You can now delete or secure this file.";
?> 