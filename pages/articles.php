<?php
session_start();
include_once '../includes/navbar.php';
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');
$articles = $conn->query("SELECT * FROM articles ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Articles | Sambodhan</title>
<style>
body { background: #e0f7fa; font-family: 'Segoe UI', Arial, sans-serif; }
.container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); padding: 32px 24px; }
h2 { color: #009688; text-align: center; }
.article { margin-bottom: 32px; padding-bottom: 18px; border-bottom: 1px solid #b2dfdb; }
.article h3 { color: #00796b; margin-bottom: 8px; }
.article p { color: #234; }
</style>
</head>
<body>
<div class="container">
<h2>Articles</h2>
<?php while($row = $articles->fetch_assoc()): ?>
<div class="article">
    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
</div>
<?php endwhile; ?>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 