<?php
// session_start();
include '../config.php';  // Adjust path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

// Initialize variables for the form
$role_name = '';
$status = 'active'; // Default status
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $role_name = trim($_POST['role_name']);
    $status = trim($_POST['status']);

    // Basic validation
    if (empty($role_name) || empty($status)) {
        $errors[] = "Role Name and Status are required.";
    }

    if (empty($errors)) {
        // Prepare the SQL statement to insert a new role
        $stmt = $conn->prepare("
            INSERT INTO tb_role (name, status) 
            VALUES (?, ?)
        ");
        $stmt->bind_param("ss", $role_name, $status);

        if ($stmt->execute()) {
            // Redirect to roles list after successful insertion
            echo "<script>window.location.href = '?page=roles';</script>";
            exit();
        } else {
            $errors[] = "Failed to add role: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Add Role</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Add New Role</h3>

        <!-- Display errors if any -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Add Role Form -->
        <form method="post" action="">
            <div class="mb-3">
                <label for="role_name" class="form-label">Role Name</label>
                <input type="text" id="role_name" name="role_name" class="form-control" value="<?php echo htmlspecialchars($role_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="active" <?php echo ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Role</button>
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
