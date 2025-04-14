<?php
session_start();
include('./constant/connect.php');
include('./constant/layout/head.php');
include('./constant/layout/header.php');
include('./constant/layout/client_sidebar.php');

$userId = $_SESSION['userId']; // Assuming user is logged in and userId is stored in session

// Get the total number of orders for the client
$orderSql = "SELECT COUNT(*) as totalOrders FROM orders WHERE client_id = $userId AND delete_status = 0";
$orderResult = $connect->query($orderSql);
$orderData = $orderResult->fetch_assoc();
$totalOrders = $orderData['totalOrders'];

?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 dashboard">
                <div class="card" style="background: #2BC155;">
                    <div class="media widget-ten">
                        <div class="media-left meida media-middle">
                            <span><i class="ti-agenda"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <!-- <h1 style>Bilal</h1> -->
                            <h2 class="color-white"><?php echo $totalOrders; ?></h2>
                            <a href="client_orders.php">
                                <p class="m-b-0"><b>Total Orders</b></p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./constant/layout/footer.php'); ?>