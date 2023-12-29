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
$query = "SELECT snack_id, date, snack_name, snack_quantity, snack_price, snack_sold, (snack_quantity - snack_sold) AS remaining_quantity, ROUND(snack_price / snack_quantity, 2) AS unit_price FROM snacks WHERE snack_name LIKE ? and (snack_quantity - snack_sold) > 0 ORDER BY snack_id";
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
