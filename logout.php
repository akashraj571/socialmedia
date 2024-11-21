<<<<<<< HEAD
<?php
// Start the session to access session variables
session_start();

// Destroy all session data to log the user out
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session

// Redirect to the login page
header('Location: login_register.php');
exit();
?>
=======
<?php
// Start the session to access session variables
session_start();

// Destroy all session data to log the user out
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session

// Redirect to the login page
header('Location: login_register.php');
exit();
?>
>>>>>>> bc75d51bb1f19d615206c84d3aa857618a632dd1
