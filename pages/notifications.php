<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

include_once '../includes/navbar.php';
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

$user_id = intval($_SESSION['user_id']);

// Mark notification as read
if (isset($_GET['mark_read'])) {
    $notification_id = intval($_GET['mark_read']);
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $notification_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: notifications.php');
    exit();
}

// Mark all notifications as read
if (isset($_GET['mark_all_read'])) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: notifications.php');
    exit();
}

// Get user's notifications
$notifications = $conn->query("SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC");

// Get unread count
$unread_count = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE user_id = $user_id AND is_read = 0")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Notifications | Sambodhan</title>
<style>
body { 
    background: #e0f7fa; 
    font-family: 'Segoe UI', Arial, sans-serif; 
    margin: 0; 
}
.container { 
    max-width: 800px; 
    margin: 40px auto; 
    background: #fff; 
    border-radius: 14px; 
    box-shadow: 0 2px 10px rgba(0,0,0,0.07); 
    padding: 32px 24px; 
}
h2 { 
    color: #009688; 
    text-align: center; 
    margin-bottom: 30px;
}
.notification {
    background: #f9f9f9;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    border-left: 4px solid #009688;
    position: relative;
}
.notification.unread {
    background: #e8f5e8;
    border-left-color: #4CAF50;
}
.notification-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 1.1rem;
}
.notification-message {
    color: #666;
    line-height: 1.5;
    margin-bottom: 10px;
}
.notification-date {
    color: #999;
    font-size: 0.9rem;
}
.notification-actions {
    position: absolute;
    top: 15px;
    right: 15px;
}
.mark-read-btn {
    background: #009688;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
}
.mark-read-btn:hover {
    background: #00796b;
}
.header-actions {
    text-align: right;
    margin-bottom: 20px;
}
.mark-all-read-btn {
    background: #2196F3;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}
.mark-all-read-btn:hover {
    background: #1976D2;
}
.no-notifications {
    text-align: center;
    color: #666;
    font-style: italic;
    padding: 40px;
}
.unread-badge {
    background: #f44336;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 0.7rem;
    margin-left: 5px;
}
</style>
</head>
<body>
<div class="container">
    <h2>Notifications</h2>
    
    <?php if ($unread_count > 0): ?>
    <div class="header-actions">
        <a href="notifications.php?mark_all_read=1" class="mark-all-read-btn" onclick="markAllAsRead()">Mark All as Read</a>
    </div>
    <?php endif; ?>
    
    <?php if ($notifications->num_rows > 0): ?>
        <?php while($notification = $notifications->fetch_assoc()): ?>
            <div class="notification <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                <div class="notification-title">
                    <?php echo htmlspecialchars($notification['title']); ?>
                    <?php if (!$notification['is_read']): ?>
                        <span class="unread-badge">New</span>
                    <?php endif; ?>
                </div>
                <div class="notification-message">
                    <?php echo nl2br(htmlspecialchars($notification['message'])); ?>
                </div>
                <div class="notification-date">
                    <?php echo htmlspecialchars($notification['created_at']); ?>
                </div>
                <?php if (!$notification['is_read']): ?>
                    <div class="notification-actions">
                        <a href="notifications.php?mark_read=<?php echo $notification['id']; ?>" class="mark-read-btn" onclick="markAsRead(<?php echo $notification['id']; ?>)">Mark as Read</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-notifications">
            <p>No notifications yet.</p>
        </div>
    <?php endif; ?>
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

// Function to mark notification as read with AJAX
function markAsRead(notificationId) {
    fetch(`notifications.php?mark_read=${notificationId}`)
        .then(() => {
            updateNotificationCount();
        })
        .catch(error => console.error('Error marking as read:', error));
}

// Function to mark all notifications as read with AJAX
function markAllAsRead() {
    fetch('notifications.php?mark_all_read=1')
        .then(() => {
            updateNotificationCount();
        })
        .catch(error => console.error('Error marking all as read:', error));
}

// Update count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateNotificationCount();
});
</script>

<?php include_once '../includes/footer.php'; ?>
</body>
</html> 