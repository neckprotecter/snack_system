<?php
header("content-type:text/html; charset=utf8");         // 设置编码
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 获取表单提交的数据
    $snack_name = $_POST["snack_name"];
    $snack_quantity = $_POST["snack_quantity"];
    $snack_price = $_POST["snack_price"];

    $encoding = array('UTF-8', 'ASCII', 'GB2312', 'GBK');
    $utf8_snack_name = mb_convert_encoding($snack_name, "UTF-8", mb_detect_encoding($snack_name, $encoding));

    // 连接到数据库
    $conn = mysqli_connect("10.151.1.73", "root", "root", "snack");

    if (!$conn)
        die("数据库连接失败: " . mysqli_connect_error());

    $snackid = null; // 初始化 snackid

    // 查询是否有同名零食存在
    $query = "SELECT snack_id FROM snacks WHERE snack_name = ?";
    $stmt0 = $conn->prepare($query);
    $stmt0->bind_param("s", $utf8_snack_name);
    $stmt0->execute();
    $stmt0->store_result();
    $stmt0->bind_result($snackid);

    if ($stmt0->fetch())
    {
        // 零食已存在，执行更新操作
        $stmt0->close();

        $update_query = "UPDATE snacks SET snack_quantity = snack_quantity + ?, snack_price = snack_price + ? WHERE snack_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("idi", $snack_quantity, $snack_price, $snackid);

        if ($stmt->execute())
            echo "The snack was successfully added to inventory.";
        else
            echo "Error adding snacks: " . $stmt->error;
    }
    else
    {
        // 零食不存在，执行插入操作
        $stmt0->close();

        $insert_query = "INSERT INTO snacks (date, snack_name, snack_sold, snack_quantity, snack_price) VALUES (NULL, ?, 0, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sid", $utf8_snack_name, $snack_quantity, $snack_price);

        if ($stmt->execute())
            echo "The snack was successfully added to inventory.";
        else
            echo "Error adding snacks: " . $stmt->error;
    }

    header("Location: admin_dashboard.php");

    $stmt->close();
    $conn->close();
}
?>
