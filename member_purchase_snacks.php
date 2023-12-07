<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Snacks</title>
    <style>
        body
        {
            text-align: center; /* 居中显示文本 */
            padding: 20px; /* 增大上下行间距 */
            margin-bottom : 20px;
        }
    </style>
</head>
<body>
    
    <h1> 确认信息 </h1>
    <form method="post" action="member_purchase_snacks.php">
    <?php
    header("content-type:text/html; charset=UTF-8"); // 设置编码
    session_start();

    // 连接数据库（根据你的数据库信息进行修改）
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $database = "snack";
    $conn = new mysqli($servername, $username, $password, $database);

    // 获取用户选择的零食数量
    $snackQuantities = array();
    $totalPrice = 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        foreach ($_POST as $key => $value) 
        {
            if (strpos($key, 'snack_') === 0) 
            {
                $buy_snack_name = substr($key, 6); // 提取零食名称
                $buy_quantity = (int)$value;
                $buy_snackQuantities[$buy_snack_name] = $buy_quantity;

                // 查询零食的单价
                $query = "SELECT snack_price, snack_quantity FROM snacks WHERE snack_name = ?";
                $stmt1 = $conn->prepare($query);
                $stmt1->bind_param("s", $buy_snack_name);
                $stmt1->execute();
                $stmt1->bind_result($snack_price, $snack_quantity);
                $stmt1->fetch();
                $stmt1->close();
                

                $query = "UPDATE snacks SET snack_sold = snack_sold + ? WHERE snack_name = ?";
                $stmt2 = $conn->prepare($query);
                $stmt2->bind_param("is", $buy_quantity, $buy_snack_name);
                $stmt2->execute();
                $stmt2->close();
            
                // 计算单价
                $snack_monovalent = $snack_price / $snack_quantity;

                // 查询是实习还是正式成员
                $query = "SELECT member FROM users WHERE username = ?";
                $stmt3 = $conn->prepare($query);
                $stmt3->bind_param("s", $_SESSION["username"]);
                $stmt3->execute();
                $stmt3->bind_result($usermember);
                $stmt3->fetch();
                $stmt3->close();

                if($usermember == 1)
                    $discount = 0.2;  // 正式成员2折
                else
                    $discount = 0.5;  // 实习成员5折

                // 每一种零食的总价
                $buy_snack_per_price = number_format($discount * $snack_monovalent * $buy_snackQuantities[$buy_snack_name], 2);

                if($buy_quantity != 0)
                {
                    // 存储购买记录到数据库
                    $username = $_SESSION["username"];
                    // 在这里将购买记录插入到数据库，包括用户名、零食名称、购买数量等信息
                    // $conn = new mysqli($servername, $username, $password, $database);
                    $query = "INSERT INTO purchase_history (id, time, username, snack_name, quantity, price) VALUES (NULL, NULL, ?, ?, ?, ?)";
                    $stmt4 = $conn->prepare($query);
                    $stmt4->bind_param("sssd", $username, $buy_snack_name, $buy_quantity, $buy_snack_per_price);
                    $stmt4->execute();
                    $stmt4->close();
                }
                
                // 在表单中显示零食名称和每种零食的总价
                echo "<label for='snack_$buy_snack_name'>$buy_snack_name  数量：$buy_quantity (总价: $buy_snack_per_price 元)</label> <br><br>";
                // 计算总价
                $totalPrice += $buy_snack_per_price;
            }
        }
    }
    // 清空用户的购物车（根据需求可以在此删除 session 数据）

    // 关闭数据库连接
    $conn->close();
    ?>
    </form>

    <?php
    if (!empty($buy_snackQuantities))
    {
        echo "<h2>总价：$totalPrice 元</h2>";
    }
    ?>
    <br>
    <form method="post" action="http://192.168.1.107:80/userlogin.html">
    <br>
    <input type="submit" value="返回登录界面">
    <br>

    <h4>如果想支持一下开发人员^_^</h4>
    <img src="payphoto.jpg" alt="收款码" width="270" height="345.75">


</form>
</body>
</html>
