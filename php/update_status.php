<?php
// ============================================================
// update_status.php - Allows admin to update a startup idea's status
// ============================================================

// Step 1: Start the session
session_start();

// Include database connection file
require 'config.php';

// ============================================================
// Step 2: Get the startup idea ID from the URL
// ============================================================
$idea_id = isset($_GET['id']) ? $_GET['id'] : null;

// ============================================================
// Step 3: Validate that the ID is a valid integer
// ============================================================
if ($idea_id === null || !filter_var($idea_id, FILTER_VALIDATE_INT)) {
    die("Invalid startup idea.");
}

// Convert to an integer for safety
$idea_id = (int) $idea_id;

// ============================================================
// Step 4: Handle the form submission (POST request)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the new status entered by the admin
    $new_status = $_POST['status'];

    // ============================================================
    // Step 5: Validate that the status is one of the allowed values
    // ============================================================
    $allowed_status = ["Pending", "Approved", "Rejected"];

    if (!in_array($new_status, $allowed_status)) {
        die("Invalid status value.");
    }

    // ============================================================
    // Step 6: Update the status column using a prepared statement
    // ============================================================
    $update_sql = "UPDATE startup_ideas SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
    die("Database error.");
}
    $update_stmt->bind_param("si", $new_status, $idea_id);

    if ($update_stmt->execute()) {
        // Update successful - redirect to the admin dashboard
        $update_stmt->close();
        $conn->close();
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Update failed
        echo "Error updating status.";
        $update_stmt->close();
        $conn->close();
        exit();
    }
}

// ============================================================
// Step 7: Fetch the startup idea's title and status (for GET request)
// ============================================================
$fetch_sql = "SELECT startup_title, status FROM startup_ideas WHERE id = ?";
$fetch_stmt = $conn->prepare($fetch_sql);
if (!$fetch_stmt) {
    die("Database error.");
}
$fetch_stmt->bind_param("i", $idea_id);
$fetch_stmt->execute();
$fetch_result = $fetch_stmt->get_result();

// ============================================================
// Step 8: Check if the startup idea exists
// ============================================================
if ($fetch_result->num_rows === 0) {
    die("Startup idea not found.");
}

$idea = $fetch_result->fetch_assoc();
$startup_title = $idea['startup_title'];
$current_status = $idea['status'];

// Close the fetch statement (the connection stays open for the page below)
$fetch_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status - Startup Incubation Portal</title>

    <!-- Google Fonts: Sora for headings, Inter for body text -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <!-- External CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ===================== Simple Navbar Section ===================== -->
    <header>
        <nav class="navbar">
            <div class="logo">Startup Incubation Portal</div>

            <ul class="nav-links">
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
            </ul>

            <div class="nav-buttons">
                <a href="logout.php" class="btn btn-outline">Logout</a>
            </div>
        </nav>
    </header>

    <main>

        <!-- ===================== Update Status Section ===================== -->
        <section class="contact-section">
            <h2>Update Application Status</h2>

            <form class="contact-form" action="update_status.php?id=<?php echo $idea_id; ?>" method="POST">

                <!-- Startup Title (read-only) -->
                <label for="startup_title">Startup Title</label>
                <input type="text" id="startup_title" value="<?php echo htmlspecialchars($startup_title); ?>" readonly>

                <!-- Status Dropdown -->
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Pending" <?php if ($current_status === "Pending") echo "selected"; ?>>Pending</option>
                    <option value="Approved" <?php if ($current_status === "Approved") echo "selected"; ?>>Approved</option>
                    <option value="Rejected" <?php if ($current_status === "Rejected") echo "selected"; ?>>Rejected</option>
                </select>

                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </section>

    </main>

    <!-- ===================== Footer Section ===================== -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Startup Incubation Portal. All rights reserved.</p>
            <p>Email: support@startupportal.com</p>
            <ul class="footer-links">
                <li><a href="about.html">About</a></li>
                <li><a href="services.html">Services</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </div>
    </footer>

</body>
</html>
<?php
// ============================================================
// Step 9: Close the database connection
// ============================================================
$conn->close();
?>