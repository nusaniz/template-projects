<?php
// session_start(); // Start session to access session variables

// Check if the user is logged in and has an 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    // Redirect to login page if not authenticated or not an admin
    header('Location: ../login/');
    exit();
}

// Include the database configuration file
include '../config.php';

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete user from the database
    $sql = "DELETE FROM tb_users WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the user list page with a success message
        echo "<script>
                alert('User deleted successfully.');
                window.location.href = '?page=users';
              </script>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "<div class='alert alert-danger' role='alert'>No user ID specified.</div>";
}
?>
