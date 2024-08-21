<?php
// session_start();
include '../config.php';  // Adjusted path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
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

// Check if user ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>window.location.href = '?page=users';</script>";
    exit();
}

$userId = (int)$_GET['id'];

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
    if (empty($username) || empty($full_name) || empty($role_id)) {
        $errors[] = "Username, full_name, and Role are required.";
    }

    if (empty($errors)) {
        // Prepare the SQL statement to update the user details
        $stmt = $conn->prepare("
            UPDATE tb_users 
            SET username = ?, `full_name` = ?, role_id = ?, divisi_id = ?, jabatan_id = ?,
                password = ?, update_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->bind_param("ssssssi", $username, $full_name, $role_id, $divisi_id, $jabatan_id, $password, $userId);

        if ($stmt->execute()) {
            // Redirect to users list after successful update
            echo "<script>window.location.href = '?page=users';</script>";
            exit();
        } else {
            $errors[] = "Failed to update user: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Fetch roles, divisi, and jabatan options for the form
$roles = $conn->query("SELECT id, name FROM tb_role WHERE status = 'active'");
$divisi = $conn->query("SELECT id, name FROM tb_divisi WHERE status = 'active'");
$jabatan = $conn->query("SELECT id, name FROM tb_jabatan WHERE status = 'active'");

// Retrieve existing user data
$query = "
    SELECT username, `full_name`, role_id, divisi_id, jabatan_id, password 
    FROM tb_users 
    WHERE id = $userId
";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "<script>window.location.href = '?page=users';</script>";
    exit();
}

$user = $result->fetch_assoc();
$username = htmlspecialchars($user['username']);
$full_name = htmlspecialchars($user['full_name']);
$role_id = htmlspecialchars($user['role_id']);
$divisi_id = htmlspecialchars($user['divisi_id']);
$jabatan_id = htmlspecialchars($user['jabatan_id']);
$password = htmlspecialchars($user['password']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Edit User</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Edit User</h3>

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

        <!-- Edit User Form -->
        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo $username; ?>" required>
            </div>
            <div class="mb-3">
                <label for="full_name" class="form-label">full_name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo $full_name; ?>" required>
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
                <input type="password" id="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <small class="form-text text-muted">Leave blank if you do not want to change the password.</small>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
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
