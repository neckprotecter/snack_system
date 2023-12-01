<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            padding: 20px;
            margin: 0;
            background-color: #ADD8E6;
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
            background-color: #fff;
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
            background-color: #fff;
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
    <p>你好，管理员。</p>
    <a href="logout.php">退出登录<br><br></a>

    <!-- 零食库存信息部分 -->
    <div class="dashboard-section">
        <h2>零食库存信息</h2>
        <?php
        
        ?>
    </div>

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
        </form>
    </div>

    <!-- 实时更新脚本 -->
    <script>
        var snackInputs = document.querySelectorAll('input[name^="snack_"]');

        snackInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                var snackName = this.name.substring(6);
                var remainingQuantity = parseInt(this.max) - parseInt(this.value);
                var label = document.querySelector('label[for="snack_' + snackName + '"]');
                label.innerHTML = snackName + ' (库存: ' + remainingQuantity + ')';
            });
        });
    </script>
</body>
</html>
