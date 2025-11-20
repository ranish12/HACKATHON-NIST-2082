<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = null;
$message = '';

// Handle form submission
if (isset($_POST['send_warning'])) {
    $title = trim($_POST['title']);
    $warning_message = trim($_POST['message']);
    
    if ($title && $warning_message) {
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $id, $title, $warning_message);
        if ($stmt->execute()) {
            $message = '<div style="background: #4CAF50; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Warning sent successfully!</div>';
        } else {
            $message = '<div style="background: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Error sending warning!</div>';
        }
        $stmt->close();
    } else {
        $message = '<div style="background: #ff9800; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Please fill in all fields!</div>';
    }
}

// Get user data
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
        header('Location: Users.php');
        exit();
    }
} else {
    header('Location: Users.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Warn User | Sambodhan</title>
<style>
body { 
    background: #e0f7fa; 
    font-family: 'Segoe UI', Arial, sans-serif; 
    margin: 0; 
}
.admin-layout { 
    display: flex; 
    min-height: 90vh; 
}
.sidebar {
    width: 220px;
    background: #009688;
    color: #fff;
    padding: 0;
    display: flex;
    flex-direction: column;
}
.sidebar h3 {
    text-align: center;
    margin: 24px 0 16px 0;
    font-size: 1.3rem;
    letter-spacing: 1px;
}
.sidebar a {
    color: #fff;
    text-decoration: none;
    padding: 16px 24px;
    font-size: 1.1rem;
    border-bottom: 1px solid #00796b;
    transition: background 0.2s;
}
.sidebar a:hover, .sidebar a.active {
    background: #00796b;
}
.main-content {
    flex: 1;
    padding: 40px 32px;
    background: #fff;
}
h2 { color: #009688; margin-bottom: 30px; }
.form-container {
    background: #f9f9f9;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    max-width: 800px;
}
.form-group {
    margin-bottom: 20px;
}
label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}
input[type=text], textarea {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #b2dfdb;
    box-sizing: border-box;
    font-size: 14px;
}
textarea {
    resize: vertical;
    min-height: 120px;
}
.btn {
    background: #009688;
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    margin-right: 10px;
}
.btn:hover {
    background: #00796b;
}
.btn-secondary {
    background: #757575;
}
.btn-secondary:hover {
    background: #616161;
}
.btn-warning {
    background: #ff9800;
}
.btn-warning:hover {
    background: #f57c00;
}
.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #009688;
    text-decoration: none;
    font-weight: 600;
}
.back-link:hover {
    text-decoration: underline;
}
.required {
    color: #f44336;
}
.user-info {
    background: #fff3cd;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    border-left: 4px solid #ffc107;
}
.user-info strong {
    color: #856404;
}
</style>
</head>
<body>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <a href="Users.php" class="back-link">← Back to Users</a>
        <h2>Send Warning to User</h2>
        
        <?php echo $message; ?>
        
        <div class="form-container">
            <div class="user-info">
                <strong>User:</strong> <?php echo htmlspecialchars($user['username']); ?><br>
                <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?><br>
                <strong>Status:</strong> <?php echo isset($user['is_banned']) && $user['is_banned'] ? 'Banned' : 'Active'; ?>
            </div>
            
            <form method="post" id="warningForm">
                <div class="form-group">
                    <label for="title">Warning Title: <span class="required">*</span></label>
                    <input type="text" id="title" name="title" placeholder="e.g., Community Guidelines Violation" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Warning Message: <span class="required">*</span></label>
                    <textarea id="message" name="message" placeholder="Please explain the reason for this warning and what the user should do to avoid further action..." required></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="send_warning" class="btn btn-warning">Send Warning</button>
                    <a href="Users.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Function to update notification count
function updateNotificationCount() {
    fetch('/Sambodhan/pages/get_notification_count.php')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('notification-count');
            if (countElement) {
                if (data.count > 0) {
                    countElement.textContent = data.count;
                    countElement.style.display = 'flex';
                } else {
                    countElement.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error fetching notification count:', error));
}

// Update notification count when form is submitted successfully
document.getElementById('warningForm').addEventListener('submit', function() {
    // Update count after a short delay to allow the form to submit
    setTimeout(updateNotificationCount, 1000);
});

// Update count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateNotificationCount();
});
</script>

<?php include_once '../includes/footer.php'; ?>
</body>
</html> 