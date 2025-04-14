<?php include('./constant/layout/head.php'); ?>
<?php include('./constant/layout/header.php'); ?>

<?php include('./constant/layout/sidebar.php'); ?>

<?php include('./constant/connect');
$sql = "SELECT product_id, product_name,product_image,rate,quantity,brand_id,expdate,categories_id,active,status FROM product WHERE status = 1";
$result = $connect->query($sql);
//echo $sql;exit;

?>
<div class="page-wrapper">

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary"> View Medicine</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">View Medicine</li>
            </ol>
        </div>
    </div>


    <div class="container-fluid">




        <div class="card">
            <div class="card-body">

                <a href="add-product.php"><button class="btn btn-primary">Add Medicine</button></a>

                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th style="width:10%;">Photo</th>
                                <th>Medicine Name</th>
                                <th>Rate</th>
                                <th>Quantity</th>
                                <th>Manufacturer</th>
                                <th>Category</th>
                                <th>Quantity Status</th>
                                <th>Expiry Date</th> <!-- New Column -->
                                <th>Expiry Status</th> <!-- New Column -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result as $row) {
                                $sql = "SELECT * from brands where brand_id='" . $row['brand_id'] . "'";
                                $result1 = $connect->query($sql);
                                $row1 = $result1->fetch_assoc();

                                $sql = "SELECT * from categories where categories_id='" . $row['categories_id'] . "'";
                                $result2 = $connect->query($sql);
                                $row2 = $result2->fetch_assoc();

                                // Get today's date and expiry date
                                $d1 = date('Y-m-d');
                                $expiryDate = $row['expdate'];
                                $expiryStatus = '';
                                $badgeClass = '';

                                // Determine the status of the expiry
                                if ($expiryDate < $d1) {
                                    $expiryStatus = "Expired";
                                    $badgeClass = "label-danger"; // Bootstrap danger class
                                } elseif (strtotime($expiryDate) <= strtotime($d1 . ' + 10 days')) {
                                    $expiryStatus = "Expiring Soon";
                                    $badgeClass = "label-warning"; // Bootstrap warning class
                                } else {
                                    $expiryStatus = "Not Expired";
                                    $badgeClass = "label-success"; // Bootstrap success class
                                }
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $row['product_id'] ?></td>
                                    <td><img src="assets/myimages/<?php echo $row['product_image']; ?>" style="width: 80px; height: 80px;"></td>
                                    <td> <label class="label <?php echo $expiryDate < $d1 ? 'label-danger' : 'label-success'; ?>">
                                            <?php echo $row['product_name']; ?>
                                        </label>
                                    </td>
                                    <td><?php echo $row['rate'] ?></td>
                                    <td><?php echo $row['quantity'] ?></td>
                                    <td><?php echo $row1['brand_name'] ?></td>
                                    <td><?php echo $row2['categories_name'] ?></td>
                                    <td><?php echo $row['active'] == 1 ? "<label class='label label-success'><h4>Available</h4></label>" : "<label class='label label-danger'><h4>Not Available</h4></label>"; ?></td>
                                    <td><?php echo $expiryDate; ?></td> <!-- Expiry Date Column -->
                                    <td><label class="label <?php echo $badgeClass; ?>"><?php echo $expiryStatus; ?></label></td> <!-- Expiry Status Column -->
                                    <td>
                                        <a href="editproduct.php?id=<?php echo $row['product_id'] ?>"><button type="button" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></button></a>
                                        <a href="php_action/removeProduct.php?id=<?php echo $row['product_id'] ?>"><button type="button" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete this record?')"><i class="fa fa-trash"></i></button></a>
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