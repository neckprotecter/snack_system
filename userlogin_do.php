<?php
header("content-type:text/html; charset=GBK");         //设置编码
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
// 连接数据库，查询用户信息
// 这里需要使用适当的数据库连接方法和SQL查询来检查用户输入的用户名和密码是否匹配
    $servername = "10.151.1.73"; // 数据库服务器主机名
    $username = "root"; // 数据库用户名
    $password = "root"; // 数据库密码
    $database = "snack"; // 数据库名称

    // 创建数据库连接
    $conn = new mysqli($servername, $username, $password, $database);
    // 检查连接是否成功
    if ($conn->connect_error)
        die("连接数据库失败: " . $conn->connect_error);

// 从用户提交的表单数据中获取用户名
    // $_POST 是一个超全局数组，用于从 POST 请求中获取数据。POST 请求通常用于向服务器提交表单数据
    $username = $_POST["username"]; 
    $password = $_POST["password"];
    if ($password == "")
    {
        echo "登陆失败，密码不能为空，请重试。";
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // 哈希处理


// 查询user信息
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
        $user_hashed_password = password_hash($user_password, PASSWORD_DEFAULT);  // 哈希处理

        $user = $result->fetch_assoc();
        // $stored_role = $user["role"];
        
        if (password_verify($password, $user_hashed_password))
        {
            // 用户名和密码匹配，登录成功
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $user_role;
            
            
            // 根据用户的角色重定向到不同的页面
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
            // 密码不匹配
            echo "登录失败，请检查密码。";
        }
    }
    else
    {
        // 用户名不存在
        echo "登录失败，请检查用户名。";
    }
}
?>
