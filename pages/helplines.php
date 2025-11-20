<?php
session_start();
include_once '../includes/navbar.php';
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');
$helplines = $conn->query("SELECT * FROM helplines ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Helplines | Sambodhan</title>
<style>
body { background: #e0f7fa; font-family: 'Segoe UI', Arial, sans-serif; }
.container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); padding: 32px 24px; }
h2 { color: #009688; text-align: center; }
table { width: 100%; border-collapse: collapse; margin-top: 24px; }
th, td { border: 1px solid #b2dfdb; padding: 8px; text-align: left; }
th { background: #e0f7fa; }
</style>
</head>
<body>
<div class="container">
<h2>Helplines</h2>
<table>
<tr><th>Name</th><th>Phone</th><th>Website</th><th>Description</th></tr>
<?php while($row = $helplines->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['phone']); ?></td>
    <td><?php if ($row['website']) { echo '<a href="'.htmlspecialchars($row['website']).'" target="_blank">'.htmlspecialchars($row['website']).'</a>'; } ?></td>
    <td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 