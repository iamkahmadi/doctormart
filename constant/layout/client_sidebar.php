<?php
require_once('./constant/connect.php');
?>

<div class="left-sidebar">

    <div class="scroll-sidebar">

        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                <li class="nav-label">Home</li>
                <li>
                    <a href="client_dashboard.php" aria-expanded="false">
                        <i class="fa fa-tachometer"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="client_orders.php" aria-expanded="false">
                        <i class="fa fa-shopping-cart"></i> My Orders
                    </a>
                </li>
                <li>
                    <a href="client-add-order.php" aria-expanded="false">
                        <i class="fa fa-file"></i> Products Order
                    </a>
                </li>
                <li>
                    <a href="checkout.php" aria-expanded="false">
                        <i class="fa fa-shopping-cart"></i> Checkout
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