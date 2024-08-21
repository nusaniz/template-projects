<?php
session_start(); // Start session to access session variables

// Check if the user is logged in and has an 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    // Redirect to login page if not authenticated or not an admin
    header('Location: ../login/');
    exit();
}

// Include necessary files
include 'includes/header.php';
include 'includes/sidebar.php';
?>






        

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <div class="content">
    <div class="container-fluid">
        <?php
            // Determine the page to include
            $page = isset($_GET['page']) ? $_GET['page'] : 'home';

            // Sanitize page parameter to prevent directory traversal
            // Ensure that the page parameter only contains alphanumeric characters, underscores, and dashes
            $page = preg_replace('/[^a-zA-Z0-9_-]/', '', $page);

            // Construct the file path
            $file = $page . '.php';

            // Check if the file exists in the current directory
            if (file_exists($file)) {
                include $file;
            } else {
                // Include a default or error page if the file is not valid
                include '404.php';
            }
        ?>
    </div>
</div>

        </div>

        <?php include 'includes/footer.php';?>