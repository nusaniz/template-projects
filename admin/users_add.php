<?php
// session_start();
include '../config.php';  // Adjusted path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Initialize variables for the form
$username = '';
$full_name = '';
$role_id = '';
$divisi_id = '';
$jabatan_id = '';
$password = '';
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $role_id = trim($_POST['role_id']);
    $divisi_id = trim($_POST['divisi_id']);
    $jabatan_id = trim($_POST['jabatan_id']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($full_name) || empty($role_id) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        // Prepare and execute SQL statement to insert a new user
        $stmt = $conn->prepare("
            INSERT INTO tb_users (username, `full_name`, role_id, divisi_id, jabatan_id, password, status)
            VALUES (?, ?, ?, ?, ?, ?, 'active')
        ");
        $stmt->bind_param("ssssss", $username, $full_name, $role_id, $divisi_id, $jabatan_id, $password);

        if ($stmt->execute()) {
            // Redirect to users list after successful addition
            // header("Location: ?page=users");
            echo "<script>window.location.href = '?page=users';</script>";
            exit();
        } else {
            $errors[] = "Failed to add user: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Fetch roles, divisi, and jabatan options for the form
$roles = $conn->query("SELECT id, name FROM tb_role WHERE status = 'active'");
$divisi = $conn->query("SELECT id, name FROM tb_divisi WHERE status = 'active'");
$jabatan = $conn->query("SELECT id, name FROM tb_jabatan WHERE status = 'active'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Add New User</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Add New User</h3>

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

        <!-- Add User Form -->
        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="mb-3">
                <label for="full_name" class="form-label">full_name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo htmlspecialchars($full_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Role</label>
                <select id="role_id" name="role_id" class="form-select" required>
                    <option value="">Select Role</option>
                    <?php while ($row = $roles->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $role_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="divisi_id" class="form-label">Divisi</label>
                <select id="divisi_id" name="divisi_id" class="form-select">
                    <option value="">Select Divisi</option>
                    <?php while ($row = $divisi->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $divisi_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jabatan_id" class="form-label">Jabatan</label>
                <select id="jabatan_id" name="jabatan_id" class="form-select">
                    <option value="">Select Jabatan</option>
                    <?php while ($row = $jabatan->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $jabatan_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
            <a href="?page=users" class="btn btn-secondary">Cancel</a>
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
