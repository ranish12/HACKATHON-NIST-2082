<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit();
}
include_once '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Sambodhan</title>
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
        .section {
            margin-bottom: 36px;
        }
        .section h2 {
            color: #009688;
            margin-bottom: 10px;
        }
        .section ul {
            margin: 0 0 0 18px;
            padding: 0;
        }
        .section ul li {
            margin-bottom: 8px;
            font-size: 1.05rem;
        }
        @media (max-width: 800px) {
            .admin-layout { flex-direction: column; }
            .sidebar { width: 100%; flex-direction: row; }
            .sidebar a { flex: 1; text-align: center; border-bottom: none; border-right: 1px solid #00796b; }
            .main-content { padding: 24px 8px; }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>
    </div>
    <?php include_once '../includes/footer.php'; ?>
</body>
</html> 