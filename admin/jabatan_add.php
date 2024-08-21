<?php
include '../config.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "INSERT INTO tb_jabatan (name, status) VALUES ('$name', '$status')";
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
    <title><?php echo $web_name;?> | Add New Position</title>
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Add New Position</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Position Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Position</button>
            <a href="?page=jabatan" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

<?php
?>