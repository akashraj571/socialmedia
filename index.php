<?php
// Entry point. Redirect to login or dashboard if logged in.
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login_register.php');
}
exit();
?>
