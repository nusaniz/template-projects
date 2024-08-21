<?php
include '../config.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

// Get the position ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Delete the position from the database
$query = "DELETE FROM tb_jabatan WHERE id = $id";
if (mysqli_query($conn, $query)) {
    // header("Location: ?page=jabatan");
    echo "<script>window.location.href= '?page=jabatan'</script>";
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
