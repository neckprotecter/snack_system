<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Dashboard</title>
    <style>
        body
        {
            text-align: center; /* 居中显示文本 */
            padding: 20px; /* 增大上下行间距 */
            margin-bottom : 20px;
        }
        form
        {
            display: inline-block; /* 使表单元素水平排列 */
        }
        label, input
        {
            margin-left : 5px; /* 增大上下行间距 */
            margin-right : 5px; /* 增大上下行间距 */
        }
    </style>
</head>
<body>
    <h1>Welcome to the Member Dashboard</h1>
    <p>你好,  <?php session_start(); echo $_SESSION["username"]; ?>! <!-- 显示用户的用户名 -->
    <!-- 在此添加成员仪表板的内容 -->
    
    <a href="logout.php"> 退出登录 <br> <br> </a> <!-- 提供注销链接 -->

    <div class="inventory">
        <h2>零食库存 <br> </h2>
        <form method="post" action="member_purchase_snacks.php">
            <?php
            header("content-type:text/html; charset=utf-8");         //设置编码
            session_start();

            $servername = "localhost"; // 数据库服务器主机名
            $username = "root"; // 数据库用户名
            $password = "root"; // 数据库密码
            $database = "snack"; // 数据库名称
            // 创建数据库连接
            $conn = new mysqli($servername, $username, $password, $database);

            // 查询数据库中零食的信息，包括零食名称、snack_quantity和snack_price
            $query = "SELECT snack_name, snack_sold, snack_quantity, snack_price FROM snacks";
            $result = $conn->query($query);

            if ($result->num_rows > 0)
            {
                while ($row = $result->fetch_assoc())
                {
                    $snack_name = $row["snack_name"];
                    $snack_quantity = $row["snack_quantity"];
                    $snack_price = $row["snack_price"];
                    $snack_sold = $row["snack_sold"];
                    $remaining_quantity = $snack_quantity - $snack_sold;
                    $snack_monovalent = number_format($snack_price / $snack_quantity, 2);  //单价，小数点后两位

                    // 在表单中显示零食信息，包括名称、数量、单价和让用户选择购买的数量
                    echo "<label for='snack_$snack_name'>$snack_name (库存: $remaining_quantity)</label>";
                    echo "<label>单价: $snack_monovalent</label>";
                    echo "<input type='number' name='snack_$snack_name' id='snack_$snack_name' value='0' min='0' max='$remaining_quantity'><br><br>";
                    
                }
            }
            else
            {
                echo "库存为空。";
            }
            ?>
            <br>
            <input type="submit" value="购买">
        </form>
    </div>
</body>
</html>
