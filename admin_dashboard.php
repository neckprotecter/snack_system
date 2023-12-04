<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center; /* 居中显示文本 */
            padding: 20px; /* 增大上下行间距 */
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

        h1 {
            color: #333;
        }

        p {
            color: #555;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            color: #217dbb;
        }

        .dashboard-section {
            background-color: #dfffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-section h2 {
            color: #333;
        }

        .snack-info {
            margin-bottom: 20px;
        }

        .add-stock-form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #ADD8E6;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label, input {
            margin-top: 10px;
            margin-bottom: 10px;
            display: block;
            width: 100%; /* 让标签和输入框占满容器宽度 */
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>管理员库存管理页面</h1>
    <p>你好，管理员。</p> <!-- 显示用户的用户名 -->
    <a href="logout.php">退出登录<br><br></a> <!-- 提供注销链接 -->

    
    <!-- 添加库存部分 -->
    <div class="dashboard-section">
        <h2>添加库存</h2>
        <form class="add-stock-form" method="post" action="admin_add_snack.php">
            <label for="snack_name">零食名称：</label>
            <input type="text" name="snack_name" id="snack_name" required>
            
            <label for="snack_quantity">数量：</label>
            <input type="number" name="snack_quantity" id="snack_quantity" required>
            
            <label for="snack_price">进货价格：</label>
            <input type="number" name="snack_price" id="snack_price" step="0.01" required>
            
            <input type="submit" value="添加库存">
        </form> <!-- 换行 -->
    </div>
    
    <!-- 展示零食库存信息部分 -->
    <div class="dashboard-section">
        <h2>零食库存信息</h2>
        <?php
        header("content-type:text/html; charset=utf-8");         //设置编码
        session_start();

        $servername = "10.151.1.73"; // 数据库服务器主机名
        $username = "root"; // 数据库用户名
        $password = "root"; // 数据库密码
        $database = "snack"; // 数据库名称
        // 创建数据库连接
        $conn = new mysqli($servername, $username, $password, $database);

        // 查询数据库中零食的信息，包括零食名称、snack_quantity 和 snack_sold
        $query = "SELECT * FROM snacks ORDER BY snack_id";
        $result = $conn->query($query);  // conn是数据库连接对象，代表与数据库服务器的连接，query执行查询语句，返回一个结果集

        if ($result->num_rows > 0) {
            echo "<table border='1'>"; // 开始表格
        
            // 输出表头
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>时间</th>";
            echo "<th>零食名称</th>";
            echo "<th>总量</th>";
            echo "<th>总价</th>";
            echo "<th>已售</th>";
            echo "<th>单价</th>";
            echo "</tr>";
        
            // 输出每一行数据
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["snack_id"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>" . $row["snack_name"] . "</td>";
                echo "<td>" . $row["snack_quantity"] . "</td>";
                echo "<td>" . $row["snack_price"] . "</td>";
                echo "<td>" . $row["snack_sold"] . "</td>";
                echo "<td>" . number_format($row["snack_price"] / $row["snack_quantity"], 2) . "</td>";
                echo "</tr>";
            }
        
            echo "</table>"; // 结束表格
        } else {
            echo "查询到表单为空。";
        }
        ?>
    </div>

    <!-- 展示零食购买记录部分 -->
    <div class="dashboard-section">
        <h2>零食购买记录</h2>
        <?php
        // header("content-type:text/html; charset=utf-8");         //设置编码
        session_start();

        // 查询购买记录表中的信息，以表格的形式展出
        $query = "SELECT * FROM purchase_history ORDER BY id";
        $result = $conn->query($query);  // conn是数据库连接对象，代表与数据库服务器的连接，query执行查询语句，返回一个结果集

        if ($result->num_rows > 0) {
            echo "<table border='1'>"; // 开始表格
        
            // 输出表头
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>时间</th>";
            echo "<th>用户名</th>";
            echo "<th>零食名称</th>";
            echo "<th>数量</th>";
            echo "<th>价格</th>";
            echo "</tr>";
        
            // 输出每一行数据
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["time"] . "</td>";
                echo "<td>" . $row["username"] . "</td>";
                echo "<td>" . $row["snack_name"] . "</td>";
                echo "<td>" . $row["quantity"] . "</td>";
                echo "<td>" . $row["price"] . "</td>";
                echo "</tr>";
            }
        
            echo "</table>"; // 结束表格
        } else {
            echo "查询到表单为空。";
        }
        ?>
    </div>

    <!-- 实时更新，也许有用 -->
    <script>
        // 获取所有零食输入字段
        var snackInputs = document.querySelectorAll('input[name^="snack_"]');

        // 添加事件监听器以在输入字段更改时更新库存信息
        snackInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                // // 获取零食名称和库存输入字段的值
                // var snackName = this.name.substring(6);  // 去掉前缀 "snack_"
                // var remainingQuantity = parseInt(this.max) - parseInt(this.value);
                
                // 更新显示的库存信息
                var label = document.querySelector('label[for="snack_' + snackName + '"]');
                label.innerHTML = snackName + ' (库存: ' + remainingQuantity + ')';
            });
        });
    </script>


</body>
</html>
