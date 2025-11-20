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
<title>Manage Inspiring Stories | Sambodhan</title>
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
.header {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}
.content-table {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
th {
    background-color: #f4f4f4;
}
.story-content {
    max-width: 400px;
    white-space: pre-wrap;
    word-wrap: break-word;
}
.status {
    padding: 5px 10px;
    border-radius: 15px;
    color: white;
    font-weight: bold;
    text-transform: capitalize;
}
.status.pending { background-color: #f0ad4e; }
.status.approved { background-color: #5cb85c; }
.actions {
    display: flex;
    flex-direction: row;
    gap: 5px;
}
.actions a {
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
}
.btn-approve { background-color: #5cb85c; }
.btn-delete { background-color: #d9534f; }
</style>
</head>
<body>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
<?php
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
// Check if table exists
$table_check = $conn->query("SHOW TABLES LIKE 'inspiring_stories'");
$table_exists = $table_check->num_rows > 0;

$result = null;
if ($table_exists) {
    // Handle actions
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($_GET['action'] === 'approve') {
            $conn->query("UPDATE inspiring_stories SET status = 'approved' WHERE id = $id");
        } elseif ($_GET['action'] === 'delete') {
            $conn->query("DELETE FROM inspiring_stories WHERE id = $id");
        }
        header("Location: stories.php");
        exit();
    }
    // Fetch all stories
    $result = $conn->query("SELECT id, username, title, story, status, created_at FROM inspiring_stories ORDER BY created_at DESC");
}
?>
        <div class="header">
            <h1>Manage Inspiring Stories</h1>
        </div>

        <?php if (!$table_exists): ?>
            <div class="setup-notice" style="padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px; text-align: center;">
                <p><strong>Database Error:</strong> The 'inspiring_stories' table was not found.</p>
                <p>Please <a href="../edit.php" target="_blank">run the setup script</a> to create the necessary database table.</p>
            </div>
        <?php else: ?>
            <div class="content-table">
                <table>
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Title</th>
                            <th>Story</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td class="story-content"><?php echo htmlspecialchars($row['story']); ?></td>
                                    <td>
                                        <span class="status <?php echo $row['status']; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                                    <td class="actions">
                                        <?php if ($row['status'] === 'pending'): ?>
                                            <a href="stories.php?action=approve&id=<?php echo $row['id']; ?>" class="btn-approve">Approve</a>
                                        <?php endif; ?>
                                        <a href="stories.php?action=delete&id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this story?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No stories submitted yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 