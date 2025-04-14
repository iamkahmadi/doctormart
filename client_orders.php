<?php include('./constant/layout/head.php'); ?>
<?php include('./constant/layout/header.php'); ?>

<?php include('./constant/layout/client_sidebar.php'); ?>
.

<?php include('./constant/connect');
$user = $_SESSION['userId'];
$sql = "SELECT * FROM orders WHERE delete_status = 0 AND client_id='$user'";
$result = $connect->query($sql);

?>
<div class="page-wrapper">

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary"> View Order</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">View Order</li>
            </ol>
        </div>
    </div>


    <div class="container-fluid">




        <div class="card">
            <div class="card-body">

                <a href="client-add-order.php"><button class="btn btn-primary">Add Order</button></a>

                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Order Date</th>
                                <th>Client Name</th>
                                <th>Contact</th>
                                <th>Order Shipping Status</th>

                                <td>Payment Method</td>
                                <td>Shipping Address</td>
                                <!-- <td>Paid Amount</td>
                                <td>Payment Status</td>
                                <td>Payment Place</td> -->

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result as $row) {

                                $no += 1;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no; ?></td>
                                    <td><?php echo $row['orderDate'] ?></td>
                                    <td><?php echo $row['clientName'] ?></td>
                                    <td><?php echo $row['clientContact'] ?></td>
                                    <td><?php echo $row['shipping_status'] ?></td>


                                    <td><?php echo $row['paymentType']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <!-- <td><?php echo $row['paid']; ?></td>
                                    <td><?php echo $row['paymentStatus']; ?></td>
                                    <td><?php echo $row['paymentPlace']; ?></td> -->

                                    <td>
                                        <a class="btn btn-xs btn-primary m-1" href="invoiceprint.php?id=<?php echo $row['id'] ?>"><i class="fa fa-eye"></i></a>

                                        <a href="php_action/removeOrder.php?id=<?php echo $row['id'] ?>"><button type="button" class="btn btn-xs btn-danger m-1" onclick="return confirm('Are you sure to cancel the order?')"><i class="fa fa-trash"></i></button></a>

                                        <a href="invoiceprint.php?id=<?php echo $row['id'] ?>"><button type="button" class="btn btn-xs btn-success m-1"><i class="fa fa-print"></i></button></a>


                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <?php include('./constant/layout/footer.php'); ?>
        .