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
<title>Manage Helplines | Sambodhan</title>
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
th:nth-child(1), td:nth-child(1) { width: 20%; } /* Name column */
th:nth-child(2), td:nth-child(2) { width: 15%; } /* Phone column */
th:nth-child(3), td:nth-child(3) { width: 20%; } /* Website column */
th:nth-child(4), td:nth-child(4) { width: 25%; } /* Description column */
th:nth-child(5), td:nth-child(5) { width: 20%; } /* Actions column */
form { margin-bottom: 24px; }
input[type=text], textarea { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #b2dfdb; margin-bottom: 10px; box-sizing: border-box; }
button { background: #009688; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; margin-right: 8px; }
button:hover { background: #00796b; }
.btn-edit { background: #2196F3; }
.btn-edit:hover { background: #1976D2; }
.btn-delete { background: #f44336; }
.btn-delete:hover { background: #d32f2f; }
.content-preview {
    max-height: 60px;
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
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);
    $website = trim($_POST['website']);
    if ($name && $phone) {
        $stmt = $conn->prepare("INSERT INTO helplines (name, phone, description, website) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $phone, $description, $website);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: Helplines.php'); exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM helplines WHERE id=$id");
    header('Location: Helplines.php'); exit();
}

$helplines = $conn->query("SELECT * FROM helplines ORDER BY id DESC");
?>
<h2>Manage Helplines</h2>
<form method="post">
    <input type="text" name="name" placeholder="Helpline Name" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="text" name="website" placeholder="Website (optional)">
    <textarea name="description" placeholder="Description" rows="3"></textarea>
    <button type="submit" name="add">Add Helpline</button>
</form>

<table>
<tr><th>Name</th><th>Phone</th><th>Website</th><th>Description</th><th>Actions</th></tr>
<?php while($row = $helplines->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['phone']); ?></td>
    <td><?php echo htmlspecialchars($row['website']); ?></td>
    <td class="content-preview"><?php echo htmlspecialchars($row['description']); ?></td>
    <td class="actions-cell">
        <a href="edit_helplines.php?id=<?php echo $row['id']; ?>" class="btn-edit" style="text-decoration: none; color: white; padding: 10px 20px; border-radius: 6px; display: inline-block;">Edit</a>
        <a href="Helplines.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this helpline?');" class="btn-delete" style="text-decoration: none; color: white; padding: 10px 20px; border-radius: 6px;">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 