<?php
header("content-type: application/json; charset=utf-8");
session_start();

$servername = "localhost"; 
$username = "root"; 
// $password = "root"; 
$password = "root1234"; 
$database = "snack"; 

// �������ݿ�����
$conn = new mysqli($servername, $username, $password, $database);

// ��ѯ���ݿ����û�����Ϣ
$query = "SELECT * FROM users ORDER BY user_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $usermessage = array();

    while ($row = $result->fetch_assoc()) {
        $usermessage[] = $row;
    }

    echo json_encode($usermessage);
} else {
    echo json_encode(array('message' => '��ѯ����Ϊ�ա�'));
}

$conn->close();
?>
