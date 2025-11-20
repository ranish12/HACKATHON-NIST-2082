<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$journal = null;
$message = '';

// Handle form submission
if (isset($_POST['update'])) {
    $entry = trim($_POST['entry']);
    
    if ($entry) {
        $stmt = $conn->prepare("UPDATE journal_entries SET entry=? WHERE id=?");
        $stmt->bind_param('si', $entry, $id);
        if ($stmt->execute()) {
            $message = '<div style="background: #4CAF50; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Journal entry updated successfully!</div>';
        } else {
            $message = '<div style="background: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Error updating journal entry!</div>';
        }
        $stmt->close();
    } else {
        $message = '<div style="background: #ff9800; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Please fill in the journal entry!</div>';
    }
}

// Get journal data
if ($id > 0) {
    $stmt = $conn->prepare("SELECT j.*, u.username FROM journal_entries j LEFT JOIN users u ON j.user_id=u.id WHERE j.id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $journal = $result->fetch_assoc();
    $stmt->close();
    
    if (!$journal) {
        header('Location: Journals.php');
        exit();
    }
} else {
    header('Location: Journals.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Journal Entry | Sambodhan</title>
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
.info-row {
    background: #e8f5e8;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    border-left: 4px solid #4CAF50;
}
.info-row strong {
    color: #2E7D32;
}
textarea {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #b2dfdb;
    box-sizing: border-box;
    font-size: 14px;
    resize: vertical;
    min-height: 200px;
    font-family: inherit;
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
        <a href="Journals.php" class="back-link">← Back to Journal Entries</a>
        <h2>Edit Journal Entry</h2>
        
        <?php echo $message; ?>
        
        <div class="form-container">
            <div class="info-row">
                <strong>User:</strong> <?php echo htmlspecialchars($journal['username'] ?? 'Anonymous'); ?><br>
                <strong>Created:</strong> <?php echo htmlspecialchars($journal['created_at']); ?><br>
                <strong>Status:</strong> <?php echo $journal['is_approved'] ? 'Approved' : 'Pending Approval'; ?>
            </div>
            
            <form method="post">
                <div class="form-group">
                    <label for="entry">Journal Entry: <span class="required">*</span></label>
                    <textarea id="entry" name="entry" required><?php echo htmlspecialchars($journal['entry']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="update" class="btn">Update Journal Entry</button>
                    <a href="Journals.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 