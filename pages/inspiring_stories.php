<?php
session_start();
include_once '../includes/navbar.php';
$conn = new mysqli('localhost', 'root', '', 'sambodhan');

// Handle story submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $title = $conn->real_escape_string($_POST['title']);
    $story = $conn->real_escape_string($_POST['story']);

    if (!empty($title) && !empty($story)) {
        $stmt = $conn->prepare("INSERT INTO inspiring_stories (user_id, username, title, story) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $username, $title, $story);
        if ($stmt->execute()) {
            $message = "Your story has been submitted for approval. Thank you for sharing!";
        } else {
            $error = "Sorry, there was an error submitting your story. Please try again.";
        }
        $stmt->close();
    } else {
        $error = "Please fill out both the title and the story fields.";
    }
}

// Fetch approved stories
$stories_result = $conn->query("SELECT username, title, story, created_at FROM inspiring_stories WHERE status = 'approved' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspiring Stories | Sambodhan</title>
    <style>
        body { background: #f5f8ff; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; }
        .page-header { text-align: center; margin-bottom: 40px; color: #00796b; }
        .story-submission, .stories-list { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); margin-bottom: 30px; }
        h2 { color: #00796b; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
        .btn { background: #009688; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
        .btn:hover { background: #00796b; }
        .message { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .story-card { background: #f8f9fa; border-left: 4px solid #009688; padding: 20px; margin-bottom: 20px; border-radius: 0 8px 8px 0; }
        .story-card h3 { margin: 0 0 10px 0; color: #2c3e50; }
        .story-card p { margin: 0 0 15px 0; white-space: pre-wrap; }
        .story-meta { font-size: 0.9rem; color: #777; }
        .login-prompt { text-align: center; padding: 20px; background: #fff3cd; border-radius: 8px; }
    </style>
</head>
<body>
<div class="container">
    <h1 class="page-header">Inspiring Stories of Hope & Resilience</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="story-submission">
            <h2>Share Your Story</h2>
            <p>Your story can be a beacon of hope for someone else. Share your journey with mental health, recovery, or resilience. All submissions are reviewed before publishing.</p>
            
            <?php if (isset($message)): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="inspiring_stories.php" method="POST">
                <div class="form-group">
                    <label for="title">Story Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="story">Your Story</label>
                    <textarea id="story" name="story" rows="8" required></textarea>
                </div>
                <button type="submit" class="btn">Submit for Approval</button>
            </form>
        </div>
    <?php else: ?>
        <div class="login-prompt">
            <p>You must be <a href="../login.php">logged in</a> to share your story.</p>
        </div>
    <?php endif; ?>

    <div class="stories-list">
        <h2>Community Stories</h2>
        <?php if ($stories_result && $stories_result->num_rows > 0): ?>
            <?php while ($row = $stories_result->fetch_assoc()): ?>
                <div class="story-card">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row['story'])); ?></p>
                    <div class="story-meta">
                        By <strong><?php echo htmlspecialchars($row['username']); ?></strong> on <?php echo date('F j, Y', strtotime($row['created_at'])); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No stories have been shared yet. Be the first to share yours!</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
</body>
</html> 