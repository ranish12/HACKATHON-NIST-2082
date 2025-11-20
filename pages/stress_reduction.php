<?php
session_start();
include_once '../includes/navbar.php';
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

// Fetch all resources
$resources = $conn->query("SELECT * FROM stress_resources ORDER BY created_at DESC");

// Group resources by type
$grouped = ['video'=>[], 'audio'=>[], 'article'=>[], 'other'=>[]];
if ($resources) {
    while ($row = $resources->fetch_assoc()) {
        $grouped[$row['type']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Stress Reduction Resources | Sambodhan</title>
<style>
body { background: #e0f7fa; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; }
.container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); padding: 32px; }
.page-header { text-align: center; color: #00796b; margin-bottom: 30px; }
.section { margin-bottom: 36px; }
.section h2 { color: #009688; margin-bottom: 18px; }
.resource-list { list-style: none; padding: 0; }
.resource-item { background: #f8f9fa; border-left: 4px solid #009688; margin-bottom: 18px; padding: 18px 20px; border-radius: 0 8px 8px 0; }
.resource-item a { color: #00796b; font-weight: 600; text-decoration: none; }
.resource-item a:hover { text-decoration: underline; }
.resource-type-label { display: inline-block; background: #b2dfdb; color: #234; border-radius: 6px; padding: 2px 10px; font-size: 0.95em; margin-bottom: 8px; }
</style>
</head>
<body>
<div class="container">
    <h1 class="page-header">Stress Reduction Resources</h1>
    <p style="text-align:center;max-width:600px;margin:0 auto 30px auto;">Explore videos, audios, articles, and more to help you relax, manage stress, and improve your well-being.</p>
    <?php foreach ($grouped as $type => $list): ?>
        <?php if (count($list) > 0): ?>
        <div class="section">
            <h2><?php echo ucfirst($type); ?>s</h2>
            <ul class="resource-list">
                <?php foreach ($list as $res): ?>
                <li class="resource-item">
                    <div class="resource-type-label"><?php echo ucfirst($type); ?></div><br>
                    <span><?php echo htmlspecialchars($res['description']); ?></span><br>
                    <a href="<?php echo htmlspecialchars($res['link']); ?>" target="_blank">Open Resource</a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php if (array_sum(array_map('count', $grouped)) === 0): ?>
        <p style="text-align:center;color:#888;">No resources have been added yet. Please check back soon!</p>
    <?php endif; ?>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 