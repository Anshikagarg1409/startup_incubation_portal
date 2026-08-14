<?php
// ============================================================
// user_dashboard.php - Displays the logged-in user's dashboard
// ============================================================

// Step 1: Start the session
session_start();

// Include database connection file
require 'config.php';

// ============================================================
// Step 2: Check whether the user is logged in
// ============================================================
if (!isset($_SESSION['user_id'])) {
    // Step 3: Not logged in - redirect to the login page
    header("Location: login.html");
    exit();
}

// Get the logged-in user's details from the session
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$email = $_SESSION['email'];

// ============================================================
// Step 4: Fetch this user's startup ideas using a prepared statement
// ============================================================
$ideas_sql = "SELECT startup_title, startup_description, status, submitted_at FROM startup_ideas WHERE user_id = ? ORDER BY submitted_at DESC";
$ideas_stmt = $conn->prepare($ideas_sql);
if (!$ideas_stmt) {
    die("Database error.");
}
$ideas_stmt->bind_param("i", $user_id);
$ideas_stmt->execute();
$ideas_result = $ideas_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Startup Incubation Portal</title>

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
                <li><a href="user_dashboard.php">Dashboard</a></li>
            </ul>

            <div class="nav-buttons">
                <!-- Logout link points to logout.php to end the session -->
                <a href="logout.php" class="btn btn-outline">Logout</a>
            </div>
        </nav>
    </header>

    <main>

        <!-- ===================== Welcome Section ===================== -->
        <section class="hero">
            <!-- Show the logged-in user's name from the session -->
            <h1 id="welcomeMessage">Welcome, <?php echo htmlspecialchars($full_name); ?>!</h1>
            <p>Manage your startup application from your dashboard.</p>
        </section>

        <!-- ===================== Quick Actions Section ===================== -->
        <section class="services-section">
            <h2>Quick Actions</h2>
            <div class="services-container">
                <div class="service-card">
                    <h3>Quick Actions</h3>
                    <p>Manage your application and profile.</p>
                    <a href="submit_idea.php" class="btn btn-primary">Submit Startup Idea</a>
                    <a href="edit-profile.html" class="btn btn-outline">Edit Profile</a>
                </div>
            </div>
        </section>

        <!-- ===================== My Startup Ideas Section ===================== -->
        <section class="services-section">
            <h2>My Startup Ideas</h2>

            <?php
            // Step 5: Check if the user has submitted any startup ideas
            if ($ideas_result->num_rows > 0) {
            ?>
                <table class="applications-table">
                    <thead>
                        <tr>
                            <th>Startup Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Step 6: Loop through each startup idea and display it
                        while ($idea = $ideas_result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($idea['startup_title']); ?></td>
                                <td><?php echo htmlspecialchars($idea['startup_description']); ?></td>
                                <td><?php echo htmlspecialchars($idea['status']); ?></td>
                                <td><?php echo htmlspecialchars($idea['submitted_at']); ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
                // Step 7: No startup ideas found for this user
                echo "<p>No startup ideas submitted yet.</p>";
            }
            ?>
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
// Step 8: Close the prepared statement and database connection
// ============================================================
$ideas_stmt->close();
$conn->close();
?>