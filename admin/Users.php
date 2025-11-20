<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | Sambodhan</title>
<style>
body { background: #e0f7fa; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; }
.admin-layout { display: flex; min-height: 90vh; }
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
h2 { color: #009688; }
table { width: 100%; border-collapse: collapse; margin-bottom: 30px; table-layout: fixed; }
th, td { border: 1px solid #b2dfdb; padding: 8px; text-align: left; word-wrap: break-word; }
th { background: #e0f7fa; font-weight: 600; }
th:nth-child(1), td:nth-child(1) { width: 20%; } /* Username column */
th:nth-child(2), td:nth-child(2) { width: 25%; } /* Email column */
th:nth-child(3), td:nth-child(3) { width: 15%; } /* Status column */
th:nth-child(4), td:nth-child(4) { width: 15%; } /* Created column */
th:nth-child(5), td:nth-child(5) { width: 25%; } /* Actions column */
.btn-warn { background: #ff9800; }
.btn-warn:hover { background: #f57c00; }
.btn-ban { background: #f44336; }
.btn-ban:hover { background: #d32f2f; }
.btn-unban { background: #4CAF50; }
.btn-unban:hover { background: #388E3C; }
.actions-cell {
    white-space: nowrap;
}
.actions-cell a {
    text-decoration: none;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    margin-right: 5px;
    display: inline-block;
    font-size: 14px;
}
.status-banned {
    color: #f44336;
    font-weight: 600;
}
.status-active {
    color: #4CAF50;
    font-weight: 600;
}
.setup-notice {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}
</style>
</head>
<body>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
<?php
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

// Check if is_banned column exists
$check_column = $conn->query("SHOW COLUMNS FROM users LIKE 'is_banned'");
$has_ban_column = $check_column && $check_column->num_rows > 0;

// Check if is_counciler column exists
$check_counciler = $conn->query("SHOW COLUMNS FROM users LIKE 'is_counciler'");
$has_counciler_column = $check_counciler && $check_counciler->num_rows > 0;

if (!$has_ban_column) {
    echo '<div class="setup-notice">
        <strong>Setup Required:</strong> The ban functionality requires database updates. 
        Please run <a href="../edit.php" style="color: #009688;">edit.php</a> to add the required columns and tables.
    </div>';
}

if (!$has_counciler_column) {
    echo '<div class="setup-notice">
        <strong>Setup Required:</strong> The counselor feature requires database updates. 
        Please run <a href="../edit.php" style="color: #009688;">edit.php</a> to add the required columns and tables.
    </div>';
}

// Handle ban (only if column exists)
if (isset($_GET['ban']) && $has_ban_column) {
    $id = intval($_GET['ban']);
    $stmt = $conn->prepare("UPDATE users SET is_banned = 1 WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: Users.php'); exit();
}

// Handle unban (only if column exists)
if (isset($_GET['unban']) && $has_ban_column) {
    $id = intval($_GET['unban']);
    $stmt = $conn->prepare("UPDATE users SET is_banned = 0 WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: Users.php'); exit();
}

// Handle set/unset counselor (only if column exists)
if (isset($_GET['make_counciler']) && $has_counciler_column) {
    $id = intval($_GET['make_counciler']);
    $stmt = $conn->prepare("UPDATE users SET is_counciler = 1 WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: Users.php'); exit();
}
if (isset($_GET['remove_counciler']) && $has_counciler_column) {
    $id = intval($_GET['remove_counciler']);
    $stmt = $conn->prepare("UPDATE users SET is_counciler = 0 WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: Users.php'); exit();
}

// Get users with or without ban/counciler status
if ($has_ban_column && $has_counciler_column) {
    $users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
} else {
    $users = $conn->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
}
?>
<h2>Manage Users</h2>
<table>
<tr><th>Username</th><th>Email</th><th>Status</th><th>Created</th><th>Actions</th></tr>
<?php while($row = $users->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['username']); ?></td>
    <td><?php echo htmlspecialchars($row['email']); ?></td>
    <td class="<?php echo ($has_ban_column && $row['is_banned']) ? 'status-banned' : 'status-active'; ?>">
        <?php echo ($has_ban_column && $row['is_banned']) ? 'Banned' : 'Active'; ?>
    </td>
    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
    <td class="actions-cell">
        <a href="warn_user.php?id=<?php echo $row['id']; ?>" class="btn-warn">Warn</a>
        <?php if ($has_ban_column): ?>
            <?php if ($row['is_banned']): ?>
                <a href="Users.php?unban=<?php echo $row['id']; ?>" onclick="return confirm('Unban this user?');" class="btn-unban">Unban</a>
            <?php else: ?>
                <a href="Users.php?ban=<?php echo $row['id']; ?>" onclick="return confirm('Ban this user? They will not be able to access the system.');" class="btn-ban">Ban</a>
            <?php endif; ?>
        <?php else: ?>
            <span style="color: #999; font-size: 12px;">Ban feature not available</span>
        <?php endif; ?>
        <?php if ($has_counciler_column): ?>
            <?php if ($row['is_counciler']): ?>
                <a href="Users.php?remove_counciler=<?php echo $row['id']; ?>" onclick="return confirm('Remove counselor status from this user?');" style="background:#607d8b;">Unset Counselor</a>
            <?php else: ?>
                <a href="Users.php?make_counciler=<?php echo $row['id']; ?>" onclick="return confirm('Make this user a counselor?');" style="background:#3f51b5;">Set as Counselor</a>
            <?php endif; ?>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 