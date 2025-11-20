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
<title>Manage Stress Resources | Sambodhan</title>
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
th:nth-child(1), td:nth-child(1) { width: 30%; } /* Description */
th:nth-child(2), td:nth-child(2) { width: 12%; } /* Type */
th:nth-child(3), td:nth-child(3) { width: 18%; } /* Link */
th:nth-child(4), td:nth-child(4) { width: 20%; } /* Created */
th:nth-child(5), td:nth-child(5) { width: 20%; } /* Actions */
form { margin-bottom: 24px; }
input[type=text], input[type=url], select { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #b2dfdb; margin-bottom: 10px; box-sizing: border-box; }
button { background: #009688; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; margin-right: 8px; }
button:hover { background: #00796b; }
.btn-edit { background: #2196F3; }
.btn-edit:hover { background: #1976D2; }
.btn-delete { background: #f44336; }
.btn-delete:hover { background: #d32f2f; }
.actions-cell { white-space: nowrap; }
</style>
</head>
<body>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
<?php
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

// Handle add/edit
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$edit_data = null;
if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM stress_resources WHERE resource_id = ?");
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) $edit_data = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desc = trim($_POST['description']);
    $type = trim($_POST['type']);
    $link = trim($_POST['link']);
    if (isset($_POST['resource_id']) && $_POST['resource_id']) {
        // Edit
        $rid = intval($_POST['resource_id']);
        $stmt = $conn->prepare("UPDATE stress_resources SET description=?, type=?, link=? WHERE resource_id=?");
        $stmt->bind_param('sssi', $desc, $type, $link, $rid);
        $stmt->execute();
        $stmt->close();
    } else {
        // Add
        $stmt = $conn->prepare("INSERT INTO stress_resources (description, type, link) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $desc, $type, $link);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: stress_resources.php');
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $did = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM stress_resources WHERE resource_id = ?");
    $stmt->bind_param('i', $did);
    $stmt->execute();
    $stmt->close();
    header('Location: stress_resources.php');
    exit();
}

// Fetch all resources
$res = $conn->query("SELECT * FROM stress_resources ORDER BY created_at DESC");
?>
<h2>Manage Stress Reduction Resources</h2>
<div style="max-width:600px;margin-bottom:30px;">
    <form method="post" action="stress_resources.php">
        <input type="hidden" name="resource_id" value="<?php echo $edit_data ? $edit_data['resource_id'] : ''; ?>">
        <label>Description:<br>
            <input type="text" name="description" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['description']) : ''; ?>">
        </label><br>
        <label>Type:<br>
            <select name="type" required>
                <?php $types = ['video','audio','article','other']; foreach($types as $t): ?>
                    <option value="<?php echo $t; ?>" <?php if ($edit_data && $edit_data['type']==$t) echo 'selected'; ?>><?php echo ucfirst($t); ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Link:<br>
            <input type="url" name="link" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['link']) : ''; ?>">
        </label><br>
        <button type="submit"><?php echo $edit_data ? 'Update' : 'Add'; ?> Resource</button>
        <?php if ($edit_data): ?>
            <a href="stress_resources.php" style="margin-left:18px;color:#009688;">Cancel</a>
        <?php endif; ?>
    </form>
</div>
<table>
    <tr>
        <th>Description</th><th>Type</th><th>Link</th><th>Created</th><th>Actions</th>
    </tr>
    <?php while($row = $res->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['description']); ?></td>
        <td><?php echo ucfirst($row['type']); ?></td>
        <td><a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">Open</a></td>
        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
        <td class="actions-cell">
            <a href="stress_resources.php?edit=<?php echo $row['resource_id']; ?>" class="btn-edit" style="text-decoration: none; color: white; padding: 8px 16px; border-radius: 6px; display: inline-block;">Edit</a>
            <a href="stress_resources.php?delete=<?php echo $row['resource_id']; ?>" onclick="return confirm('Delete this resource?');" class="btn-delete" style="text-decoration: none; color: white; padding: 8px 16px; border-radius: 6px;">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 