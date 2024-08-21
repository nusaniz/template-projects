<?php
// session_start();
include '../config.php';  // Adjust path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

// Check if role ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>window.location.href = '?page=roles';</script>";
    exit();
}

$roleId = (int)$_GET['id'];

// Confirm deletion request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare the SQL statement to delete the role
    $stmt = $conn->prepare("
        DELETE FROM tb_role 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $roleId);

    if ($stmt->execute()) {
        // Redirect to roles list after successful deletion
        echo "<script>window.location.href = '?page=roles';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to delete role: " . $conn->error . "'); window.location.href = '?page=roles';</script>";
        exit();
    }

    // Close the statement
    $stmt->close();
}

// Retrieve role name for confirmation
$query = "
    SELECT name 
    FROM tb_role 
    WHERE id = $roleId
";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "<script>window.location.href = '?page=roles';</script>";
    exit();
}

$role = $result->fetch_assoc();
$role_name = htmlspecialchars($role['name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Role</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Delete Role</h3>

        <!-- Confirmation Message -->
        <div class="alert alert-warning">
            <p>Are you sure you want to delete the role "<strong><?php echo $role_name; ?></strong>"?</p>
        </div>

        <!-- Confirmation Form -->
        <form method="post" action="">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="?page=roles" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper from jsDelivr -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
