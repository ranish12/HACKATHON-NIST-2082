<?php
session_start();
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) die(json_encode(['success'=>false, 'error'=>'DB error']));
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
    echo json_encode(['success'=>false, 'error'=>'Login required']);
    exit;
}
$jid = isset($_POST['journal_id']) ? intval($_POST['journal_id']) : 0;
if (!$jid) {
    echo json_encode(['success'=>false, 'error'=>'Invalid journal']);
    exit;
}
$res = $conn->query("SELECT id FROM journal_likes WHERE journal_id=$jid AND user_id=$user_id");
if ($res->num_rows > 0) {
    $conn->query("DELETE FROM journal_likes WHERE journal_id=$jid AND user_id=$user_id");
    $liked = false;
} else {
    $conn->query("INSERT INTO journal_likes (journal_id, user_id) VALUES ($jid, $user_id)");
    $liked = true;
}
$likes_res = $conn->query("SELECT COUNT(*) as cnt FROM journal_likes WHERE journal_id=$jid");
$likes_count = $likes_res ? intval($likes_res->fetch_assoc()['cnt']) : 0;
echo json_encode(['success'=>true, 'liked'=>$liked, 'likes'=>$likes_count]); 