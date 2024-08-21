<?php
// session_start();
include '../config.php';  // Adjusted path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get search parameters from URL if available
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Define number of records per page
$limit = 10;

// Get current page number (default to 1 if not set)
$page = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;
$offset = ($page - 1) * $limit;

// Query to count total records matching the search criteria
$totalQuery = "
    SELECT COUNT(*) AS total 
    FROM tb_users 
    WHERE username LIKE '%$search%'
";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$total = $totalRow['total'];
$totalPages = ceil($total / $limit);

// Query to get data with search filter and pagination limits, including joins
$query = "
    SELECT 
        u.id, 
        u.username, 
        u.`full_name`, 
        u.`password`, 
        u.`created_at`, 
        u.`update_at`, 
        r.name AS role_name, 
        d.name AS divisi_name, 
        j.name AS jabatan_name 
    FROM tb_users u
    LEFT JOIN tb_role r ON u.role_id = r.id
    LEFT JOIN tb_divisi d ON u.divisi_id = d.id
    LEFT JOIN tb_jabatan j ON u.jabatan_id = j.id
    WHERE u.username LIKE '%$search%'
    LIMIT $limit OFFSET $offset
";
$result = $conn->query($query);

// Calculate range of displayed data
$startRange = $offset + 1;
$endRange = min($offset + $limit, $total);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Users List</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Users List</h3>

        <!-- Search Form -->
        <form method="get" class="mb-4">
            <div class="input-group mb-3">
                <input type="hidden" name="page" value="users">
                <input type="text" name="search" class="form-control" placeholder="Search by username" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">Search</button>
                <a href="?page=users" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Total Records Information -->
        <div class="mb-3">
            <p>Showing <?php echo $startRange; ?> to <?php echo $endRange; ?> of <?php echo $total; ?> users.</p>
        </div>

        <a href="?page=users_add" class="btn btn-primary mb-3">Add New User</a>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Username</th>
                        <th>full_name</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Created At</th>
                        <th>Update At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = $offset + 1;
                        while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['password']); ?></td>
                        <td><?php echo htmlspecialchars($row['role_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['divisi_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['jabatan_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['update_at']); ?></td>
                        <td>
                            <a href="?page=users_detail&id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="?page=users_edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?page=users_delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile;
                    } else {
                        echo "<tr><td colspan='8'>No users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=users&pg=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php
                    // Calculate start and end page numbers for pagination
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);

                    // Display page numbers
                    for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=users&pg=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=users&pg=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap JS Bundle with Popper from jsDelivr -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
