<?php
include '../config.php';  // Adjust path to config.php

// Get user_id from the request
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$response = array('divisi' => array(), 'jabatan' => array());

if ($user_id) {
    // Fetch divisi and jabatan for the given user
    $query = "
        SELECT d.id AS divisi_id, d.name AS divisi_name, j.id AS jabatan_id, j.name AS jabatan_name
        FROM tb_users u
        LEFT JOIN tb_divisi d ON u.divisi_id = d.id
        LEFT JOIN tb_jabatan j ON u.jabatan_id = j.id
        WHERE u.id = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $response['divisi'][] = array('id' => $row['divisi_id'], 'name' => $row['divisi_name']);
            $response['jabatan'][] = array('id' => $row['jabatan_id'], 'name' => $row['jabatan_name']);
        }
        
        $stmt->close();
    }
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
