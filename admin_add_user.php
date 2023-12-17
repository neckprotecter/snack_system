<?php
header("Content-Type: application/json; charset=utf-8");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_username = $_POST["username"];
    $_password = $_POST["password"];
    $_role = $_POST["role"];
    $_member = $_POST["member"];

    // 连接到数据库
    // $conn = mysqli_connect("localhost", "root", "root", "snack");
    $conn = mysqli_connect("localhost", "root", "root1234", "snack");

    if (!$conn)
        die("数据库连接失败: " . mysqli_connect_error());

    $snackid = null;

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt0 = $conn->prepare($query);
    $stmt0->bind_param("s", $username);
    $stmt0->execute();
    $stmt0->store_result();

    if ($stmt0->fetch()) {
        $stmt0->close();

        echo json_encode(["success" => false, "message" => "用户已存在" . $stmt->error]);
    } else {
        $stmt0->close();

        $insert_query = "INSERT INTO users (username, password, role, member) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssi", $_username, $_password, $_role, $_member);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "The snack was successfully to added user."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error adding user: " . $stmt->error]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
