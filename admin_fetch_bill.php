<?php
header("content-type: application/json; charset=utf-8");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost"; 
    $username = "root"; 
    $password = "root1234"; 
    $database = "snack"; 

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("����ʧ��: " . $conn->connect_error);
    }

    // ��ȡ JSON ����
    $jsonData = json_decode(file_get_contents('php://input'), true);

    // ��ȡ HTML ҳ�洫�ݵ�ʱ��β���
    $startDate = $jsonData["startDate"];
    $endDate = $jsonData["endDate"];

    $startDate_ = date("Y-m-d H:i:s", strtotime($startDate));
    $endDate_ = date("Y-m-d H:i:s", strtotime($endDate));

    // ���� purchase_history ����ָ��ʱ����ڵ��ܽ��
    $purchaseQuery = "SELECT SUM(price) as total_purchase FROM purchase_history WHERE time BETWEEN ? AND ?";
    $purchaseStmt = $conn->prepare($purchaseQuery);
    $purchaseStmt->bind_param("ss", $startDate_, $endDate_);
    $purchaseStmt->execute();
    $purchaseResult = $purchaseStmt->get_result();
    $purchaseRow = $purchaseResult->fetch_assoc();
    $totalPurchase = $purchaseRow['total_purchase'];

    // ���� snacks �������м�¼�� snack_price �ܺ�
    $snacksQuery = "SELECT SUM(snack_price) as total_snack_price FROM snacks WHERE date BETWEEN ? AND ?";
    $snacksStmt = $conn->prepare($snacksQuery);
    $snacksStmt->bind_param("ss", $startDate_, $endDate_);
    $snacksStmt->execute();
    $snacksResult = $snacksStmt->get_result();
    $snacksRow = $snacksResult->fetch_assoc();
    $totalSnackPrice = $snacksRow['total_snack_price'];

    // ���ؽ���� HTML ҳ��
    $result = array(
        "total_purchase" => $totalPurchase,
        "total_snack_price" => $totalSnackPrice,
        "startdate" => $startDate_,
        "enddate" => $endDate_
    );

    echo json_encode($result);

    $conn->close();
}
?>
