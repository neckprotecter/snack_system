<?php
header("Content-Type: application/json; charset=utf-8");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_userid = $_POST["userId"];
    $_username = $_POST["modify_username"];
    $_password = $_POST["modify_password"];
    $_role = $_POST["modify_role"];
    $_member = $_POST["modify_member"];
    echo("<script>console.log('".json_encode($_userid)."');</script>");
    echo("<script>console.log('".json_encode($_username)."');</script>");
    echo("<script>console.log('".json_encode($_password)."');</script>");
    echo("<script>console.log('".json_encode($_role)."');</script>");
    echo("<script>console.log('".json_encode($_member)."');</script>");

    // if($_role == '1')
    //     $_role = "admin";
    // else if($_role == '2')
    //     $_role = "member";


    // echo("<script>console.log('".json_encode($_role)."');</script>");
    // 连接到数据库
    // $conn = mysqli_connect("localhost", "root", "root", "snack");
    $conn = mysqli_connect("localhost", "root", "root1234", "snack");

    if (!$conn)
        die("数据库连接失败: " . mysqli_connect_error());

    $snackid = null;

    $query = "SELECT * FROM users WHERE id = ?";
    $stmt0 = $conn->prepare($query);
    $stmt0->bind_param("i", $_userid);
    $stmt0->execute();
    $stmt0->store_result();

    if (!$stmt0->fetch()) {
        $stmt0->close();

        echo json_encode(["success" => false, "message" => "用户不存在" . $stmt->error]);
    } else {
        $stmt0->close();

        $update_query = "UPDATE users SET username = ?, password = ?, role = ?, member = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssii", $_username, $_password, $_role, $_member, $_userid);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "The snack was successfully to modify user."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error modify user: " . $stmt->error]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
