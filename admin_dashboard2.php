<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
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
    <h1>Welcome to the Admin Dashboard</h1>
    <p> 你好, 管理员。 <!-- 显示用户的用户名 -->
    <!-- 在此添加成员仪表板的内容 -->
    
    <a href="logout.php"> 退出登录 <br> <br> </a> <!-- 提供注销链接 -->

    <div>
        <h2>零食库存信息</h2>
        <?php
        header("content-type:text/html; charset=utf-8");         //设置编码
        session_start();

        $servername = "localhost"; // 数据库服务器主机名
        $username = "root"; // 数据库用户名
        $password = "root"; // 数据库密码
        $database = "snack"; // 数据库名称
        // 创建数据库连接
        $conn = new mysqli($servername, $username, $password, $database);

        // 查询数据库中零食的信息，包括零食名称、snack_quantity 和 snack_sold
        $query = "SELECT snack_name, snack_quantity, snack_sold FROM snacks";
        $result = $conn->query($query);  // conn是数据库连接对象，代表与数据库服务器的连接，query执行查询语句，返回一个结果集

        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc()) {
                $snack_name = $row["snack_name"];
                $snack_quantity = $row["snack_quantity"];
                $snack_sold = $row["snack_sold"];
                $remaining_quantity = $snack_quantity - $snack_sold;

                // 在表单中显示零食名称和库存
                echo "<label for='snack_$snack_name'>$snack_name (库存: $remaining_quantity) <br><br> </label> \n";
            }
        }
        else
        {
            echo "查询到表单为空。";
        }
        ?>
    </div>

    <div>
        <h2>添加库存</h2>
        <!-- 添加库存 -->
        <form method="post" action="admin_add_snack.php">
            <label for="snack_name">零食名称：</label>
            <input type="text" name="snack_name" id="snack_name" required>
            
            <label for="snack_quantity">数量：</label>
            <input type="number" name="snack_quantity" id="snack_quantity" required>
            
            <label for="snack_price">进货价格：</label>
            <input type="number" name="snack_price" id="snack_price" step="0.01" required>
            
            <input type="submit" value="添加库存">
            <br>  <!-- 换行 -->
        </form>
    </div>

<!-- 实时更新，也许有用 -->
    <script>
        // 获取所有零食输入字段
        var snackInputs = document.querySelectorAll('input[name^="snack_"]');

        // 添加事件监听器以在输入字段更改时更新库存信息
        snackInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                // 获取零食名称和库存输入字段的值
                var snackName = this.name.substring(6); // 去掉前缀 "snack_"
                var remainingQuantity = parseInt(this.max) - parseInt(this.value);

                // 更新显示的库存信息
                var label = document.querySelector('label[for="snack_' + snackName + '"]');
                label.innerHTML = snackName + ' (库存: ' + remainingQuantity + ')';
            });
        });
    </script>


</body>
</html>



