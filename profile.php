<?php
include 'includes/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch the current logged-in user's data
$stmtUser = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();

// Fetch the user's posts
$stmtPosts = $pdo->prepare('SELECT * FROM posts WHERE user_id = ?');
$stmtPosts->execute([$userId]);
$posts = $stmtPosts->fetchAll();

// Count number of posts
$postCount = count($posts);

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['profile_picture']['name'])) {
        $uploadDir = 'uploads/profile_pictures/';
        $fileName = basename($_FILES['profile_picture']['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filePath)) {
            // Update profile picture in database
            $stmtUpdatePic = $pdo->prepare('UPDATE users SET profile_picture = ? WHERE id = ?');
            $stmtUpdatePic->execute([$filePath, $userId]);

            // Refresh page after updating
            header('Location: profile.php');
            exit();
        } else {
            $errorMessage = "Error uploading file.";
        }
    }

    // Update bio if form is submitted
    $bio = trim($_POST['bio']);
    $stmtUpdateBio = $pdo->prepare('UPDATE users SET bio = ? WHERE id = ?');
    $stmtUpdateBio->execute([$bio, $userId]);

    // Refresh page after updating
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Profile Section */
        .profile {
            padding: 80px 5%;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 80px;
            text-align: center;
        }

        /* Profile Info */
        .profile-info {
            max-width: 800px;
            margin: 0 auto;
        }

        .profile-name {
            font-size: 36px;
            color: #333;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .profile-bio {
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            font-size: 16px;
            color: #555;
        }

        .bio-title {
            font-size: 18px;
            font-weight: 600;
            color: #444;
            margin-bottom: 10px;
        }

        .profile-contact {
            font-size: 16px;
            color: #555;
        }

        .profile-contact p {
            margin: 5px 0;
        }

        /* Profile Picture */
        .profile-pic {
            margin-bottom: 20px;
        }

        .profile-pic img {
            max-width: 150px;
            height: auto;
            border-radius: 50%;
            border: 3px solid #ff5e5e;
        }

        /* Post Gallery */
        .post-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .post-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .post-item img,
        .post-item video {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Dark Mode Styles */
        body.dark-mode .profile {
            background-color: #444;
            color: #ccc;
        }

        body.dark-mode .profile-name {
            color: #fff;
        }

        body.dark-mode .profile-bio {
            background-color: #555;
            color: #ccc;
        }

        body.dark-mode .bio-title {
            color: #ff5e5e;
        }

        /* Form Styles */
        form {
            margin-top: 30px;
        }

        label {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #ff5e5e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e91e63;
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            background-color: #333;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            position: fixed;
            top: 20px;
            right: 20px;
            border-radius: 5px;
        }

        .dark-mode-toggle:hover {
            background-color: #555;
        }
        .navbar {
            background-color: #222;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
        }
        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo a {
            color: #ff5e5e;
            font-size: 32px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .logo a:hover {
            color: #ff5e5e;
        }
        .nav-links a {
            color: #fff;
            font-size: 16px;
            margin-left: 25px;
            text-decoration: none;
            position: relative;
            transition: color 0.3s ease;
        }
        .nav-links a:hover {
            color: #ff5e5e;
        }
        .nav-links a::after {
            content: "";
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #ff5e5e;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .nav-links a:hover::after {
            transform: scaleX(1);
        }

    </style>
</head>
<body>
    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" onclick="toggleDarkMode()">Toggle Dark Mode</button>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logoo">
            <a href="dashboard.php" style="font-family: TimesNewRoman; color: white; font-size:30px;">Exception IO</a>
            </div>
            <div class="nav-links">
                <a href="create_post.php">Create Post</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Profile Section -->
    <div class="profile">
        <h2 class="profile-name"><?php echo htmlspecialchars($user['username']); ?>'s Profile</h2>
        
        <!-- Profile Bio -->
        <div class="profile-bio">
            <p class="bio-title">Professional Bio:</p>
            <p><?php echo htmlspecialchars($user['bio']); ?></p>
        </div>

        <!-- Profile Picture -->
        <div class="profile-pic">
    <?php if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])): ?>
        <!-- Display user's profile picture -->
        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
    <?php else: ?>
        <!-- Fallback to default avatar -->
        <img src="uploads/profile_pictures/default-avatar.png" alt="Default Avatar">
    <?php endif; ?>
</div>


        <!-- Profile Picture Upload -->
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Change Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
            <button type="submit">Upload</button>
        </form>

        <!-- Bio Update -->
        <form action="profile.php" method="POST">
            <label for="bio">Update Bio:</label>
            <textarea name="bio" id="bio" rows="4" placeholder="Write something about yourself..."><?php echo htmlspecialchars($user['bio']); ?></textarea>
            <button type="submit">Update Bio</button>
        </form>

        <!-- Post Count -->
        <p><strong>Posts:</strong> <?php echo $postCount; ?></p>

        <!-- User's Posts (Image Gallery) -->
        <div class="post-gallery">
            <?php if ($postCount > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-item">
                        <?php if (strpos($post['media'], '.mp4') !== false): ?>
                            <video controls src="<?php echo $post['media']; ?>"></video>
                        <?php else: ?>
                            <img src="<?php echo $post['media']; ?>" alt="Post Media">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Dark Mode Toggle Functionality
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>
</html>
