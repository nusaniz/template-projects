<?php
// Database configuration
include '../config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS `tb_divisi` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) DEFAULT NULL,
    `status` enum('active','inactive') NOT NULL DEFAULT 'active',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute SQL
if ($conn->query($sql) === TRUE) {
    echo "Table `tb_divisi` created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// SQL to insert data
$insertData = [
    [1, 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi', 'active'],
    [2, 'Kementerian Kesehatan', 'active'],
    [3, 'Kementerian Pertahanan', 'active'],
    [4, 'Kementerian Keuangan', 'active'],
    [5, 'Kementerian Dalam Negeri', 'active'],
    [6, 'Kementerian Energi dan Sumber Daya Mineral', 'active'],
    [7, 'Kementerian Perhubungan', 'active'],
    [8, 'Kementerian Pekerjaan Umum dan Perumahan Rakyat', 'active'],
    [9, 'Kementerian Luar Negeri', 'active'],
    [10, 'Kementerian Agama', 'active'],
    [11, 'Kementerian Sosial', 'active'],
    [12, 'Kementerian Riset dan Teknologi', 'active'],
    [13, 'Kementerian Koperasi dan UKM', 'active'],
    [14, 'Kementerian Perdagangan', 'active'],
    [15, 'Kementerian Perindustrian', 'active'],
    [16, 'Kementerian Kelautan dan Perikanan', 'active'],
    [17, 'Kementerian Pertanian', 'active'],
    [18, 'Kementerian Pariwisata dan Ekonomi Kreatif', 'active'],
    [19, 'Kementerian Hukum dan HAM', 'active'],
    [20, 'Kementerian Komunikasi dan Informatika', 'active'],
    [21, 'Kementerian Pembangunan Daerah Tertinggal dan Transmigrasi', 'active'],
    [22, 'Kementerian Lingkungan Hidup dan Kehutanan', 'active'],
];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO `tb_divisi` (`id`, `name`, `status`) VALUES (?, ?, ?)");

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
