<?php
include 'includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // Increment the likes count for the post
    $stmt = $pdo->prepare('UPDATE posts SET likes = likes + 1 WHERE id = ?');
    $stmt->execute([$postId]);

    header('Location: dashboard.php');
    exit();
} else {
    die('Invalid post ID.');
}
?>
