<?php
// ============================================================
// Register.php - Handles user registration form submission
// ============================================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid Request");
}

// Include database connection file
require 'config.php';

// ============================================================
// Step 1: Get data from the registration form (POST method)
// ============================================================
$full_name = $_POST['fullName'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirmPassword'];

// ============================================================
// Step 2: Check if password and confirm password match
// ============================================================
if ($password !== $confirm_password) {
    die("Error: Password and Confirm Password do not match.");
}

// ============================================================
// Step 3: Check if the email already exists in the database
// ============================================================
$check_email_sql = "SELECT id FROM users WHERE email = ?";
$check_stmt = $conn->prepare($check_email_sql);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    die("Error: This email is already registered. Please login instead.");
}

$check_stmt->close();

// ============================================================
// Step 4: Hash the password before storing it
// ============================================================
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ============================================================
// Step 5: Insert the new user into the database
// ============================================================
$insert_sql = "INSERT INTO users (full_name, email, password, created_at) VALUES (?, ?, ?, NOW())";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("sss", $full_name, $email, $hashed_password);

if ($insert_stmt->execute()) {
    // ============================================================
    // Step 6: Show success message
    // ============================================================
    echo "Registration successful! You can now login.";
} else {
    // ============================================================
    // Step 7: Show error message if something goes wrong
    // ============================================================
    echo "Error: Something went wrong. Please try again.";
}

$insert_stmt->close();
$conn->close();
?>
