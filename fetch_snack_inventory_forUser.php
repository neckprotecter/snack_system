<?php
header("content-type: application/json; charset=utf-8");
session_start();

$servername = "localhost"; 
$username = "root"; 
// $password = "root"; 
$password = "root1234"; 
$database = "snack"; 

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $database);

$query = "SELECT snack_id, snack_name, snack_quantity, snack_sold, ROUND(snack_price / snack_quantity, 2) AS unit_price, (snack_quantity - snack_sold) AS remaining_quantity FROM snacks WHERE (snack_quantity - snack_sold) > 0 ORDER BY snack_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $snackInventory = array();

    while ($row = $result->fetch_assoc()) {
        $snackInventory[] = $row;
    }
    echo json_encode($snackInventory);
} else {
    echo json_encode(array('message' => '查询到表单为空。'));
}

$conn->close();
?>
