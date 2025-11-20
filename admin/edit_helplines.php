<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$helpline = null;
$message = '';

// Handle form submission
if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);
    $website = trim($_POST['website']);
    
    if ($name && $phone) {
        $stmt = $conn->prepare("UPDATE helplines SET name=?, phone=?, description=?, website=? WHERE id=?");
        $stmt->bind_param('ssssi', $name, $phone, $description, $website, $id);
        if ($stmt->execute()) {
            $message = '<div style="background: #4CAF50; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Helpline updated successfully!</div>';
        } else {
            $message = '<div style="background: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Error updating helpline!</div>';
        }
        $stmt->close();
    } else {
        $message = '<div style="background: #ff9800; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Please fill in all required fields!</div>';
    }
}

// Get helpline data
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM helplines WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $helpline = $result->fetch_assoc();
    $stmt->close();
    
    if (!$helpline) {
        header('Location: Helplines.php');
        exit();
    }
} else {
    header('Location: Helplines.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Helpline | Sambodhan</title>
<style>
body { 
    background: #e0f7fa; 
    font-family: 'Segoe UI', Arial, sans-serif; 
    margin: 0; 
}
.admin-layout { 
    display: flex; 
    min-height: 90vh; 
}
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
h2 { color: #009688; margin-bottom: 30px; }
.form-container {
    background: #f9f9f9;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    max-width: 800px;
}
.form-group {
    margin-bottom: 20px;
}
label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}
input[type=text], textarea {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #b2dfdb;
    box-sizing: border-box;
    font-size: 14px;
}
textarea {
    resize: vertical;
    min-height: 120px;
}
.btn {
    background: #009688;
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    margin-right: 10px;
}
.btn:hover {
    background: #00796b;
}
.btn-secondary {
    background: #757575;
}
.btn-secondary:hover {
    background: #616161;
}
.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #009688;
    text-decoration: none;
    font-weight: 600;
}
.back-link:hover {
    text-decoration: underline;
}
.required {
    color: #f44336;
}
</style>
</head>
<body>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <a href="Helplines.php" class="back-link">← Back to Helplines</a>
        <h2>Edit Helpline</h2>
        
        <?php echo $message; ?>
        
        <div class="form-container">
            <form method="post">
                <div class="form-group">
                    <label for="name">Helpline Name: <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($helpline['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number: <span class="required">*</span></label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($helpline['phone']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="website">Website:</label>
                    <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($helpline['website']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($helpline['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="update" class="btn">Update Helpline</button>
                    <a href="Helplines.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 