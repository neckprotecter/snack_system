<?php
    session_start(); // 启动会话
    session_unset(); // 清空会话数据
    session_destroy(); // 销毁会话
    header("Location: userlogin.html"); // 重定向到登录页面
    exit;
?>
