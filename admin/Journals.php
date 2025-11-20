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
<title>Manage Journals | Sambodhan</title>
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
th:nth-child(1), td:nth-child(1) { width: 15%; } /* User column */
th:nth-child(2), td:nth-child(2) { width: 40%; } /* Entry column */
th:nth-child(3), td:nth-child(3) { width: 10%; } /* Approved column */
th:nth-child(4), td:nth-child(4) { width: 15%; } /* Created column */
th:nth-child(5), td:nth-child(5) { width: 20%; } /* Actions column */
.btn-edit { background: #2196F3; }
.btn-edit:hover { background: #1976D2; }
.btn-delete { background: #f44336; }
.btn-delete:hover { background: #d32f2f; }
.btn-approve { background: #4CAF50; }
.btn-approve:hover { background: #388E3C; }
.content-preview {
    max-height: 80px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
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
</style>
</head>
<body>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
<?php
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

// Approve
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE journal_entries SET is_approved=1 WHERE id=$id");
    header('Location: Journals.php'); exit();
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM journal_entries WHERE id=$id");
    header('Location: Journals.php'); exit();
}

$journals = $conn->query("SELECT j.*, u.username FROM journal_entries j LEFT JOIN users u ON j.user_id=u.id ORDER BY j.created_at DESC");
?>
<h2>Manage Journal Entries</h2>
<table>
<tr><th>User</th><th>Entry</th><th>Approved</th><th>Created</th><th>Actions</th></tr>
<?php while($row = $journals->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['username'] ?? 'Anonymous'); ?></td>
    <td class="content-preview"><?php echo htmlspecialchars($row['entry']); ?></td>
    <td><?php echo $row['is_approved'] ? 'Yes' : 'No'; ?></td>
    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
    <td class="actions-cell">
        <a href="edit_journals.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
        <?php if (!$row['is_approved']): ?>
            <a href="Journals.php?approve=<?php echo $row['id']; ?>" class="btn-approve">Approve</a>
        <?php endif; ?>
        <a href="Journals.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this journal entry?');" class="btn-delete">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 