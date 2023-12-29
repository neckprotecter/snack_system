<?php
header("content-type: application/json; charset=utf-8");
session_start();

$servername = "localhost";
$username = "root";
// $password = "root";
$password = "root1234";
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

// 查询是实习还是正式成员
$query = "SELECT member FROM users WHERE username = ?";
$stmt1 = $conn->prepare($query);
$stmt1->bind_param("s", $_SESSION["username"]);
$stmt1->execute();
$stmt1->bind_result($usermember);
$stmt1->fetch();
$stmt1->close();

if($usermember == 1)
    $discount = 0.2;  // 正式成员2折
else
    $discount = 0.5;  // 实习成员5折

// 折扣计算
$_totalprice = number_format($discount * $_totalprice, 2);

// 使用准备好的语句防止SQL注入
$query = "INSERT INTO purchase_history (purchase_id, time, username, snack_name, quantity, price) VALUES (NULL, NULL, ?, ?, ?, ?)";
$insertQuery = $conn->prepare($query);

// 绑定参数
$insertQuery->bind_param("sssd", $_username, $_snackname, $_purchaseQuantity, $_totalprice);

if ($insertQuery->execute()) {
    echo json_encode(['success' => true, 'message' => '购买成功']);
} 
else {
    echo json_encode(['success' => false, 'message' => '购买失败']);
}
$insertQuery->close();

// 查询购买历史中某种零食的购买次数
$query = "SELECT SUM(quantity) FROM purchase_history WHERE snack_name = ?";
$stmt3 = $conn->prepare($query);
$stmt3->bind_param("s", $_snackname);
$stmt3->execute();
$stmt3->bind_result($purchaseCount);
$stmt3->fetch();
$stmt3->close();

// 更新 snacks 表的 snack_sold
$query = "UPDATE snacks SET snack_sold = ? WHERE snack_name = ?";
$stmt4 = $conn->prepare($query);
// $newSnackSold = $purchaseCount + $_purchaseQuantity;
$stmt4->bind_param("is", $purchaseCount, $_snackname);
$stmt4->execute();
$stmt4->close();

// 关闭连接
$conn->close();
?>
