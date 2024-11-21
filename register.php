<<<<<<< HEAD
<?php
session_start();
include('includes/db.php'); // Include database connection

// Registration logic
if (isset($_POST['register'])) {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the email is already taken
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error_message = "Email is already registered!";
    } else {
        // Check if passwords match
        if ($password === $confirm_password) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                $success_message = "Registration successful! Please log in.";
                header('Location: login_register.php'); // Redirect to login page
                exit();
            } else {
                $error_message = "Something went wrong. Please try again.";
            }
        } else {
            $error_message = "Passwords do not match!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .register-form {
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

        .error-message, .success-message {
            margin-top: 10px;
            font-size: 14px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
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

        .register-form {
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

<h2>Register</h2>

<!-- Registration Form -->
<div class="register-form">
    <form method="POST" action="register.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="register">Register</button>
    </form>

    <?php
    // Display error or success message
    if (isset($error_message)) {
        echo "<p class='error-message'>$error_message</p>";
    }
    if (isset($success_message)) {
        echo "<p class='success-message'>$success_message</p>";
    }
    ?>
</div>

<p>Already have an account? <a href="login_register.php">Login here</a></p>

<script>
    // Dark Mode Toggle Functionality
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
    }
</script>

</body>
</html>
=======
<?php
session_start();
include('includes/db.php'); // Include database connection

// Registration logic
if (isset($_POST['register'])) {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the email is already taken
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error_message = "Email is already registered!";
    } else {
        // Check if passwords match
        if ($password === $confirm_password) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                $success_message = "Registration successful! Please log in.";
                header('Location: login_register.php'); // Redirect to login page
                exit();
            } else {
                $error_message = "Something went wrong. Please try again.";
            }
        } else {
            $error_message = "Passwords do not match!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .register-form {
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

        .error-message, .success-message {
            margin-top: 10px;
            font-size: 14px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
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

        .register-form {
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

<h2>Register</h2>

<!-- Registration Form -->
<div class="register-form">
    <form method="POST" action="register.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="register">Register</button>
    </form>

    <?php
    // Display error or success message
    if (isset($error_message)) {
        echo "<p class='error-message'>$error_message</p>";
    }
    if (isset($success_message)) {
        echo "<p class='success-message'>$success_message</p>";
    }
    ?>
</div>

<p>Already have an account? <a href="login_register.php">Login here</a></p>

<script>
    // Dark Mode Toggle Functionality
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
    }
</script>

</body>
</html>
>>>>>>> bc75d51bb1f19d615206c84d3aa857618a632dd1
