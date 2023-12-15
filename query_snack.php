<?php
header("content-type: application/json; charset=utf-8");
session_start();

$servername = "localhost";
$username = "root";
// $password = "root";
$password = "root1234";
$database = "snack";

$conn = new mysqli($servername, $username, $password, $database);

$snackname = $_GET['snackname'];
$searchInput = "%$snackname%";
$query = "SELECT snack_id, snack_name, snack_quantity, snack_sold, ROUND(snack_price / snack_quantity, 2) AS unit_price, (snack_quantity - snack_sold) AS remaining_quantity FROM snacks WHERE snack_name LIKE ?";
$stmt = $conn->prepare($query);

$stmt->bind_param("s", $searchInput);
$stmt->execute();

$result = $stmt->get_result();
$snackInventory = array();
if ($result->num_rows > 0) {
    $snackInventory = array();
    $newId = 1; 
    while ($row = $result->fetch_assoc()) {
        $row['new_id'] = $newId++;
        $snackInventory[] = $row;
    }
    echo json_encode($snackInventory);
} else {
    echo json_encode($snackInventory);
}

$stmt->close();
$conn->close();
?>
