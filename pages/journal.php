<?php
session_start();
include_once '../includes/navbar.php';
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die('DB error');

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

// Handle new journal entry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entry'])) {
    $entry = trim($_POST['entry']);
    $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    if ($entry && $user_id) {
        $stmt = $conn->prepare("INSERT INTO journal_entries (user_id, entry, is_private, is_approved) VALUES (?, ?, ?, 0)");
        $stmt->bind_param('isi', $user_id, $entry, $is_anonymous);
        $stmt->execute();
        $stmt->close();
        $msg = 'Your journal entry has been submitted for review.';
    }
}
// Handle like/unlike
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_journal_id']) && $user_id) {
    $jid = intval($_POST['like_journal_id']);
    $res = $conn->query("SELECT id FROM journal_likes WHERE journal_id=$jid AND user_id=$user_id");
    if ($res->num_rows > 0) {
        $conn->query("DELETE FROM journal_likes WHERE journal_id=$jid AND user_id=$user_id");
    } else {
        $conn->query("INSERT INTO journal_likes (journal_id, user_id) VALUES ($jid, $user_id)");
    }
}
// Handle new comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_journal_id']) && $user_id) {
    $jid = intval($_POST['comment_journal_id']);
    $comment = trim($_POST['comment']);
    if ($comment) {
        $stmt = $conn->prepare("INSERT INTO journal_comments (journal_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param('iis', $jid, $user_id, $comment);
        $stmt->execute();
        $stmt->close();
    }
}
// Public, approved journals
$public = $conn->query("SELECT j.*, u.username FROM journal_entries j LEFT JOIN users u ON j.user_id=u.id WHERE j.is_approved=1 ORDER BY j.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Journal | Sambodhan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
<style>
body {
    min-height: 100vh;
    margin: 0;
    background: linear-gradient(135deg, #b2dfdb 0%, #e0f7fa 100%);
    font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
}
.journal-container {
    max-width: 700px;
    margin: 40px auto 30px auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 6px 32px rgba(0,0,0,0.13);
    padding: 38px 32px 32px 32px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.journal-logo {
    width: 60px;
    height: 60px;
    background: #e0f7fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    font-size: 2.2rem;
    color: #009688;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}
.journal-title {
    font-size: 1.7rem;
    font-weight: 700;
    color: #00796b;
    margin-bottom: 6px;
    letter-spacing: 1px;
    text-align: center;
}
.journal-desc {
    color: #555;
    font-size: 1.05rem;
    margin-bottom: 22px;
    text-align: center;
}
.journal-write-card {
    width: 100%;
    background: #f7fafc;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    padding: 18px 12px 10px 12px;
    margin-bottom: 28px;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    position: relative;
}
.floating-label {
    position: relative;
    margin-bottom: 18px;
}
.floating-label textarea {
    width: 100%;
    border: none;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    padding: 22px 14px 12px 14px;
    font-size: 1.08rem;
    min-height: 80px;
    resize: vertical;
    transition: box-shadow 0.2s;
}
.floating-label textarea:focus {
    outline: none;
    box-shadow: 0 2px 12px #b2dfdb;
}
.floating-label label {
    position: absolute;
    left: 18px;
    top: 18px;
    color: #888;
    font-size: 1.08rem;
    pointer-events: none;
    background: #fff;
    padding: 0 4px;
    border-radius: 4px;
    transition: 0.18s;
}
.floating-label textarea:focus + label,
.floating-label textarea:not(:placeholder-shown) + label {
    top: -12px;
    left: 10px;
    font-size: 0.98rem;
    color: #009688;
    background: #f7fafc;
}
.toggle-anon {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    margin-top: 2px;
}
.toggle-switch {
    position: relative;
    width: 38px;
    height: 22px;
    display: inline-block;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background: #b2dfdb;
    border-radius: 22px;
    transition: background 0.2s;
}
.toggle-switch input:checked + .toggle-slider {
    background: #009688;
}
.toggle-slider:before {
    content: '';
    position: absolute;
    left: 3px;
    top: 3px;
    width: 16px;
    height: 16px;
    background: #fff;
    border-radius: 50%;
    transition: transform 0.2s;
}
.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(16px);
}
.write-btn {
    background: #009688;
    color: #fff;
    border: none;
    border-radius: 24px;
    font-size: 1.13rem;
    font-weight: 600;
    padding: 12px 0;
    margin-top: 6px;
    width: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: background 0.18s;
}
.write-btn:hover {
    background: #00796b;
}
.success {
    color: #388e3c;
    background: #e8f5e9;
    border-radius: 8px;
    padding: 8px 16px;
    margin-bottom: 14px;
    text-align: center;
    width: 100%;
    font-size: 1.05rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.journal-section {
    width: 100%;
    margin-top: 30px;
}
.section-title {
    color: #009688;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 12px;
    margin-top: 18px;
    border-bottom: 1.5px solid #b2dfdb;
    padding-bottom: 4px;
}
.journal-entry {
    background: #f1f8e9;
    border-radius: 12px;
    padding: 18px 18px 12px 18px;
    margin-bottom: 18px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    position: relative;
}
.journal-entry .meta {
    color: #555;
    font-size: 0.97rem;
    margin-bottom: 7px;
}
.likes-comments-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px 0 0 0;
}
.likes-count {
    color: #00796b;
    font-size: 1.05rem;
    font-weight: 500;
    margin-right: 16px;
}
.comment-box {
    display: none;
    margin-top: 10px;
    background: #e0f7fa;
    border-radius: 8px;
    padding: 10px 14px;
}
.comment {
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid #b2dfdb;
}
.comment:last-child { border-bottom: none; }
.comment-author { color: #009688; font-weight: 600; font-size: 0.98rem; }
.comment-date { color: #555; font-size: 0.93rem; margin-left: 8px; }
.add-comment-form { margin-top: 10px; display: flex; gap: 8px; }
.add-comment-form textarea { flex: 1; min-height: 36px; max-height: 80px; margin-bottom: 0; }
.add-comment-form button { padding: 8px 18px; font-size: 1rem; border-radius: 8px; width: auto; }
.login-required { color: #c62828; font-size: 0.98rem; margin-top: 8px; }
@media (max-width: 600px) {
    .journal-container { padding: 10px 0 10px 0; }
    .journal-write-card { padding: 10px 2vw 8px 2vw; }
    .likes-comments-row { gap: 4px; }
}
</style>
<script>
function toggleCommentBox(id) {
    var box = document.getElementById('comment-box-' + id);
    if (box) box.style.display = (box.style.display === 'block') ? 'none' : 'block';
    if (box && box.style.display === 'block') {
        var textarea = box.querySelector('textarea');
        if (textarea) textarea.focus();
    }
}

function likeJournal(jid, btn) {
    if (!btn) return;
    btn.disabled = true;
    fetch('journal_like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'journal_id=' + encodeURIComponent(jid)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.classList.toggle('liked', data.liked);
            btn.nextElementSibling.textContent = data.likes;
        } else if (data.error) {
            alert(data.error);
        }
    })
    .finally(() => { btn.disabled = false; });
}

function setFloatingLabel() {
    document.querySelectorAll('.floating-label textarea').forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            if (this.value.length > 0) this.classList.add('has-content');
            else this.classList.remove('has-content');
        });
    });
}
document.addEventListener('DOMContentLoaded', setFloatingLabel);
</script>
</head>
<body>
<div class="journal-container">
    <div class="journal-logo">📔</div>
    <div class="journal-title">Anonymous Journal</div>
    <div class="journal-desc">Write your feelings, reflect, and share. You can choose to post as yourself or anonymously.</div>
    <?php if (!empty($msg)) echo '<div class="success">'.$msg.'</div>'; ?>
    <form method="post" class="journal-write-card" autocomplete="off">
        <div class="floating-label">
            <textarea name="entry" id="journal-entry" rows="4" required placeholder=" " maxlength="1000"></textarea>
            <label for="journal-entry">How are you feeling today?</label>
        </div>
        <div class="toggle-anon">
            <label class="toggle-switch">
                <input type="checkbox" name="is_anonymous">
                <span class="toggle-slider"></span>
            </label>
            <span style="color:#009688;font-size:1.01rem;">Post as Anonymous</span>
        </div>
        <button type="submit" class="write-btn"><span style="font-size:1.2em;">&#9998;</span> Write</button>
    </form>
    <div class="journal-section">
        <div class="section-title">Public Journals</div>
        <?php while($row = $public->fetch_assoc()): ?>
            <?php
            $jid = $row['id'];
            // Likes
            $likes_res = $conn->query("SELECT COUNT(*) as cnt FROM journal_likes WHERE journal_id=$jid");
            $likes_count = $likes_res ? $likes_res->fetch_assoc()['cnt'] : 0;
            $liked = false;
            if ($user_id) {
                $liked_res = $conn->query("SELECT id FROM journal_likes WHERE journal_id=$jid AND user_id=$user_id");
                $liked = $liked_res && $liked_res->num_rows > 0;
            }
            // Comments
            $comments = $conn->query("SELECT c.*, u.username FROM journal_comments c LEFT JOIN users u ON c.user_id=u.id WHERE c.journal_id=$jid ORDER BY c.created_at ASC");
            ?>
            <div class="journal-entry">
                <div class="meta">
                    By: <?php echo ($row['is_private'] == 1 || empty($row['username'])) ? 'Anonymous' : htmlspecialchars($row['username']); ?>
                    | Created: <?php echo htmlspecialchars($row['created_at']); ?>
                </div>
                <div><?php echo nl2br(htmlspecialchars($row['entry'])); ?></div>
                <div class="likes-comments-row">
                    <button type="button" class="icon-btn<?php if($liked) echo ' liked'; ?>" title="Like" onclick="likeJournal(<?php echo $jid; ?>, this)" <?php if(!$user_id) echo 'disabled'; ?>>
                        <span class="heart-icon" style="font-size:1.2em;vertical-align:middle;">&#10084;</span>
                    </button>
                    <span class="likes-count"><?php echo $likes_count; ?></span>
                    <button type="button" class="icon-btn" title="Comment" onclick="toggleCommentBox(<?php echo $jid; ?>)">
                        <span style="font-size:1.2em;vertical-align:middle;">&#128172;</span>
                    </button>
                </div>
                <div class="comment-box" id="comment-box-<?php echo $jid; ?>">
                    <div style="font-weight:600; color:#00796b; margin-bottom:6px;">Comments</div>
                    <?php if ($comments && $comments->num_rows > 0): ?>
                        <?php while($c = $comments->fetch_assoc()): ?>
                            <div class="comment">
                                <span class="comment-author"><?php echo htmlspecialchars($c['username'] ?? 'Anonymous'); ?></span>
                                <span class="comment-date"><?php echo htmlspecialchars($c['created_at']); ?></span>
                                <div><?php echo nl2br(htmlspecialchars($c['comment'])); ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="color:#888; font-size:0.97rem;">No comments yet.</div>
                    <?php endif; ?>
                    <?php if ($user_id): ?>
                        <form method="post" class="add-comment-form">
                            <input type="hidden" name="comment_journal_id" value="<?php echo $jid; ?>">
                            <textarea name="comment" placeholder="Add a comment..." required></textarea>
                            <button type="submit">&#128172; Comment</button>
                        </form>
                    <?php else: ?>
                        <div class="login-required">Login to like or comment.</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>
</body>
</html> 