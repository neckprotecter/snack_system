<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <style>
        body
        {
            text-align: center; /* ������ʾ�ı� */
            padding: 20px; /* ���������м�� */
            margin-bottom : 20px;
        }
        form
        {
            display: inline-block; /* ʹ��Ԫ��ˮƽ���� */
        }
        label, input
        {
            margin-left : 5px; /* ���������м�� */
            margin-right : 5px; /* ���������м�� */
        }
    </style>
</head>
<body>
    <h1>Welcome to the Admin Dashboard</h1>
    <p> ���, ����Ա�� <!-- ��ʾ�û����û��� -->
    <!-- �ڴ���ӳ�Ա�Ǳ������� -->
    
    <a href="logout.php"> �˳���¼ <br> <br> </a> <!-- �ṩע������ -->

    <div>
        <h2>��ʳ�����Ϣ</h2>
        <?php
        header("content-type:text/html; charset=utf-8");         //���ñ���
        session_start();

        $servername = "localhost"; // ���ݿ������������
        $username = "root"; // ���ݿ��û���
        $password = "root"; // ���ݿ�����
        $database = "snack"; // ���ݿ�����
        // �������ݿ�����
        $conn = new mysqli($servername, $username, $password, $database);

        // ��ѯ���ݿ�����ʳ����Ϣ��������ʳ���ơ�snack_quantity �� snack_sold
        $query = "SELECT snack_name, snack_quantity, snack_sold FROM snacks";
        $result = $conn->query($query);  // conn�����ݿ����Ӷ��󣬴��������ݿ�����������ӣ�queryִ�в�ѯ��䣬����һ�������

        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc()) {
                $snack_name = $row["snack_name"];
                $snack_quantity = $row["snack_quantity"];
                $snack_sold = $row["snack_sold"];
                $remaining_quantity = $snack_quantity - $snack_sold;

                // �ڱ�����ʾ��ʳ���ƺͿ��
                echo "<label for='snack_$snack_name'>$snack_name (���: $remaining_quantity) <br><br> </label> \n";
            }
        }
        else
        {
            echo "��ѯ����Ϊ�ա�";
        }
        ?>
    </div>

    <div>
        <h2>��ӿ��</h2>
        <!-- ��ӿ�� -->
        <form method="post" action="admin_add_snack.php">
            <label for="snack_name">��ʳ���ƣ�</label>
            <input type="text" name="snack_name" id="snack_name" required>
            
            <label for="snack_quantity">������</label>
            <input type="number" name="snack_quantity" id="snack_quantity" required>
            
            <label for="snack_price">�����۸�</label>
            <input type="number" name="snack_price" id="snack_price" step="0.01" required>
            
            <input type="submit" value="��ӿ��">
            <br>  <!-- ���� -->
        </form>
    </div>

<!-- ʵʱ���£�Ҳ������ -->
    <script>
        // ��ȡ������ʳ�����ֶ�
        var snackInputs = document.querySelectorAll('input[name^="snack_"]');

        // ����¼����������������ֶθ���ʱ���¿����Ϣ
        snackInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                // ��ȡ��ʳ���ƺͿ�������ֶε�ֵ
                var snackName = this.name.substring(6); // ȥ��ǰ׺ "snack_"
                var remainingQuantity = parseInt(this.max) - parseInt(this.value);

                // ������ʾ�Ŀ����Ϣ
                var label = document.querySelector('label[for="snack_' + snackName + '"]');
                label.innerHTML = snackName + ' (���: ' + remainingQuantity + ')';
            });
        });
    </script>


</body>
</html>



