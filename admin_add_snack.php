<?php
header("Content-Type: application/json; charset=utf-8");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $snack_name = $_POST["snack_name"];
    $snack_quantity = $_POST["snack_quantity"];
    $snack_price = $_POST["snack_price"];
    $encoding = array('UTF-8', 'ASCII', 'GB2312', 'GBK');
    $utf8_snack_name = mb_convert_encoding($snack_name, "UTF-8", mb_detect_encoding($snack_name, $encoding));

    // 连接到数据库
    $conn = mysqli_connect("localhost", "root", "root", "snack");

    if (!$conn)
        die("数据库连接失败: " . mysqli_connect_error());

    $snackid = null;

    $query = "SELECT snack_id FROM snacks WHERE snack_name = ?";
    $stmt0 = $conn->prepare($query);
    $stmt0->bind_param("s", $utf8_snack_name);
    $stmt0->execute();
    $stmt0->store_result();
    $stmt0->bind_result($snackid);

    if ($stmt0->fetch()) {
        $stmt0->close();

        $update_query = "UPDATE snacks SET snack_quantity = snack_quantity + ?, snack_price = snack_price + ? WHERE snack_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("idi", $snack_quantity, $snack_price, $snackid);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "The snack was successfully added to inventory."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error adding snacks: " . $stmt->error]);
        }
    } else {
        $stmt0->close();

        $insert_query = "INSERT INTO snacks (date, snack_name, snack_sold, snack_quantity, snack_price) VALUES (NULL, ?, 0, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sid", $utf8_snack_name, $snack_quantity, $snack_price);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "The snack was successfully added to inventory."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error adding snacks: " . $stmt->error]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
