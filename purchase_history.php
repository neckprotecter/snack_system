<?php
header("content-type: application/json; charset=utf-8");
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$database = "snack";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentUsername = $_SESSION['username'];

$query = "SELECT * FROM purchase_history WHERE username = ? ORDER BY id";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $currentUsername);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $records = array();
    $newId = 1; 

    while ($row = $result->fetch_assoc()) {
        $row['new_id'] = $newId++;
        $records[] = $row;
    }
    echo json_encode($records);
} else {
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>
