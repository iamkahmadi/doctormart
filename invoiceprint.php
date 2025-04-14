<?php
session_start();
require_once('constant/connect.php');

// Fetch order details
$order_id = $_GET['id'];
$sql_order = "SELECT * FROM orders WHERE id = '$order_id' AND delete_status = 0";
$order_result = $connect->query($sql_order);
$order = $order_result->fetch_assoc();


// Fetch order items (products)
$sql_order_items = "SELECT * FROM order_item WHERE lastid = '$order_id'";
$order_items_result = $connect->query($sql_order_items);


// Fetch discount if exists (Assuming discount is a percentage stored in the order)
$discount_percentage = $order['discount'] ?? 0;  // If discount is not set, default to 0
$discount_amount = 0;
if ($discount_percentage > 0) {
	// Apply the discount to the total amount
	$discount_amount = ($totalAmount * $discount_percentage) / 100;
	$totalAmountAfterDiscount = $totalAmount - $discount_amount;
} else {
	$totalAmountAfterDiscount = $totalAmount; // No discount, same as original total
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<title>Invoice Print</title>
	<style>
		.invoice-box {
			max-width: 800px;
			margin: auto;
			padding: 30px;
			border: 1px solid #eee;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
			font-size: 16px;
			line-height: 24px;
			font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			color: #555;
		}

		.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
			border-collapse: collapse;
		}

		.invoice-box table td {
			padding: 5px;
			vertical-align: top;
		}

		.invoice-box table tr td:nth-child(2) {
			text-align: right;
		}

		.invoice-box table tr.top table td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.top table td.title {
			font-size: 45px;
			line-height: 45px;
			color: #333;
		}

		.invoice-box table tr.information table td {
			padding-bottom: 40px;
		}

		.invoice-box table tr.heading td {
			background: #eee;
			border-bottom: 1px solid #ddd;
			font-weight: bold;
		}

		.invoice-box table tr.details td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.item td {
			/*border-bottom: 1px solid #eee;*/
		}

		.invoice-box table tr.item.last td {
			border-bottom: none;
		}

		.invoice-box table tr.total td:nth-child(2) {
			/*border-top: 2px solid #eee;*/
			font-weight: bold;
		}

		@media only screen and (max-width: 600px) {
			.invoice-box table tr.top table td {
				width: 100%;
				display: block;
				text-align: center;
			}

			.invoice-box table tr.information table td {
				width: 100%;
				display: block;
				text-align: center;
			}
		}
	</style>
</head>

<body>
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0">
			<tr class="top">
				<td colspan="12">
					<table>
						<tr>
							<td class="title">
								<img src="assets/uploadImage/Logo/logo.jpg" style="width: 100%; max-width: 300px" />
							</td>

							<td>
								<h1 style="margin:0 0 11px;">Invoice</h1>
								#:<?php echo $order['uno']; ?><br />
								Created:<?php echo $order['orderDate']; ?><br />
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<?php
			$user_id = $order["client_id"];
			$sql_user = "SELECT * FROM users WHERE user_id = '$user_id'";
			$user_result = $connect->query($sql_user);
			$user = $user_result->fetch_assoc();
			?>

			<tr class="information">
				<td colspan="12">
					<table>
						<tr>
							<td>
								<b>Email:</b> <?php echo $user['email']; ?><br />
								<b>Address:</b> <?php
												if ($order["client_id"] == 1) {
													echo $user['address'];
												} else {
													echo $order['address'];
												}
												?><br />
							</td>
							<td>
								<b>Client Name:</b> <?php echo $order['clientName']; ?><br />
								<b>Client Contact No:</b> <?php echo $order['clientContact']; ?><br />
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr class="heading">
				<td>Payment Method</td>
				<!-- <td>Paid Amount</td> -->
				<!-- <td>Payment Status</td> -->
				<!-- <td>Payment Place</td> -->
			</tr>
			<tr class="details">
				<td>
					<?php echo $order["paymentType"]; ?>
				</td>
				<!-- <td><?php echo $order['paid']; ?></td>
				<td><?php echo $order['paymentStatus']; ?></td>
				<td><?php echo $order['paymentPlace']; ?></td> -->
			</tr>

			<tr class="heading">
				<td>Medicine Name</td>
				<td>Batch</td>
				<td>Exp</td>
				<td>Qty</td>
				<td>MRP</td>
				<td>Rate</td>
				<td>Total</td>
			</tr>

			<?php
			$totalAmount = 0;
			while ($item = $order_items_result->fetch_assoc()) {
				$product_id = $item['productName'];
				$sql_product = "SELECT * FROM product WHERE product_id = '$product_id'";
				$product_result = $connect->query($sql_product);
				$product = $product_result->fetch_assoc();
			?>
				<tr class="item">
					<td><?php echo $product['product_name']; ?></td>
					<td><?php echo $product['bno']; ?></td>
					<td><?php echo $product['expdate']; ?></td>
					<td><?php echo $item['quantity']; ?></td>
					<td><?php echo $product['mrp']; ?></td>
					<td><?php echo $item['rate']; ?></td>
					<td><?php echo $item['total']; ?></td>
				</tr>
			<?php
				$totalAmount += $item['total'];
			}

			?>

			<tr class="total" style="background-color: khaki;">
				<td colspan="1" style="text-align: right; font-weight: bold;">Discount:</td>
				<td style="text-align: right; font-weight: bold;"><?php echo $order['discount']; ?>%</td>
				<td colspan="4" style="text-align: right; font-weight: bold;">Total Amount:</td>
				<td style="font-weight: bold;"><?php
												if ($order["client_id"] == 1) {
													echo $order['totalAmount'];
												} else {
													echo $totalAmount;
												}
												?></td>
			</tr>
		</table>
	</div>
</body>

</html>