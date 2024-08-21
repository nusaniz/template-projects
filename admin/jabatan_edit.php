<?php
include '../config.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

// Get the position ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the position data from the database
$query = "SELECT * FROM tb_jabatan WHERE id = $id";
$result = mysqli_query($conn, $query);
$position = mysqli_fetch_assoc($result);

if (!$position) {
    echo "Position not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update the position
    $query = "UPDATE tb_jabatan SET name = '$name', status = '$status' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        // header("Location: ?page=jabatan");
        echo "<script>window.location.href= '?page=jabatan'</script>";
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Edit Position</title>
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Edit Position</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Position Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($position['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active" <?php echo ($position['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($position['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Position</button>
            <a href="?page=jabatan" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

<?php
?>
