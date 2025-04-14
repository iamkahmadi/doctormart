<?php
    require_once('./constant/connect.php');

    // Get today's date in the format YYYY-MM-DD
    $today_date = date('Y-m-d');
    
    // Query to check if there are any new orders today
    $sql_new_orders = "SELECT COUNT(*) AS total_orders FROM orders WHERE DATE(orderDate) = '$today_date' AND delete_status = 0 AND client_id!=1 AND is_seen=0";
    $result_new_orders = $connect->query($sql_new_orders);
    $row_new_orders = $result_new_orders->fetch_assoc();
    
    // Get the count of new orders today
    $new_orders_count = $row_new_orders['total_orders'];
?>

<div class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                <li class="nav-label">Home</li>
                <li> <a href="dashboard.php" aria-expanded="false"><i class="fa fa-tachometer"></i>Dashboard</a></li>

                <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
                    <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-industry"></i><span class="hide-menu">Manufacturer</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="add-brand.php">Add Manufacturer</a></li>
                            <li><a href="brand.php">Manage Manufacturer</a></li>
                            <li><a href="importbrand.php">Import Manufacturer</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
                    <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-list"></i><span class="hide-menu">Categories</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="add-category.php">Add Category</a></li>
                            <li><a href="categories.php">Manage Categories</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
                    <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-medkit"></i><span class="hide-menu">Medicine</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="add-product.php">Add Medicine</a></li>
                            <li><a href="product.php">Manage Medicine</a></li>
                            <li><a href="expired.php">Expired Medicine</a></li>
                        </ul>
                    </li>
                <?php } ?>

                <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-file"></i><span class="hide-menu">Invoices</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="add-order.php">Add Invoice</a></li>
                        <li><a href="Order.php">Manage Invoices</a></li>
                    </ul>
                </li>

                <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
                    <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-flag"></i><span class="hide-menu">Reports</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="sales_report.php">Sales Report</a></li>
                            <li><a href="productreport.php">Product Report</a></li>
                        </ul>
                    </li>
                <?php } ?>

                <li>
                    <a href="Client-Orders.php" aria-expanded="false">
                        <i class="fa fa-shopping-cart"></i> Clients Orders
                        <?php if ($new_orders_count > 0) { ?>
                            <span class="badge badge-danger" style="margin-left: 10px;"><?php echo $new_orders_count; ?> New</span>
                        <?php } ?>
                    </a>
                </li>

                <li>
                    <a href="client_profile.php" aria-expanded="false">
                        <i class="fa fa-user"></i> Profile
                    </a>
                </li>


                <li>
                    <a href="./constant/logout.php" aria-expanded="false">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
