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

// 查询数据库中用户的信息
$query = "SELECT * FROM users ORDER BY user_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $usermessage = array();

    while ($row = $result->fetch_assoc()) {
        $usermessage[] = $row;
    }

    echo json_encode($usermessage);
} else {
    echo json_encode(array('message' => '查询到表单为空。'));
}

$conn->close();
?>
