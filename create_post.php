<?php
include 'includes/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php'); // Redirect to login page if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $userId = $_SESSION['user_id'];

    // Handle file upload (image or video)
    $filePath = null; // Default value for media
    if (!empty($_FILES['media']['name'])) {
        $uploadDir = 'uploads/posts/'; // Directory for uploads
        $fileName = basename($_FILES['media']['name']); // Get original file name
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION); // Get file extension
        $filePath = $uploadDir . uniqid() . '_' . $fileName; // Create unique file path

        // Check if the file is of allowed type (image/video)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm'];
        if (in_array($_FILES['media']['type'], $allowedTypes)) {
            // Try to move the uploaded file to the target directory
            if (!move_uploaded_file($_FILES['media']['tmp_name'], $filePath)) {
                die('Error uploading file.');
            }
        } else {
            die('Invalid file type. Please upload an image or video.');
        }
    }

    // Insert post into the database
    try {
        $stmt = $pdo->prepare('INSERT INTO posts (user_id, title, content, media) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $title, $content, $filePath]);
        header('Location: dashboard.php'); // Redirect to dashboard after successful post
        exit();
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage()); // Handle database error
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Create a Post</h2>
        <input type="text" name="title" placeholder="Post Title" required>
        <textarea name="content" placeholder="What's on your mind?" required></textarea>
        
        <label for="media">Upload an image or video:</label>
        <input type="file" name="media" accept="image/*,video/*">

        <button type="submit">Post</button>
    </form>
</body>
</html>
