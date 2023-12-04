<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Dashboard</title>
    <style>
        body {
            text-align: center; /* 居中显示文本 */
            padding: 20px; /* 增大上下行间距 */
            /* margin-bottom : 20px; */
            margin: 0;
            background-color: #ADD8E6;
        }

        table {
            width: 80%; /* 设置表格宽度为页面宽度的80% */
            margin: 20px auto; /* 设置表格在水平方向上居中，并在上下方向上有一些间距 */
            border-collapse: collapse; /* 合并表格边框，使得表格看起来更整齐 */
        }
        
        th, td {
            padding: 10px; /* 为表头和单元格添加内边距，给文字留出左右空位 */
            text-align: center; /* 居中显示文字 */
        }

        .inventory {
            background-color: #dfffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            width: 100%;
            margin: 0 auto;
            display: inline-block; /* 使表单元素水平排列 */
        }

        label, input {
            margin-left : 5px; /* 增大上下行间距 */
            margin-right : 5px; /* 增大上下行间距 */
        }
    </style>
</head>
<body>
    <h1>欢迎使用零食系统-v-</h1>
    <p>你好,  <?php session_start(); echo $_SESSION["username"]; ?>! <!-- 显示用户的用户名 -->
    <!-- 在此添加成员仪表板的内容 -->
    
    <a href="logout.php"> 退出登录 <br> <br> </a> <!-- 提供注销链接 -->

    <div class="inventory">
        <h2>零食库存 <br> </h2>
        <form method="post" action="member_purchase_snacks.php">
            <?php
            header("content-type:text/html; charset=utf-8");         //设置编码
            session_start();

            $servername = "10.151.1.73"; // 数据库服务器主机名
            $username = "root"; // 数据库用户名
            $password = "root"; // 数据库密码
            $database = "snack"; // 数据库名称
            // 创建数据库连接
            $conn = new mysqli($servername, $username, $password, $database);

            // 查询数据库中零食的信息，包括零食名称、snack_quantity和snack_price
            $query = "SELECT snack_name, snack_sold, snack_quantity, snack_price FROM snacks";
            $result = $conn->query($query);


            if ($result->num_rows > 0) {
                echo "<table border='1'>"; // 开始表格
            
                // 输出表头
                echo "<tr>";
                echo "<th>零食名称</th>";
                echo "<th>总量</th>";
                echo "<th>总价</th>";
                echo "<th>已售</th>";
                echo "<th>单价</th>";
                echo "<th>购买数量</th>";
                echo "</tr>";
            
                // 输出每一行数据
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["snack_name"] . "</td>";
                    echo "<td>" . $row["snack_quantity"] . "</td>";
                    echo "<td>" . $row["snack_price"] . "</td>";
                    echo "<td>" . $row["snack_sold"] . "</td>";
                    echo "<td>" . number_format($row["snack_price"] / $row["snack_quantity"], 2) . "</td>";
                    echo "<td>" . "<input type='number' name='snack_" . $row["snack_name"] . "' value='0' min='0' max='" . ($row["snack_quantity"] - $row["snack_sold"]) . "'>" . "</td>";
                    echo "</tr>";
                }
            
                echo "</table>"; // 结束表格
            } 
            else {
                echo "库存为空。";
            }

            ?>
            <br>
            <input type="submit" value="购买">
        </form>
    </div>
</body>
</html>
