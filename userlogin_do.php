<?php
header("content-type:text/html; charset=GBK");         //���ñ���
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
// �������ݿ⣬��ѯ�û���Ϣ
// ������Ҫʹ���ʵ������ݿ����ӷ�����SQL��ѯ������û�������û����������Ƿ�ƥ��
    $servername = "10.151.1.73"; // ���ݿ������������
    $username = "root"; // ���ݿ��û���
    $password = "root"; // ���ݿ�����
    $database = "snack"; // ���ݿ�����

    // �������ݿ�����
    $conn = new mysqli($servername, $username, $password, $database);
    // ��������Ƿ�ɹ�
    if ($conn->connect_error)
        die("�������ݿ�ʧ��: " . $conn->connect_error);

// ���û��ύ�ı������л�ȡ�û���
    // $_POST ��һ����ȫ�����飬���ڴ� POST �����л�ȡ���ݡ�POST ����ͨ��������������ύ������
    $username = $_POST["username"]; 
    $password = $_POST["password"];
    if ($password == "")
    {
        echo "��½ʧ�ܣ����벻��Ϊ�գ������ԡ�";
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // ��ϣ����


// ��ѯuser��Ϣ
    $query = "SELECT * FROM users";
    $result = $conn->query($query);

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $user_name = $row["username"];
            $user_password = $row["password"];
            $user_role = $row["role"];
            if($user_name == $username)
                break;
        }
        $user_hashed_password = password_hash($user_password, PASSWORD_DEFAULT);  // ��ϣ����

        $user = $result->fetch_assoc();
        // $stored_role = $user["role"];
        
        if (password_verify($password, $user_hashed_password))
        {
            // �û���������ƥ�䣬��¼�ɹ�
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $user_role;
            
            
            // �����û��Ľ�ɫ�ض��򵽲�ͬ��ҳ��
            if (!strcmp($_SESSION["role"], "admin"))
            {
                header("Location: admin_dashboard.php");
            }
            else
            {
                header("Location: member_dashboard.php");
            }
        }
        else
        {
            // ���벻ƥ��
            echo "��¼ʧ�ܣ��������롣";
        }
    }
    else
    {
        // �û���������
        echo "��¼ʧ�ܣ������û�����";
    }
}
?>
