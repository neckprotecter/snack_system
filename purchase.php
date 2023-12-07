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

$data = json_decode(file_get_contents("php://input"), true);

$_username = $data['username'];
$_snackname = $data['snackname'];
$_purchaseQuantity = $data['purchaseQuantity'];
$_totalprice = $data['totalprice'];
$_currentTime = date("Y-m-d H:i:s");

// 输出整个 JSON 数据
var_dump($data);

// 输出各个变量的值
// echo "Username: " . $_username . PHP_EOL;
// echo "Snackname: " . $_snackname . PHP_EOL;
// echo "Total Price: " . $_totalprice . PHP_EOL;
// echo "Purchase Quantity: " . $_purchaseQuantity . PHP_EOL;
// echo "Current Time: " . $_currentTime . PHP_EOL;

// $insertQuery = "INSERT INTO purchase_history (time, username, snack_name, quantity, price)
//                 VALUES ('$currentTime', '$username', $snackname, $purchaseQuantity, $totalprice)";

// 使用准备好的语句防止SQL注入
$query = "INSERT INTO purchase_history (id, time, username, snack_name, quantity, price) VALUES (NULL, NULL, ?, ?, ?, ?)";
$insertQuery = $conn->prepare($query);

// 绑定参数
$insertQuery->bind_param("sssd", $_username, $_snackname, $_purchaseQuantity, $_totalprice);

if ($insertQuery->execute()) {
    echo json_encode(['success' => true, 'message' => '购买成功']);
} 
else {
    echo json_encode(['success' => false, 'message' => '购买失败']);
}

// 关闭连接
$conn->close();
?>
