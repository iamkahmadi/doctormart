<?php

require_once 'core.php';


$valid['success'] = array('success' => false, 'messages' => array());

//$orderId = $_POST['orderId'];
$orderId = $_GET['id'];
if ($orderId) {

	$sql = "UPDATE orders SET delete_status=1 WHERE id={$orderId}";

	if ($connect->query($sql) === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Removed";
		
		if ($_SESSION["role"] != "Admin") {
			header('location:../client_orders.php');
		} else {
			header('location:../Order.php');
		}
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while remove the brand";
	}

	$connect->close();

	echo json_encode($valid);
}
