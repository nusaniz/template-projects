<?php
// session_start();
include '../config.php';  // Adjusted path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

// Get file ID from URL
$fileId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Delete file from database if ID is valid
if ($fileId > 0) {
    // Query to check if file exists
    $checkQuery = "SELECT * FROM tb_file WHERE id = $fileId";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        // Perform delete operation
        $deleteQuery = "DELETE FROM tb_file WHERE id = $fileId";
        if (mysqli_query($conn, $deleteQuery)) {
            // Redirect to the files list page with success message
            // header("Location: ?page=files&status=deleted");
            echo "<script>window.location.href= '?page=files'</script>";
            exit();
        } else {
            die("Error deleting file: " . mysqli_error($conn));
        }
    } else {
        die("File not found.");
    }
} else {
    die("Invalid file ID.");
}

// Close the database connection
$conn->close();
?>
