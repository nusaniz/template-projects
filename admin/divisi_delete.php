<?php
include '../config.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

// Get the division ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Delete the division
$query = "DELETE FROM tb_divisi WHERE id = $id";
if (mysqli_query($conn, $query)) {
    // header("Location: ?page=divisi");
    echo "<script>window.location.href= '?page=divisi'</script>";
    exit();
} else {
    echo "Error deleting division: " . mysqli_error($conn);
}
?>
