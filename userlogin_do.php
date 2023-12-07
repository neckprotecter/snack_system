<?php
header("Content-type: application/json; charset=UTF-8"); 
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost"; 
    $username = "root"; 
    $password = "root"; 
    $database = "snack"; 
    $responseData = [];
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        echo json_encode(['error' => '数据库连接失败']);
        exit;
    }
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_password = $row["password"];
        $user_role = $row["role"];
        if ($password === $user_password) {
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $user_role;
            
            if (!strcmp($_SESSION["role"], "admin")) {
                $responseData['username'] = $username;
                $responseData['redirect'] = 'admin_dashboard.html';
            } else {
                $responseData['username'] = $username;
                $responseData['redirect'] = 'member_dashboard.html';
            }
        } else {
            echo json_encode(['username' => $username, 'input_password' => $password, 'db_password' => $user_password]);
            $responseData['error'] = '密码不正确';
        }
    } else {
        $responseData['error'] = '用户名不存在';
    }
    $_SESSION["username"] = $username;
    echo json_encode($responseData);
    exit;
}



?>
