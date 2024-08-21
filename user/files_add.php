<?php
// session_start();
include '../config.php'; // Include your database connection

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'user') {
    header("Location: ../login/");
    exit();
}

// Initialize variables
$errors = [];
$file_name = '';
$status = 'active';
$file_hash = '';
$description = ''; // Added description

// Retrieve user details from the session
$user_id = $_SESSION['user_id'];
$divisi_id = $_SESSION['divisi_id'];
$jabatan_id = $_SESSION['jabatan_id'];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $file_name = trim($_POST['file_name']);
    $status = trim($_POST['status']);
    $description = trim($_POST['description']); // Retrieve description

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_original_name = basename($_FILES['file']['name']); // Extract the base name of the file

        // Generate a unique prefix and rename the file
        $unique_prefix = substr(uniqid(time(), true), 0, 10);
        $file_base_name = $unique_prefix . '_' . $file_original_name; // New file name
        $upload_file = $upload_dir . $file_base_name;

        // Validate file upload
        if (move_uploaded_file($file_tmp_name, $upload_file)) {
            // Generate file hash
            $file_hash = hash_file('sha256', $upload_file);

            // Prepare and execute SQL statement to insert new file
            $stmt = $conn->prepare("
                INSERT INTO tb_file (file_name, file, file_hash, divisi_id, jabatan_id, status, user_id, description, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param("ssssssis", $file_name, $file_base_name, $file_hash, $divisi_id, $jabatan_id, $status, $user_id, $description);
            
            if ($stmt->execute()) {
                // Redirect to files list after successful insertion
                echo "<script>window.location.href= '?page=files'</script>";
                exit();
            } else {
                $errors[] = 'Failed to add file. Please try again.';
            }
            
            // Close the statement
            $stmt->close();
        } else {
            $errors[] = 'Failed to upload file. Please try again.';
        }
    } else {
        $errors[] = 'Please upload a file.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Add File</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Add New File</h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="file_name" class="form-label">File Name:</label>
                <input type="text" class="form-control" id="file_name" name="file_name" value="<?php echo htmlspecialchars($file_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Upload File:</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add File</button>
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
