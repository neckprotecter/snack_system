<?php
    session_start(); // �����Ự
    session_unset(); // ��ջỰ����
    session_destroy(); // ���ٻỰ
    header("Location: userlogin.html"); // �ض��򵽵�¼ҳ��
    exit;
?>
