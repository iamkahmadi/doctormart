<?php
session_start();
require_once 'core.php'; // include your database connection file

// Initialize response
$response = array('success' => false, 'messages' => '');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read input
    $orderData = json_decode(file_get_contents('php://input'), true);

    // Validate the order data
    if (isset($orderData['cart']) && isset($orderData['totalAmount'])) {
        $userId = $_SESSION['userId'];

        // Fetch user details from users table
        $sql_user = "SELECT * FROM users WHERE user_id = ?";
        $stmt_user = $connect->prepare($sql_user);
        $stmt_user->bind_param("i", $userId);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $user = $result_user->fetch_assoc();

        if ($user) {
            $clientName = $user['username'];
            $clientContact = $user['mobile_no'];
            $shippingAddress = $orderData['shippingAddress'];

            // Generate the invoice ID (INV-000)
            $invoiceId = "INV-" . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);

            // Insert into orders table with default values for projectName, discount, gstn, paid, and dueValue
            $sql_order = "INSERT INTO orders (uno, orderDate, client_id, clientName, projectName, clientContact, address, subTotal, totalAmount, discount, grandTotalValue, gstn, paid, dueValue, paymentType, paymentStatus, paymentPlace, delete_status, shipping_status) 
                          VALUES ('$invoiceId', NOW(), '$userId', '$clientName', 'Project X', '$clientContact', '$shippingAddress', '$orderData[totalAmount]', '$orderData[totalAmount]', 0, '$orderData[totalAmount]', 0, 0, 0, 'Cash On Delivery', 'Pending', 'In Pakistan', 0, 'Pending')";

            if ($connect->query($sql_order)) {
                $orderId = $connect->insert_id; // Get the last inserted order ID

                // Insert order items into the order_item table
                foreach ($orderData['cart'] as $item) {
                    $productId = $item['productId'];
                    $quantity = $item['quantity'];
                    $rate = $item['productPrice'];
                    $total = $quantity * $rate;

                    // Insert the order item
                    $sql_item = "INSERT INTO order_item (productName, quantity, rate, total, lastid, added_date) 
                                 VALUES ('$productId', $quantity, $rate, $total, $orderId, NOW())";
                    if (!$connect->query($sql_item)) {
                        $response['messages'] = 'Failed to insert order item: ' . $connect->error;
                        echo json_encode($response);
                        exit;
                    }

                    // Update the product quantity in the products table
                    $sql_update_quantity = "UPDATE product SET quantity = quantity - $quantity WHERE product_id = $productId";
                    if (!$connect->query($sql_update_quantity)) {
                        $response['messages'] = 'Failed to update product quantity: ' . $connect->error;
                        echo json_encode($response);
                        exit;
                    }
                }

                $response['success'] = true;
                $response['messages'] = 'Order placed successfully!';
            } else {
                $response['messages'] = 'Failed to place the order: ' . $connect->error;
            }
        } else {
            $response['messages'] = 'User not found.';
        }
    } else {
        $response['messages'] = 'Invalid order data.';
    }
} else {
    $response['messages'] = 'Invalid request method.';
}

// Return the response as JSON
echo json_encode($response);
?>
