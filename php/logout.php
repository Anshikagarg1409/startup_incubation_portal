<?php
// ============================================================
// logout.php - Logs the user out by ending their session
// ============================================================

// Step 1: Start the session so we can access it
session_start();

// Step 2: Remove all session variables
session_unset();

// Step 3: Destroy the session completely
session_destroy();

// Step 4: Redirect the user to the login page
header("Location: login.html");

// Step 5: Stop the script immediately after redirecting
exit();
?>