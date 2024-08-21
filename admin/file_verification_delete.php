<?php
include '../config.php';  // Adjust path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Prepare the SQL statement to delete the record
    $deleteQuery = "DELETE FROM tb_file_verification WHERE id = $id";
    
    if (mysqli_query($conn, $deleteQuery)) {
        // Redirect to file_verification.php after successful deletion
        // header("Location: ?page=file_verification");
        echo "<script>window.location.href= '?page=file_verification'</script>";
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "ID parameter missing.";
}

// Close the database connection
$conn->close();
?>
