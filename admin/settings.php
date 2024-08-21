<?php
// session_start();

// Check if the user is logged in and has an 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    // Redirect to login page if not authenticated or not an admin
    header('Location: ../login/');
    exit();
}

// Include setup.php to get current settings
include '../setup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_web_name = $_POST['web_name'];
    $new_web_logo = $_POST['web_logo'];
    $new_web_version = $_POST['web_version'];
    $new_web_email = $_POST['web_email'];
    $new_phone = $_POST['phone'];
    
    // Validate input (basic example)
    if (!empty($new_web_name) && !empty($new_web_logo) && !empty($new_web_version) && !empty($new_web_email) && !empty($new_phone)) {
        // Update setup.php with new values
        $setup_content = "<?php\n\n";
        $setup_content .= "\$web_name = '" . addslashes($new_web_name) . "';\n";
        $setup_content .= "\$web_logo = '" . addslashes($new_web_logo) . "';\n";
        $setup_content .= "\$web_version = '" . addslashes($new_web_version) . "';\n";
        $setup_content .= "\$web_email = '" . addslashes($new_web_email) . "';\n";
        $setup_content .= "\$phone = '" . addslashes($new_phone) . "';\n";
        $setup_content .= "\n?>";
        
        // Write to setup.php
        file_put_contents('../setup.php', $setup_content);
        
        // Feedback message
        $message = "Settings updated successfully!";
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($web_name); ?> | Settings</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h1 class="mb-4">Settings</h1>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Informasi</h3>
                <div class="card-tools">
                <!-- Buttons, labels, and many other things can be placed here! -->
                <!-- Here is a label for example -->
                <span class="badge badge-primary">Informasi</span>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                Pengaturan website file (../setup.php).
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        
        <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="web_name" class="form-label">Website Name:</label>
                <input type="text" id="web_name" name="web_name" class="form-control" value="<?php echo htmlspecialchars($web_name); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="web_logo" class="form-label">Logo Path:</label>
                <input type="text" id="web_logo" name="web_logo" class="form-control" value="<?php echo htmlspecialchars($web_logo); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="web_version" class="form-label">Version:</label>
                <input type="text" id="web_version" name="web_version" class="form-control" value="<?php echo htmlspecialchars($web_version); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="web_email" class="form-label">Email:</label>
                <input type="email" id="web_email" name="web_email" class="form-control" value="<?php echo htmlspecialchars($web_email); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script> -->
</body>
</html>
