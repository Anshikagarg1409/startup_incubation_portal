<?php
// ============================================================
// login.php - Handles user login form submission
// ============================================================

// Include database connection file
require 'config.php';

// ============================================================
// Step 1: Check that the form was submitted using POST method
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ============================================================
    // Step 2: Get email and password from the login form
    // ============================================================
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ============================================================
    // Step 3: Trim the email to remove extra spaces
    // ============================================================
    $email = trim($email);

    // ============================================================
    // Step 4: Use a prepared statement to find the user by email
    // ============================================================
    $login_sql = "SELECT id, full_name, email, password FROM users WHERE email = ?";
    $login_stmt = $conn->prepare($login_sql);
    $login_stmt->bind_param("s", $email);
    $login_stmt->execute();
if (empty($email) || empty($password)) {
    die("Please enter both email and password.");
}
    // ============================================================
    // Step 5: Get the result of the query
    // ============================================================
    $result = $login_stmt->get_result();

    // ============================================================
    // Step 6: Check whether the email exists
    // ============================================================
    if ($result->num_rows > 0) {

        // Fetch the user's data as an associative array
        $user = $result->fetch_assoc();

        // ============================================================
        // Step 7: Verify the entered password with the hashed password
        // ============================================================
        if (password_verify($password, $user['password'])) {

            // ============================================================
            // Step 8: Password is correct - start session and store data
            // ============================================================
            session_start();
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];

            // Redirect the user to their dashboard using PHP header redirection
           header("Location: user_dashboard.php");
exit();

        } else {
            // Password did not match
            echo "Invalid email or password.";
        }

    } else {
        // No user found with this email
        echo "Invalid email or password.";
    }

    // ============================================================
    // Step 9: Close the prepared statement and database connection
    // ============================================================
    $login_stmt->close();
    $conn->close();

} else {
    // The page was accessed without submitting the form
    echo "Invalid request.";
}
?>