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
<title>Manage Articles | Sambodhan</title>
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
th:nth-child(1), td:nth-child(1) { width: 20%; } /* Title column */
th:nth-child(2), td:nth-child(2) { width: 25%; } /* Content column - further reduced */
th:nth-child(3), td:nth-child(3) { width: 20%; } /* Actions column */
form { margin-bottom: 24px; }
input[type=text], textarea { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #b2dfdb; margin-bottom: 10px; box-sizing: border-box; }
button { background: #009688; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; margin-right: 8px; }
button:hover { background: #00796b; }
.btn-edit { background: #2196F3; }
.btn-edit:hover { background: #1976D2; }
.btn-delete { background: #f44336; }
.btn-delete:hover { background: #d32f2f; }

.content-preview {
    max-height: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.actions-cell {
    white-space: nowrap;
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

// Handle add
if (isset($_POST['add'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO articles (title, content) VALUES (?, ?)");
        $stmt->bind_param('ss', $title, $content);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: Articles.php'); exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM articles WHERE id=$id");
    header('Location: Articles.php'); exit();
}

$articles = $conn->query("SELECT * FROM articles ORDER BY created_at DESC");
?>
<h2>Manage Articles</h2>
<form method="post">
    <input type="text" name="title" placeholder="Article Title" required>
    <textarea name="content" placeholder="Article Content" rows="4" required></textarea>
    <button type="submit" name="add">Add Article</button>
</form>

<table>
<tr><th>Title</th><th>Content</th><th>Actions</th></tr>
<?php while($row = $articles->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td class="content-preview"><?php echo htmlspecialchars($row['content']); ?></td>
    <td class="actions-cell">
        <a href="edit_articles.php?id=<?php echo $row['id']; ?>" class="btn-edit" style="text-decoration: none; color: white; padding: 10px 20px; border-radius: 6px; display: inline-block;">Edit</a>
        <a href="Articles.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this article?');" class="btn-delete" style="text-decoration: none; color: white; padding: 10px 20px; border-radius: 6px;">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 