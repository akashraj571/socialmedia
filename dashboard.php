<<<<<<< HEAD
<?php
include 'includes/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit();
}

// Fetch the current logged-in user's data
$userId = $_SESSION['user_id'];
$stmtUser = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();

// Fetch all posts (assuming posts are public)
$stmtPosts = $pdo->query('SELECT * FROM posts ORDER BY created_at DESC');
$posts = $stmtPosts->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Exception IO</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Google Font and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f3f3f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Navigation Bar */
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

        /* Profile Section */
        .profile-section {
            padding: 80px 5%;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 80px;
            text-align: center;
        }
        .profile-avatar img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid #ff5e5e;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .profile-info h2 {
            font-size: 36px;
            color: #444;
            margin-bottom: 10px;
        }
        .profile-info p {
            font-size: 16px;
            color: #777;
            margin: 5px 0;
        }
        .profile-info p strong {
            color: #333;
        }

        /* Posts Section */
        .posts-section {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 30px 5%;
        }
        .post-card {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .post-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        .post-header {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: left;
        }
        .post-header h3 {
            font-size: 20px;
            color: #444;
            font-weight: 600;
        }
        .post-header p {
            font-size: 14px;
            color: #777;
        }
        .post-content {
            padding: 20px;
            color: #333;
        }
        .post-footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
        }
        .post-footer p {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .like-btn, .comment-btn {
            color: #ff5e5e;
            font-size: 18px;
            text-decoration: none;
            margin-right: 10px;
            transition: color 0.3s ease;
        }
        .like-btn:hover, .comment-btn:hover {
            color: #e91e63;
        }

        /* Comment Form */
        .comment-form input {
            width: 70%;
            padding: 8px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .comment-form button {
            padding: 8px 12px;
            background-color: #ff5e5e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .comment-form button:hover {
            background-color: #e91e63;
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            cursor: pointer;
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border-radius: 25px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .dark-mode-toggle:hover {
            background-color: #222;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #333;
            color: #ccc;
        }
        body.dark-mode .navbar {
            background-color: #111;
        }
        body.dark-mode .navbar .logo a {
            color: #ff5e5e;
        }
        body.dark-mode .navbar .nav-links a {
            color: #ccc;
        }
        body.dark-mode .post-card {
            background-color: #444;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        }
        body.dark-mode .post-header,
        body.dark-mode .post-footer {
            background-color: #555;
        }
        body.dark-mode .like-btn,
        body.dark-mode .comment-btn {
            color: #ff5e5e;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logoo">
                <a href="dashboard.php" style="font-family: TimesNewRoman; color: white; font-size:30px;">Exception IO</a>
            </div>
            <div class="nav-links">
                <a href="create_post.php"><i class="fas fa-plus-circle"></i> Create Post</a>
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <!-- Profile Section -->
    <section class="profile-section">
    <div class="profile-info">
        <!-- Display Profile Name -->
        <h2 class="profile-name"><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>'s Profile</h2>
        
        <!-- Display Profile Bio -->
        <div class="profile-bio">
            <p class="bio-title">Professional Bio:</p>
            <p><?php echo !empty($user['bio']) ? htmlspecialchars($user['bio'], ENT_QUOTES, 'UTF-8') : "Bio not provided."; ?></p>
        </div>
        
        <!-- Display Contact Email -->
        <div class="profile-contact">
            <p><strong>Email:</strong> 
            <?php echo !empty($user['email']) ? htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') : "Email not provided."; ?></p>
        </div>
    </div>
</section>

<style>
    /* Profile Section */
    .profile-section {
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

    /* Dark Mode Styles */
    body.dark-mode .profile-section {
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
</style>

    <!-- Posts Section -->
    <section class="posts-section">
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <div class="post-header">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><small>Posted on: <?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?></small></p>
                </div>

                <div class="post-content">
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <?php if ($post['media']): ?>
                        <?php if (strpos($post['media'], '.mp4') !== false): ?>
                            <video controls src="<?php echo $post['media']; ?>"></video>
                        <?php else: ?>
                            <img src="<?php echo $post['media']; ?>" alt="Post Media" class="post-media">
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="post-footer">
                    <p><i class="fas fa-heart"></i> <?php echo $post['likes']; ?> | <i class="fas fa-eye"></i> <?php echo $post['views'] ?? 0; ?></p>
                    <a href="like.php?id=<?php echo $post['id']; ?>" class="like-btn"><i class="fas fa-thumbs-up"></i> Like</a>
                    <a href="comment.php?post_id=<?php echo $post['id']; ?>" class="comment-btn"><i class="fas fa-comment-dots"></i> Comment</a>
                    <form action="comment.php" method="POST" class="comment-form">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <input type="text" name="comment" placeholder="Add a comment..." required>
                        <button type="submit"><i class="fas fa-paper-plane"></i> Send</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Dark Mode Toggle -->
    <div class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Toggle Dark Mode</div>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>

</body>
</html>
=======
<?php
include 'includes/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit();
}

// Fetch the current logged-in user's data
$userId = $_SESSION['user_id'];
$stmtUser = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();

// Fetch all posts (assuming posts are public)
$stmtPosts = $pdo->query('SELECT * FROM posts ORDER BY created_at DESC');
$posts = $stmtPosts->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Exception IO</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Google Font and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f3f3f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Navigation Bar */
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

        /* Profile Section */
        .profile-section {
            padding: 80px 5%;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 80px;
            text-align: center;
        }
        .profile-avatar img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid #ff5e5e;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .profile-info h2 {
            font-size: 36px;
            color: #444;
            margin-bottom: 10px;
        }
        .profile-info p {
            font-size: 16px;
            color: #777;
            margin: 5px 0;
        }
        .profile-info p strong {
            color: #333;
        }

        /* Posts Section */
        .posts-section {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 30px 5%;
        }
        .post-card {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .post-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        .post-header {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: left;
        }
        .post-header h3 {
            font-size: 20px;
            color: #444;
            font-weight: 600;
        }
        .post-header p {
            font-size: 14px;
            color: #777;
        }
        .post-content {
            padding: 20px;
            color: #333;
        }
        .post-footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
        }
        .post-footer p {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .like-btn, .comment-btn {
            color: #ff5e5e;
            font-size: 18px;
            text-decoration: none;
            margin-right: 10px;
            transition: color 0.3s ease;
        }
        .like-btn:hover, .comment-btn:hover {
            color: #e91e63;
        }

        /* Comment Form */
        .comment-form input {
            width: 70%;
            padding: 8px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .comment-form button {
            padding: 8px 12px;
            background-color: #ff5e5e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .comment-form button:hover {
            background-color: #e91e63;
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            cursor: pointer;
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border-radius: 25px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .dark-mode-toggle:hover {
            background-color: #222;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #333;
            color: #ccc;
        }
        body.dark-mode .navbar {
            background-color: #111;
        }
        body.dark-mode .navbar .logo a {
            color: #ff5e5e;
        }
        body.dark-mode .navbar .nav-links a {
            color: #ccc;
        }
        body.dark-mode .post-card {
            background-color: #444;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        }
        body.dark-mode .post-header,
        body.dark-mode .post-footer {
            background-color: #555;
        }
        body.dark-mode .like-btn,
        body.dark-mode .comment-btn {
            color: #ff5e5e;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logoo">
                <a href="dashboard.php" style="font-family: TimesNewRoman; color: white; font-size:30px;">Exception IO</a>
            </div>
            <div class="nav-links">
                <a href="create_post.php"><i class="fas fa-plus-circle"></i> Create Post</a>
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <!-- Profile Section -->
    <section class="profile-section">
    <div class="profile-info">
        <!-- Display Profile Name -->
        <h2 class="profile-name"><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>'s Profile</h2>
        
        <!-- Display Profile Bio -->
        <div class="profile-bio">
            <p class="bio-title">Professional Bio:</p>
            <p><?php echo !empty($user['bio']) ? htmlspecialchars($user['bio'], ENT_QUOTES, 'UTF-8') : "Bio not provided."; ?></p>
        </div>
        
        <!-- Display Contact Email -->
        <div class="profile-contact">
            <p><strong>Email:</strong> 
            <?php echo !empty($user['email']) ? htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') : "Email not provided."; ?></p>
        </div>
    </div>
</section>

<style>
    /* Profile Section */
    .profile-section {
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

    /* Dark Mode Styles */
    body.dark-mode .profile-section {
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
</style>

    <!-- Posts Section -->
    <section class="posts-section">
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <div class="post-header">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><small>Posted on: <?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?></small></p>
                </div>

                <div class="post-content">
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <?php if ($post['media']): ?>
                        <?php if (strpos($post['media'], '.mp4') !== false): ?>
                            <video controls src="<?php echo $post['media']; ?>"></video>
                        <?php else: ?>
                            <img src="<?php echo $post['media']; ?>" alt="Post Media" class="post-media">
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="post-footer">
                    <p><i class="fas fa-heart"></i> <?php echo $post['likes']; ?> | <i class="fas fa-eye"></i> <?php echo $post['views'] ?? 0; ?></p>
                    <a href="like.php?id=<?php echo $post['id']; ?>" class="like-btn"><i class="fas fa-thumbs-up"></i> Like</a>
                    <a href="comment.php?post_id=<?php echo $post['id']; ?>" class="comment-btn"><i class="fas fa-comment-dots"></i> Comment</a>
                    <form action="comment.php" method="POST" class="comment-form">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <input type="text" name="comment" placeholder="Add a comment..." required>
                        <button type="submit"><i class="fas fa-paper-plane"></i> Send</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Dark Mode Toggle -->
    <div class="dark-mode-toggle" onclick="toggleDarkMode()">🌙 Toggle Dark Mode</div>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>

</body>
</html>
>>>>>>> bc75d51bb1f19d615206c84d3aa857618a632dd1
