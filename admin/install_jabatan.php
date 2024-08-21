<?php
// Database configuration
include '../config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS `tb_jabatan` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `status` enum('active','inactive') NOT NULL DEFAULT 'active',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute SQL
if ($conn->query($sql) === TRUE) {
    echo "Table `tb_jabatan` created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// SQL to insert data
$insertData = [
    [1, 'Menteri', 'active'],
    [2, 'Wakil Menteri', 'active'],
    [3, 'Sekretaris Jenderal', 'active'],
    [4, 'Direktur Jenderal', 'active'],
    [5, 'Direktur', 'active'],
    [6, 'Kepala Biro', 'active'],
    [7, 'Kepala Subdirektorat', 'active'],
    [8, 'Kepala Bagian', 'active'],
    [9, 'Kepala Unit', 'active'],
    [10, 'Koordinator', 'active'],
    [11, 'Staf Ahli', 'active'],
    [12, 'Pakar', 'active'],
    [13, 'Asisten', 'active'],
    [14, 'Kasubag', 'active'],
    [15, 'Kasubdit', 'active'],
    [16, 'Kasie', 'active'],
    [17, 'Kepala Sekretariat', 'active'],
    [18, 'Kepala Departemen', 'active'],
    [19, 'Pengawas', 'active'],
    [20, 'Kepala Divisi', 'active'],
];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO `tb_jabatan` (`id`, `name`, `status`) VALUES (?, ?, ?)");

foreach ($insertData as $row) {
    $stmt->bind_param("iss", $row[0], $row[1], $row[2]);
    if ($stmt->execute()) {
        echo "New record created successfully with id: " . $row[0] . "<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
