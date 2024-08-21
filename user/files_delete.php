<?php
// session_start();
include '../config.php'; // Include your database connection

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'user') {
    header("Location: ../login/");
    exit();
}

// Initialize variables
$file_id = null;
$user_id = $_SESSION['user_id'];

// Retrieve file ID from the query string
if (isset($_GET['id'])) {
    $file_id = (int)$_GET['id'];

    // Check if the file exists and belongs to the current user
    $stmt = $conn->prepare("
        SELECT file, file_name
        FROM tb_file
        WHERE id = ? AND user_id = ?
    ");
    $stmt->bind_param("ii", $file_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $file_data = $result->fetch_assoc();
        $file_path = '../uploads/' . $file_data['file'];
        $file_name = $file_data['file_name'];

        // Delete the file from the server
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete the file record from the database
        $stmt = $conn->prepare("
            DELETE FROM tb_file
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $file_id, $user_id);
        
        if ($stmt->execute()) {
            // Redirect to files list after successful deletion
            echo "<script>alert('File deleted successfully.'); window.location.href= '?page=files';</script>";
        } else {
            echo "<script>alert('Failed to delete file. Please try again.'); window.location.href= '?page=files';</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('File not found or you do not have permission to delete this file.'); window.location.href= '?page=files';</script>";
    }
} else {
    echo "<script>alert('No file ID provided.'); window.location.href= '?page=files';</script>";
}

// Close the database connection
$conn->close();
?>
