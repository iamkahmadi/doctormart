<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$uno = $_POST['uno'];
	$orderDate = $_POST['orderDate'];
	$client_id = isset($_POST['client_id']) ? $_POST['client_id'] : null; // Get client_id from the request
	$clientName = '';
	$clientContact = '';
	$subTotal = $_POST['subTotalValue'] ?? 0;
	$totalAmount = $_POST['totalAmountValue'];
	$discount = $_POST['discount'];
	$grandTotalValue = $_POST['grandTotalValue'] ?? 0;
	$gstn = $_POST['gstn'] ?? 0;
	$paid = $_POST['paid'] ?? 0;
	$dueValue = $_POST['dueValue'] ?? 0;
	$paymentType = $_POST['paymentType'] ?? "Cash In Hand";
	$paymentStatus = $_POST['paymentStatus'] ?? 1;
	$paymentPlace = $_POST['paymentPlace'] ?? "In Pakistan";


	// Check if client_id exists
	if ($client_id) {
		// Fetch user details from the users table
		$sqlUser = "SELECT username, mobile_no FROM users WHERE user_id = '$client_id'";
		$resultUser = $connect->query($sqlUser);

		if ($resultUser->num_rows > 0) {
			$user = $resultUser->fetch_assoc();
			$clientName = $user['username'];
			$clientContact = $user['mobile_no'];
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Client not found.";
			echo json_encode($valid);
			exit();
		}
	} else {
		// Use the provided clientName and clientContact from the request
		$clientName = $_POST['clientName'];
		$clientContact = $_POST['clientContact'];
		$client_id = 1; // If no client_id, set to a default value (1)
	}


	// Prepare the SQL statement for inserting the order
	$sql = "INSERT INTO orders (uno, orderDate, client_id, clientName, gstn, clientContact, subTotal, totalAmount, discount, grandTotalValue, paid, dueValue, paymentType, paymentStatus, paymentPlace, shipping_status) 
            VALUES ('$uno', '$orderDate', '$client_id', '$clientName', '$gstn', '$clientContact', '$subTotal', '$totalAmount', '$discount', '$grandTotalValue', '$paid', '$dueValue', '$paymentType', '$paymentStatus', '$paymentPlace', 'Order Placed')";

	if ($connect->query($sql) === TRUE) {
		$lastid = mysqli_insert_id($connect); // Get the last inserted order ID
		$checkbox1 = count($_POST['productName']);

		for ($i = 0; $i < $checkbox1; $i++) {
			extract($_POST);
			$added_date = date('Y-m-d');

			$productName = $_POST["productName"];
			$quantity = $_POST["quantity"];
			$rateValue = $_POST["rateValue"];

			$totalValue = $quantity[$i] * $rateValue[$i];

			// Insert order items into the order_item table
			$sql1 = "INSERT INTO order_item (productName, quantity, rate, total, lastid, added_date) 
                     VALUES ('$productName[$i]', '$quantity[$i]', '$rateValue[$i]', '$totalValue', '$lastid', '$added_date')";

			if ($connect->query($sql1) === TRUE) {
				// Update the product quantity in the product table
				$product_id = $productName[$i]; // Assuming productName contains product IDs
				$quantity_bought = $quantity[$i];

				// Fetch the current quantity of the product
				$sqlProduct = "SELECT quantity FROM product WHERE product_id = '$product_id'";
				$resultProduct = $connect->query($sqlProduct);

				if ($resultProduct->num_rows > 0) {
					$product = $resultProduct->fetch_assoc();
					$current_quantity = $product['quantity'];

					// Decrease the quantity by the amount bought
					$new_quantity = $current_quantity - $quantity_bought;

					// Update the product's quantity
					$sqlUpdateProduct = "UPDATE product SET quantity = '$new_quantity' WHERE product_id = '$product_id'";

					if ($connect->query($sqlUpdateProduct) === TRUE) {
						$valid['success'] = true;
						$valid['messages'] = "Successfully Added";
					} else {
						$valid['success'] = false;
						$valid['messages'] = "Error updating product quantity.";
					}
				}
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Error while adding order item.";
			}
		}

		if ($_SESSION["role"] != "Admin") {
			header('location:../client_orders.php');
		} else {
			header('location:../Order.php');
		}
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while adding the order";
		if ($_SESSION["role"] != "Admin") {
			header('location:../client-add-order.php');
		} else {
			header('location:../add-order.php');
		}
	}

	$connect->close();
	echo json_encode($valid);
} // /if $_POST
