<?php
// session_start();
include '../config.php'; // Adjust path to config.php

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'user') {
    header("Location: ../login/");
    exit();
}

// Get search parameters from URL if available
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Define number of records per page
$limit = 10;

// Get current page number (default to 1 if not set)
$page = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;
$offset = ($page - 1) * $limit;

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Query to count total records matching the search criteria for the logged-in user
$totalQuery = "
    SELECT COUNT(*) AS total 
    FROM tb_file 
    WHERE user_id = ? AND file_name LIKE ?
";
$stmt = $conn->prepare($totalQuery);
$searchTerm = "%$search%";
$stmt->bind_param("is", $user_id, $searchTerm);
$stmt->execute();
$totalResult = $stmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$total = $totalRow['total'];
$totalPages = ceil($total / $limit);

// Query to get data with search filter and pagination limits for the logged-in user
$query = "
    SELECT 
        f.id, 
        f.uuid, 
        f.file_name, 
        f.file, 
        f.file_hash, 
        f.divisi_id, 
        f.jabatan_id, 
        f.status, 
        f.description, 
        f.created_at, 
        f.update_at,
        f.user_id,
        u.username,
        u.`full_name` AS full_name,  -- Added user details
        d.name AS divisi_name,
        j.name AS jabatan_name
    FROM tb_file f
    LEFT JOIN tb_divisi d ON f.divisi_id = d.id
    LEFT JOIN tb_jabatan j ON f.jabatan_id = j.id
    LEFT JOIN tb_users u ON f.user_id = u.id  -- Join with tb_users to get username and full_name
    WHERE f.user_id = ? AND f.file_name LIKE ?
    LIMIT ? OFFSET ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("issi", $user_id, $searchTerm, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Calculate range of displayed data
$startRange = $offset + 1;
$endRange = min($offset + $limit, $total);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | My Files</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">My Files</h3>

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
                Masukkan data file dokumen yang ingin divalidasi atau rilis.
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- Search Form -->
        <form method="get" class="mb-4">
            <div class="input-group mb-3">
                <input type="hidden" name="page" value="files">
                <input type="text" name="search" class="form-control" placeholder="Search by file name" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">Search</button>
                <a href="?page=files" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Total Records Information -->
        <div class="mb-3">
            <p>Showing <?php echo $startRange; ?> to <?php echo $endRange; ?> of <?php echo $total; ?> files.</p>
        </div>

        <a href="?page=files_add" class="btn btn-primary mb-3">Add New File</a>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>UUID</th>
                        <th>Username</th> <!-- New column header -->
                        <th>Full Name</th> <!-- New column header -->
                        <th>File Name</th>
                        <th>Description</th>
                        <th>File</th>
                        <th>File Hash</th>
                        <th>Divisi ID</th>
                        <th>Divisi Name</th>
                        <th>Jabatan ID</th>
                        <th>Jabatan Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
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
                        <td><?php echo htmlspecialchars($row['uuid']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td> <!-- New column data -->
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td> <!-- New column data -->
                        <td><?php echo htmlspecialchars($row['file_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['file']); ?></td>
                        <td><?php echo htmlspecialchars($row['file_hash']); ?></td>
                        <td><?php echo htmlspecialchars($row['divisi_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['divisi_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['jabatan_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['jabatan_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['update_at']); ?></td>
                        <td>
                            <a href="?page=files_detail&id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="?page=files_edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?page=files_delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this file?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile;
                    } else {
                        echo "<tr><td colspan='15'>No files found.</td></tr>";
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
                    <a class="page-link" href="?page=files&pg=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
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
                    <a class="page-link" href="?page=files&pg=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=files&pg=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
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
