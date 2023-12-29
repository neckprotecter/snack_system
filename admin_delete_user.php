<?php
header("Content-Type: application/json; charset=utf-8");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_id = $_POST["userId"];

    // 连接到数据库
    // $conn = mysqli_connect("localhost", "root", "root", "snack");
    $conn = mysqli_connect("localhost", "root", "root1234", "snack");

    if (!$conn)
        die("数据库连接失败: " . mysqli_connect_error());

    $snackid = null;
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt0 = $conn->prepare($query);
    $stmt0->bind_param("i", $_id);
    $stmt0->execute();
    $stmt0->store_result();
    // $stmt0->bind_result($snackid);

    if (!$stmt0->fetch()) {
        $stmt0->close();

        echo json_encode(["success" => false, "message" => "用户不存在" . $stmt->error]);
    } else {
        $stmt0->close();

        $delete_query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "The snack was successfully to delete user."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error delete: " . $stmt->error]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
