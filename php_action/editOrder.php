<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
	$orderId = $_POST['orderId'];
	$uno = $_POST['uno'];
	$orderDate = $_POST['orderDate'];
	$clientName = $_POST['clientName'];
	$clientContact = $_POST['clientContact'];
	$shipping_status = $_POST['shipping_status'];
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

	// Update the order table
	$sql = "UPDATE orders SET
                uno = '$uno',
                orderDate = '$orderDate',
                clientName = '$clientName',
                clientContact = '$clientContact',
                subTotal = '$subTotal',
                totalAmount = '$totalAmount',
                discount = '$discount',
                grandTotalValue = '$grandTotalValue',
                paid = '$paid',
                dueValue = '$dueValue',
                paymentType = '$paymentType',
                paymentStatus = '$paymentStatus',
                paymentPlace = '$paymentPlace',
                shipping_status = '$shipping_status'
            WHERE id = '$orderId'";

	if ($connect->query($sql) === TRUE) {
		// Update order items
		$checkbox1 = count($_POST['productName']);
		for ($i = 0; $i < $checkbox1; $i++) {
			extract($_POST);
			$productName = $_POST["productName"];
			$quantity = $_POST["quantity"];
			$rateValue = $_POST["rateValue"];

			// Get the old quantity for comparison
			$sqlOldQuantity = "SELECT quantity FROM order_item WHERE lastid = '$orderId' AND productName = '$productName[$i]'";
			$resultOldQuantity = $connect->query($sqlOldQuantity);
			$oldQuantity = 0;
			if ($resultOldQuantity->num_rows > 0) {
				$row = $resultOldQuantity->fetch_assoc();
				$oldQuantity = $row['quantity'];
			}

			$totalValue = $quantity[$i] * $rateValue[$i];

			// Update order_item table
			$sql1 = "UPDATE order_item SET
                        productName = '$productName[$i]',
                        quantity = '$quantity[$i]',
                        rate = '$rateValue[$i]',
                        total = '$totalValue'
                    WHERE lastid = '$orderId' AND productName = '$productName[$i]'";

			if ($connect->query($sql1) === TRUE) {
				// Update the product quantity in the product table
				$product_id = $productName[$i];
				$quantity_bought = $quantity[$i];

				// Fetch current quantity in product table
				$sqlProduct = "SELECT quantity FROM product WHERE product_id = '$product_id'";
				$resultProduct = $connect->query($sqlProduct);
				$product = $resultProduct->fetch_assoc();
				$current_quantity = $product['quantity'];

				// Calculate the change in quantity (increase or decrease)
				$quantity_change = $quantity_bought - $oldQuantity;

				// If the quantity has increased, subtract the difference from the product stock
				if ($quantity_change > 0) {
					$new_quantity = $current_quantity - $quantity_change;
				} else {
					// If the quantity has decreased, add the difference to the product stock
					$new_quantity = $current_quantity - $quantity_change;  // `quantity_change` is negative, so it adds to stock.
				}

				// Update the product quantity
				$sqlUpdateProduct = "UPDATE product SET quantity = '$new_quantity' WHERE product_id = '$product_id'";

				if ($connect->query($sqlUpdateProduct) === TRUE) {
					$valid['success'] = true;
					$valid['messages'] = "Order updated successfully!";

					if ($_SESSION["role"] != "Admin") {
						header('location:../client_orders.php');
					} else {
						header('location:../Order.php');
					}
				} else {
					$valid['success'] = false;
					$valid['messages'] = "Error updating product quantity.";
				}
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Error while updating order item.";
			}
		}
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating order.";
	}

	echo json_encode($valid);
}
$connect->close();
