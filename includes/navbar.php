<nav style="background:#009688; padding:16px 0; text-align:center; position: relative;">
    <a href="/Sambodhan/index.php" style="color:#fff; text-decoration:none; font-size:1.3rem; font-weight:bold; margin:0 20px;">Sambodhan</a>
    <a href="/Sambodhan/pages/mood_checkin.php" style="color:#fff; text-decoration:none; margin:0 15px;">Mood Check-In</a>
    <a href="/Sambodhan/pages/articles.php" style="color:#fff; text-decoration:none; margin:0 15px;">Articles</a>
    <a href="/Sambodhan/pages/helplines.php" style="color:#fff; text-decoration:none; margin:0 15px;">Helplines</a>
    <a href="/Sambodhan/pages/journal.php" style="color:#fff; text-decoration:none; margin:0 15px;">Journal</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/Sambodhan/pages/notifications.php" style="color:#fff; text-decoration:none; margin:0 15px; position: relative;">
            <span style="font-size: 1.2em;">🔔</span>
            <span id="notification-count" style="position: absolute; top: -8px; right: -8px; background: #f44336; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 11px; display: flex; align-items: center; justify-content: center; font-weight: bold; display: none;">0</span>
        </a>
        <a href="/Sambodhan/logout.php" style="color:#fff; text-decoration:none; margin:0 15px;">Logout</a>
    <?php else: ?>
        <a href="/Sambodhan/login.php" style="color:#fff; text-decoration:none; margin:0 15px;">Login</a>
    <?php endif; ?>
</nav>

<?php if (isset($_SESSION['user_id'])): ?>
<script>
// Function to update notification count#
function updateNotificationCount() {
    fetch('/Sambodhan/pages/get_notification_count.php')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('notification-count');
            if (data.count > 0) {
                countElement.textContent = data.count;
                countElement.style.display = 'flex';
            } else {
                countElement.style.display = 'none';
            }
        })
        .catch(error => console.error('Error fetching notification count:', error));
}

// Update count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateNotificationCount();
});

// Update count every 30 seconds
setInterval(updateNotificationCount, 30000);
</script>
<?php endif; ?> 