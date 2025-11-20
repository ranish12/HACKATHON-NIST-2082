<?php
session_start();
include_once '../includes/navbar.php';
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

if (!isset($_SESSION['user_id'])) {
    echo '<div style="max-width:600px;margin:40px auto;padding:30px;background:#fff3cd;border-radius:10px;text-align:center;">You must be <a href="../login.php">logged in</a> to chat with a counselor.</div>';
    include_once '../includes/footer.php';
    exit();
}

$user_id = $_SESSION['user_id'];
$is_counciler = false;
// Check if current user is a counselor
$res = $conn->query("SELECT is_counciler FROM users WHERE id = $user_id");
if ($res && $row = $res->fetch_assoc()) {
    $is_counciler = $row['is_counciler'] == 1;
}

// Get all counselors
$counselors = $conn->query("SELECT id, username FROM users WHERE is_counciler = 1");

// Determine chat partner
$chat_with = null;
if ($is_counciler) {
    // Counselors see all users who have messaged them
    $users = $conn->query("SELECT DISTINCT u.id, u.username FROM council_messages m JOIN users u ON m.user_id = u.id WHERE m.counselor_id = $user_id");
    if (isset($_GET['user'])) {
        $chat_with = intval($_GET['user']);
    } elseif ($users && $u = $users->fetch_assoc()) {
        $chat_with = $u['id'];
    }
} else {
    // Users select a counselor
    if (isset($_POST['counselor_id'])) {
        $chat_with = intval($_POST['counselor_id']);
        $_SESSION['counselor_id'] = $chat_with;
    } elseif (isset($_SESSION['counselor_id'])) {
        $chat_with = intval($_SESSION['counselor_id']);
    }
}

// Handle message send
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && $chat_with) {
    $msg = trim($_POST['message']);
    if ($msg !== '') {
        if ($is_counciler) {
            // Counselor replying to user
            $stmt = $conn->prepare("INSERT INTO council_messages (user_id, counselor_id, sender, message) VALUES (?, ?, 'counselor', ?)");
            $stmt->bind_param('iis', $chat_with, $user_id, $msg);
        } else {
            // User sending to counselor
            $stmt = $conn->prepare("INSERT INTO council_messages (user_id, counselor_id, sender, message) VALUES (?, ?, 'user', ?)");
            $stmt->bind_param('iis', $user_id, $chat_with, $msg);
        }
        $stmt->execute();
        $stmt->close();
    }
    header("Location: council_chat.php" . ($is_counciler ? "?user=$chat_with" : ''));
    exit();
}

// Fetch chat history
$messages = [];
if ($chat_with) {
    if ($is_counciler) {
        $q = $conn->prepare("SELECT * FROM council_messages WHERE user_id = ? AND counselor_id = ? ORDER BY created_at ASC");
        $q->bind_param('ii', $chat_with, $user_id);
    } else {
        $q = $conn->prepare("SELECT * FROM council_messages WHERE user_id = ? AND counselor_id = ? ORDER BY created_at ASC");
        $q->bind_param('ii', $user_id, $chat_with);
    }
    $q->execute();
    $res = $q->get_result();
    while ($row = $res->fetch_assoc()) $messages[] = $row;
    $q->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Counselor Chat | Sambodhan</title>
<style>
body { background: #e0f7fa; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; }
.container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); padding: 32px; }
.page-header { text-align: center; color: #00796b; margin-bottom: 30px; }
select, textarea, input[type=submit] { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #b2dfdb; margin-bottom: 15px; font-size: 1rem; }
input[type=submit] { background: #009688; color: #fff; border: none; font-weight: 600; cursor: pointer; transition: background 0.2s; }
input[type=submit]:hover { background: #00796b; }
.chat-box { background: #f8f9fa; border-radius: 8px; padding: 18px; min-height: 180px; max-height: 320px; overflow-y: auto; margin-bottom: 18px; }
.msg { margin-bottom: 14px; }
.msg.user { text-align: right; }
.msg.counselor { text-align: left; }
.msg .bubble { display: inline-block; padding: 10px 16px; border-radius: 16px; max-width: 70%; font-size: 1rem; }
.msg.user .bubble { background: #b2dfdb; color: #234; }
.msg.counselor .bubble { background: #e0f7fa; color: #234; border: 1px solid #b2dfdb; }
.msg .meta { font-size: 0.85em; color: #888; margin-top: 2px; }
.counselor-list, .user-list { margin-bottom: 20px; }
.counselor-list label, .user-list label { font-weight: 600; }
</style>
</head>
<body>
<div class="container">
    <h2 class="page-header">Chat with a Counselor</h2>
    <?php if ($is_counciler): ?>
        <div class="user-list">
            <form method="get" action="council_chat.php">
                <label for="user">Select User:</label>
                <select name="user" id="user" onchange="this.form.submit()">
                    <?php $users = $conn->query("SELECT DISTINCT u.id, u.username FROM council_messages m JOIN users u ON m.user_id = u.id WHERE m.counselor_id = $user_id");
                    while ($u = $users->fetch_assoc()): ?>
                        <option value="<?php echo $u['id']; ?>" <?php if ($chat_with == $u['id']) echo 'selected'; ?>><?php echo htmlspecialchars($u['username']); ?></option>
                    <?php endwhile; ?>
                </select>
            </form>
        </div>
    <?php elseif (!$chat_with): ?>
        <div class="counselor-list">
            <form method="post" action="council_chat.php">
                <label for="counselor_id">Select a Counselor:</label>
                <select name="counselor_id" id="counselor_id" required>
                    <option value="">-- Choose Counselor --</option>
                    <?php while ($c = $counselors->fetch_assoc()): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['username']); ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="submit" value="Start Chat">
            </form>
        </div>
    <?php endif; ?>

    <?php if ($chat_with): ?>
        <div class="chat-box" id="chat-box">
            <?php if (empty($messages)): ?>
                <p style="color:#888;">No messages yet. Start the conversation!</p>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="msg <?php echo $msg['sender']; ?>">
                        <div class="bubble"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                        <div class="meta"><?php echo ($msg['sender'] == 'user' ? 'You' : 'Counselor'); ?> &middot; <?php echo date('M j, H:i', strtotime($msg['created_at'])); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <form method="post" action="council_chat.php<?php echo $is_counciler ? '?user=' . $chat_with : ''; ?>">
            <textarea name="message" rows="3" placeholder="Type your message..." required></textarea>
            <input type="submit" value="Send">
        </form>
    <?php endif; ?>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 