<?php
session_start();
include('includes/db.php'); // Include your database connection

// Login logic
if (isset($_POST['login'])) {
    // Get user inputs from the login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a query to check if the user exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user data

    if ($user) {
        // If the user exists, verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['username'] = $user['username']; // Store username in session

            // Redirect to dashboard.php
            header('Location: dashboard.php');
            exit(); // Ensure the script stops after redirection
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with that email address!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 20px;
        }

        /* Form Styles */
        .login-form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        /* Footer link styles */
        p {
            font-size: 16px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #333;
            color: #ccc;
        }

        .login-form {
            background-color: #444;
            color: #fff;
        }

        input {
            background-color: #555;
            color: #fff;
            border: 1px solid #666;
        }

        button {
            background-color: #ff5e5e;
        }

        button:hover {
            background-color: #e91e63;
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .dark-mode-toggle:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<!-- Dark Mode Toggle Button -->
<button class="dark-mode-toggle" onclick="toggleDarkMode()">Toggle Dark Mode</button>

<h2>Login</h2>

<!-- Login Form -->
<div class="login-form">
    <form method="POST" action="login_register.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <?php
    // Display error message if it exists
    if (isset($error_message)) {
        echo "<p class='error-message'>$error_message</p>";
    }
    ?>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

<script>
    // Dark Mode Toggle Functionality
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
    }
</script>

</body>
</html>
