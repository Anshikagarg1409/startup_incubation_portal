<?php
// ============================================================
// submit_idea.php - Handles startup idea submission
// ============================================================

// ============================================================
// Step 1: Start the session
// ============================================================
session_start();

// Include database connection file
require 'config.php';

// ============================================================
// Step 2: Check that the form was submitted using POST method
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ============================================================
    // Step 3: Check whether the user is logged in
    // ============================================================
    if (!isset($_SESSION['user_id'])) {
        die("Login required.");
    }

    // Get the logged-in user's ID from the session
    $user_id = $_SESSION['user_id'];

    // ============================================================
    // Step 4: Receive the startup title and description
    // ============================================================
    $startup_title = $_POST['startup_title'];
    $startup_description = $_POST['startup_description'];

    // ============================================================
    // Step 5: Trim both inputs to remove extra spaces
    // ============================================================
    $startup_title = trim($startup_title);
    $startup_description = trim($startup_description);

    // ============================================================
    // Step 6: Check that neither field is empty
    // ============================================================
    if ($startup_title === "" || $startup_description === "") {
        die("Please fill in both the startup title and description.");
    }

    // ============================================================
    // Step 7: Set the default status for a new idea
    // ============================================================
    $status = "Pending";

    // ============================================================
    // Step 8: Insert the startup idea using a prepared statement
    // ============================================================
    $insert_sql = "INSERT INTO startup_ideas (user_id, startup_title, startup_description, status, submitted_at) VALUES (?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("isss", $user_id, $startup_title, $startup_description, $status);

    // ============================================================
    // Step 9: Check if the insertion was successful
    // ============================================================
    if ($insert_stmt->execute()) {

        // Insertion successful - redirect to the user dashboard
        header("Location: user_dashboard.php");
        exit();

    } else {
        // Insertion failed - show an error message
        echo "Error submitting startup idea.";
    }

    // ============================================================
    // Step 10: Close the prepared statement and database connection
    // ============================================================
    $insert_stmt->close();
    $conn->close();

} else {
    // The page was accessed without submitting the form
    echo "Invalid request.";
}
?>