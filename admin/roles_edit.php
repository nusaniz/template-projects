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
$status = '';
$errors = [];

// Check if role ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>window.location.href = '?page=roles';</script>";
    exit();
}

$roleId = (int)$_GET['id'];

// Retrieve existing role data
$query = "
    SELECT name, status 
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
$status = htmlspecialchars($role['status']);

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
        // Prepare the SQL statement to update the role
        $stmt = $conn->prepare("
            UPDATE tb_role 
            SET name = ?, status = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssi", $role_name, $status, $roleId);

        if ($stmt->execute()) {
            // Redirect to roles list after successful update
            echo "<script>window.location.href = '?page=roles';</script>";
            exit();
        } else {
            $errors[] = "Failed to update role: " . $conn->error;
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
    <title><?php echo $web_name;?> | Edit Role</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Edit Role</h3>

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

        <!-- Edit Role Form -->
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
            <button type="submit" class="btn btn-primary">Update Role</button>
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
