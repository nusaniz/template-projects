<?php
include '../config.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
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

// Query to count total records matching the search criteria
$totalQuery = "
    SELECT COUNT(*) AS total 
    FROM tb_jabatan 
    WHERE name LIKE '%$search%'
";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$total = $totalRow['total'];
$totalPages = ceil($total / $limit);

// Query to get data with search filter and pagination limits
$query = "
    SELECT id, name, status 
    FROM tb_jabatan 
    WHERE name LIKE '%$search%'
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
    <title><?php echo $web_name;?> | Positions List</title>
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Positions List</h3>

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
                Berisi data jabatan dalam sebuah divisi atau lembaga dll. <br>
                Contoh: <a href="../assets/jabatan.txt">Jabatan</a> <br>
                <a href="?page=install_jabatan" class="btn btn-sm btn-warning">Install Jabatan</a>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- Search Form -->
        <form method="get" class="mb-4">
            <div class="input-group mb-3">
                <input type="hidden" name="page" value="jabatan">
                <input type="text" name="search" class="form-control" placeholder="Search by position name" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">Search</button>
                <a href="?page=jabatan" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Total Records Information -->
        <div class="mb-3">
            <p>Showing <?php echo $startRange; ?> to <?php echo $endRange; ?> of <?php echo $total; ?> positions.</p>
        </div>

        <a href="?page=jabatan_add" class="btn btn-primary mb-3">Add New Position</a>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Position Name</th>
                        <th>Status</th>
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
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <a href="?page=jabatan_edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?page=jabatan_delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this position?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile;
                    } else {
                        echo "<tr><td colspan='5'>No positions found.</td></tr>";
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
                    <a class="page-link" href="?page=jabatan&pg=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
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
                    <a class="page-link" href="?page=jabatan&pg=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=jabatan&pg=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</body>
</html>

<?php
?>
