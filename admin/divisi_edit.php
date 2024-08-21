<?php
include '../config.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

// Get the division ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the current data for the division
$query = "SELECT * FROM tb_divisi WHERE id = $id";
$result = mysqli_query($conn, $query);
$division = mysqli_fetch_assoc($result);

if (!$division) {
    // header("Location: ?page=divisi");
    echo "<script>window.location.href= '?page=divisi'</script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update the division
    $query = "UPDATE tb_divisi SET name = '$name', status = '$status' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        // header("Location: ?page=divisi");
        echo "<script>window.location.href= '?page=divisi'</script>";
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
    <title><?php echo $web_name;?> | Edit Division</title>
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Edit Division</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Division Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($division['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active" <?php echo $division['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $division['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Division</button>
            <a href="?page=divisi" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

<?php
?>
