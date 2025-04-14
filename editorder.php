<?php include('./constant/layout/head.php'); ?>
<?php include('./constant/layout/header.php'); ?>
<?php include('./constant/layout/sidebar.php'); ?>

<?php include('./constant/connect.php'); ?>

<h4><i class='glyphicon glyphicon-circle-arrow-right'></i></h4>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Edit Order</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Edit Order</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-11" style="margin-left: 5%;">
                <div class="card">
                    <div class="card-title"></div>
                    <div id="edit-order-messages"></div>
                    <div class="card-body">
                        <?php
                        $orderId = $_GET['id'];
                        $orderQuery = "SELECT * FROM orders WHERE id = '$orderId'";
                        $orderResult = $connect->query($orderQuery);
                        $order = $orderResult->fetch_assoc();
                        ?>

                        <form class="form-horizontal" method="POST" id="editOrderForm" action="php_action/editOrder.php">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-sm-2 control-label">Invoice No</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="<?php echo $order['uno']; ?>" name="uno" readonly />
                                    </div>

                                    <input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId; ?>">

                                    <label class="col-sm-2 control-label">Invoice Date</label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" value="<?php echo $order['orderDate']; ?>" name="orderDate" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <label class="col-sm-2 control-label">Client Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="<?php echo $order['clientName']; ?>" name="clientName" />
                                    </div>

                                    <label class="col-sm-2 control-label">Client Contact</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" value="<?php echo $order['clientContact']; ?>" name="clientContact" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Order Shipping Status</label>
                                        <input type="text" class="form-control" id="shipping_status" name="shipping_status" placeholder="Contact Number" autocomplete="off" value="<?php echo $order['shipping_status']; ?>" />
                                    </div>
                                </div>
                            </div>

                            <!-- Product Table -->
                            <table class="table" id="productTable">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Rate</th>
                                        <th>Avail.</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $orderItemsQuery = "SELECT * FROM order_item WHERE lastid = '$orderId'";
                                    $orderItemsResult = $connect->query($orderItemsQuery);
                                    $rowCount = 1;
                                    while ($item = $orderItemsResult->fetch_assoc()) {
                                    ?>
                                        <tr id="row<?php echo $rowCount; ?>">
                                            <td>
                                                <select class="form-control select-2" name="productName[]" id="productName<?php echo $rowCount; ?>" onchange="getProductData(<?php echo $rowCount; ?>)">
                                                    <option value="">~~~~~~~~~~~~SELECT~~~~~~~~~~~~</option>
                                                    <?php
                                                    $productQuery = "SELECT * FROM product";
                                                    $productResult = $connect->query($productQuery);
                                                    while ($product = $productResult->fetch_assoc()) {
                                                    ?>
                                                        <option value="<?php echo $product['product_id']; ?>" <?php echo $product['product_id'] == $item['productName'] ? 'selected' : ''; ?>>
                                                            <?php echo $product['product_name']; ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="rate[]" id="rate<?php echo $rowCount; ?>" disabled="true" class="form-control" value="<?php echo $item['rate']; ?>" />
                                                <input type="hidden" name="rateValue[]" id="rateValue<?php echo $rowCount; ?>" value="<?php echo $item['rate']; ?>" />
                                            </td>
                                            <td>
                                                <p id="available_quantity<?php echo $rowCount; ?>"><?php echo $item['quantity']; ?></p>
                                            </td>
                                            <td><input type="number" min="1" value="<?php echo $item['quantity']; ?>" name="quantity[]" id="quantity<?php echo $rowCount; ?>" class="form-control" onkeyup="getTotal(<?php echo $rowCount; ?>)" /></td>
                                            <td><input type="text" name="total[]" id="total<?php echo $rowCount; ?>" class="form-control" value="<?php echo $item['total']; ?>" disabled="true" /></td>
                                            <td><button type="button" class="btn btn-danger" onclick="removeProductRow(<?php echo $rowCount; ?>)"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php
                                        $rowCount++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <div class="form-group">
                                <label for="discount" class="col-sm-2 control-label">Discount %</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="discount" onkeyup="discountFunc()" value="<?php echo $order['discount']; ?>" />
                                </div>

                                <label for="totalAmount" class="col-sm-2 control-label">Total Amount</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="totalAmount" id="totalAmount" disabled="true" value="<?php echo $order['totalAmount']; ?>" />
                                    <input type="hidden" name="totalAmountValue" id="totalAmountValue" value="<?php echo $order['totalAmount']; ?>" />
                                </div>
                            </div>

                            <div class="form-group submitButtonFooter">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Update</button>
                                    <button type="reset" class="btn btn-danger" onclick="resetOrderForm()"><i class="glyphicon glyphicon-erase"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // Function to fetch products dynamically
    function fetchProducts(x) {
        $.ajax({
            url: 'php_action/fetchProductData.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let options = '<option value="">~~~~~~~~~~~~SELECT~~~~~~~~~~~~</option>';
                data.forEach(product => {
                    options += `<option value="${product[0]}" id="changeProduct${product[0]}">${product[1]}</option>`;
                });
                $(`#productName${x}`).html(options);
            },
            error: function() {
                alert("Failed to fetch product data.");
            }
        });
    }

    // Add a new row to the product table
    function addRow() {
        var rowCount = $("#productTable tbody tr").length + 1;
        console.log(rowCount);

        var newRow = `
    <tr id="row${rowCount}">
        <td>
            <select class="form-control select-2" name="productName[]" id="productName${rowCount}" onchange="getProductData(${rowCount})">
                <option value="">~~SELECT~~</option>
            </select>
        </td>
        <td>
            <input type="text" name="rate[]" id="rate${rowCount}" disabled="true" class="form-control" />
            <input type="hidden" name="rateValue[]" id="rateValue${rowCount}" />
        </td>
        <td><p id="available_quantity${rowCount}"></p></td>
        <td><input type="number" min="1" value="1" name="quantity[]" id="quantity${rowCount}" class="form-control" onkeyup="getTotal(${rowCount})" /></td>
        <td><input type="text" name="total[]" id="total${rowCount}" class="form-control" disabled="true" /></td>
        <td><button type="button" class="btn btn-danger" onclick="removeProductRow(${rowCount})"><i class="fa fa-trash"></i></button></td>
    </tr>
  `;
        $("#productTable tbody").append(newRow);

        // Fetch products for the new row
        fetchProducts(rowCount);

        // Reinitialize select2 for the newly added row
        $('.select-2').select2(); // This applies select2 to all select elements with class 'select-2'
    }



    // Function to calculate the total for a row
    function getTotal(x) {
        var rate = parseFloat($("#rate" + x).val()) || 0;
        var quantity = parseFloat($("#quantity" + x).val()) || 0;
        var total = rate * quantity;
        $("#total" + x).val(total.toFixed(2));
        updateTotalAmount(); // Update the total when row total is calculated
    }

    // Update the total amount for the order
    function updateTotalAmount() {
        var totalAmount = 0;
        $("input[name='total[]']").each(function() {
            totalAmount += parseFloat($(this).val()) || 0;
        });

        // Get discount percentage and apply it
        var discount = parseFloat($("input[name='discount']").val()) || 0;
        var discountAmount = (totalAmount * (discount / 100));

        // Calculate final amount after discount
        var finalAmount = totalAmount - discountAmount;

        $("#totalAmount").val(finalAmount.toFixed(2)); // Display the total after discount
        $("#totalAmountValue").val(finalAmount.toFixed(2)); // Send the final amount to the backend
    }

    // Fetch product details when a product is selected
    function getProductData(x) {
        var productId = $("#productName" + x).val();
        if (productId) {
            $.ajax({
                url: 'php_action/fetchSelectedProduct.php',
                method: 'POST',
                data: {
                    productId: productId
                },
                dataType: 'json',
                success: function(data) {
                    $("#rate" + x).val(data.rate);
                    $("#rateValue" + x).val(data.rate);
                    $("#available_quantity" + x).text(data.available_quantity);
                    getTotal(x); // Recalculate the total when the product is selected
                }
            });
        }
    }

    // Remove a row from the table
    function removeProductRow(x) {
        $("#row" + x).remove();
        updateTotalAmount(); // Recalculate the total after row removal
    }

    // Function to update discount in real time
    function discountFunc() {
        updateTotalAmount(); // Call this function to apply discount when user changes discount percentage
    }

    $(document).ready(function() {
        fetchProducts(1); // Fetch products for the first row initially
    });
</script>


<?php include('./constant/layout/footer.php'); ?>