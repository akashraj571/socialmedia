<?php
include 'includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $comment = trim($_POST['comment']);
    $userId = $_SESSION['user_id'];

    // Insert comment into the database
    $stmt = $pdo->prepare('INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)');
    $stmt->execute([$postId, $userId, $comment]);

    header('Location: dashboard.php');
    exit();
}
?>
