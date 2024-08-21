<?php
session_start();
include '../config.php'; // Include your database connection

// Check if the user is logged in and has the "user" role
// if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'user') {
//     header("Location: ../login/");
//     exit();
// }

// Initialize variables
$errors = [];
$validation_message = '';
$alert_class = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_original_name = basename($_FILES['file']['name']); // Extract the base name of the file
        
        // Generate file hash
        $file_hash = hash_file('sha256', $file_tmp_name);
        
        // Check if the file hash exists in the tb_file table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tb_file WHERE file_hash = ?");
        $stmt->bind_param("s", $file_hash);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        if ($count > 0) {
            $validation_message = 'Valid';
            $alert_class = 'alert-success'; // Green color for success
            $status = 'valid';
        } else {
            $validation_message = 'Invalid';
            $alert_class = 'alert-danger'; // Red color for danger
            $status = 'invalid';
        }
        
        // Insert the file hash into tb_file_verification
        $stmt = $conn->prepare("
            INSERT INTO tb_file_verification (file, file_hash, status, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("sss", $file_original_name, $file_hash, $status);
        
        if (!$stmt->execute()) {
            $errors[] = 'Failed to verify file. Please try again.';
        }
        
        $stmt->close();
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
    <title>Verify File</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Verify File</h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php foreach ($errors as $error): ?>
                    <p style=" margin: 0; "><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($validation_message)): ?>
            <div class="alert <?php echo htmlspecialchars($alert_class); ?> alert-dismissible fade show">
                <p style=" margin: 0; "><?php echo htmlspecialchars($validation_message); ?></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="file" class="form-label">Upload File:</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify File</button>
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
