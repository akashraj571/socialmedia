<?php
include 'includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];

    // Handle file upload
    $uploadDir = 'uploads/stories/';
    $fileName = basename($_FILES['story']['name']);
    $filePath = $uploadDir . uniqid() . '_' . $fileName;

    if (move_uploaded_file($_FILES['story']['tmp_name'], $filePath)) {
        // Insert story into the database
        $stmt = $pdo->prepare('INSERT INTO stories (user_id, media) VALUES (?, ?)');
        $stmt->execute([$userId, $filePath]);
        header('Location: dashboard.php');
        exit();
    } else {
        die('Error uploading story.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Story</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Upload a Story</h2>
        <input type="file" name="story" accept="image/*,video/*" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
