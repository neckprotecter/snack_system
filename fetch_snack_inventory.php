<?php
header("content-type: application/json; charset=utf-8");
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$database = "snack"; 

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $database);

// 查询数据库中零食的信息，包括零食名称、snack_quantity 和 snack_sold
$query = "SELECT snack_id, date, snack_name, snack_quantity, snack_price, snack_sold, ROUND(snack_price / snack_quantity, 2) AS unit_price FROM snacks ORDER BY snack_id";
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
