<?php
// ============================================================
// admin_dashboard.php - Displays all startup ideas for the admin
// ============================================================

// Step 1: Start the session
session_start();

// Include database connection file
require 'config.php';

// ============================================================
// Step 2: Fetch all startup ideas using an INNER JOIN
// (Using a prepared statement for consistency, even though
// there are no user inputs in this query)
// ============================================================
$ideas_sql = "SELECT startup_ideas.id, users.full_name, users.email, 
                     startup_ideas.startup_title, startup_ideas.startup_description, 
                     startup_ideas.status, startup_ideas.submitted_at
              FROM startup_ideas
              INNER JOIN users ON users.id = startup_ideas.user_id
              ORDER BY startup_ideas.submitted_at DESC";

$ideas_stmt = $conn->prepare($ideas_sql);
if (!$ideas_stmt) {
    die("Database error.");
}
$ideas_stmt->execute();
$ideas_result = $ideas_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Startup Incubation Portal</title>

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
                <!-- Logout link points to logout.php to end the session -->
                <a href="logout.php" class="btn btn-outline">Logout</a>
            </div>
        </nav>
    </header>

    <main>

        <!-- ===================== Welcome Section ===================== -->
        <section class="hero">
            <h1>Welcome, Admin</h1>
            <p>Manage startup applications submitted by users.</p>
        </section>

        <!-- ===================== Applications Table Section ===================== -->
        <section class="services-section">
            <h2>All Applications</h2>

            <?php
            // Step 3: Check if there are any startup ideas
            if ($ideas_result->num_rows > 0) {
            ?>
                <table class="applications-table">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>Startup Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Step 4: Loop through each startup idea and display it
                        while ($row = $ideas_result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['startup_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['startup_description']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                                <td>
                                    <a href="update_status.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Update Status</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
                // Step 5: No startup ideas found in the database
                echo "<p>No startup ideas found.</p>";
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
// Step 6: Close the prepared statement and database connection
// ============================================================
$ideas_stmt->close();
$conn->close();
?>