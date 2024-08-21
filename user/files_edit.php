<?php
session_start(); // Ensure session management is enabled
include '../config.php'; // Include your database connection

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'user') {
    header("Location: ../login/");
    exit();
}

// Initialize variables
$errors = [];
$file_name = '';
$file_path = '';
$file_hash = '';
$description = ''; // Initialize description variable
$status = 'active';
$file_id = null;

// Retrieve user details from the session
$user_id = $_SESSION['user_id'];
$divisi_id = $_SESSION['divisi_id'];
$jabatan_id = $_SESSION['jabatan_id'];

// Retrieve file ID from the query string
if (isset($_GET['id'])) {
    $file_id = (int)$_GET['id'];

    // Fetch the existing file details
    $stmt = $conn->prepare("
        SELECT file_name, file, file_hash, status, description
        FROM tb_file
        WHERE id = ? AND user_id = ?
    ");
    $stmt->bind_param("ii", $file_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $file_data = $result->fetch_assoc();
        $file_name = $file_data['file_name'];
        $file_path = $file_data['file'];
        $file_hash = $file_data['file_hash'];
        $status = $file_data['status'];
        $description = $file_data['description']; // Retrieve description
    } else {
        $errors[] = 'File not found or you do not have permission to edit this file.';
    }
    $stmt->close();
} else {
    $errors[] = 'No file ID provided.';
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $new_file_name = trim($_POST['file_name']);
    $new_status = trim($_POST['status']);
    $new_description = isset($_POST['description']) ? trim($_POST['description']) : NULL;

    // Prepare SQL parts for the update query
    $update_fields = [];
    $params = [];
    
    // Check if file name has changed
    if ($new_file_name !== $file_name) {
        $update_fields[] = 'file_name = ?';
        $params[] = $new_file_name;
    }
    
    // Check if status has changed
    if ($new_status !== $status) {
        $update_fields[] = 'status = ?';
        $params[] = $new_status;
    }
    
    // Check if description has changed
    if ($new_description !== $description) {
        $update_fields[] = 'description = ?';
        $params[] = $new_description;
    }
    
    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_original_name = basename($_FILES['file']['name']); // Extract the base name of the file

        // Generate a unique prefix and rename the file
        $unique_prefix = substr(uniqid(time(), true), 0, 10);
        $file_base_name = $unique_prefix . '_' . $file_original_name; // New file name
        $upload_file = $upload_dir . $file_base_name;

        // Validate and move uploaded file
        if (move_uploaded_file($file_tmp_name, $upload_file)) {
            // Generate new file hash
            $file_hash = hash_file('sha256', $upload_file);

            // Check if file path has changed
            if ($file_base_name !== $file_path) {
                $update_fields[] = 'file = ?';
                $params[] = $file_base_name;
                
                // Optionally, delete the old file
                if (file_exists($upload_dir . $file_path)) {
                    unlink($upload_dir . $file_path);
                }
            }

            // Always update file hash when file is uploaded
            $update_fields[] = 'file_hash = ?';
            $params[] = $file_hash;
        } else {
            $errors[] = 'Failed to upload file. Please try again.';
        }
    }
    
    // Append the update timestamp
    $update_fields[] = 'update_at = NOW()';

    // Check if there are any fields to update
    if (!empty($update_fields)) {
        // Construct the SQL query
        $sql = "UPDATE tb_file SET " . implode(', ', $update_fields) . " WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $params[] = $file_id;
        $params[] = $user_id;
        $types = str_repeat('s', count($params) - 2) . 'ii'; // Determine the types for the parameters
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            // Redirect to files list after successful update
            echo "<script>window.location.href= '?page=files'</script>";
            exit();
        } else {
            $errors[] = 'Failed to update file. Please try again.';
        }
        
        $stmt->close();
    } else {
        $errors[] = 'No changes detected.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Edit File</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Edit File</h3>

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
                <label for="file" class="form-label">Upload New File (optional):</label>
                <input type="file" class="form-control" id="file" name="file">
                <small class="form-text text-muted">Leave blank if you do not want to change the file.</small>
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
            <button type="submit" class="btn btn-primary">Update File</button>
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
