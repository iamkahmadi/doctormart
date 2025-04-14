<?php include('./constant/layout/head.php'); ?>
<?php include('./constant/layout/header.php'); ?>
<?php include('./constant/layout/client_sidebar.php'); ?>

<?php
include('./constant/connect');

$sql = "SELECT * FROM product WHERE active = 1 AND status = 1 AND quantity != 0"; // Get active products
$result = $connect->query($sql);
?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Products Order</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Products Order</li>
            </ol>
        </div>
    </div>

    <div class="container">
        <a href="checkout.php" class="btn btn-success mt-3" id="checkout-btn">Go to Checkout (0 items)</a>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card" style="height: 450px;">
                        <img class="card-img-top" src="assets/myimages/<?php echo $product['product_image']; ?>" alt="Product Image" style="width: 100%; height: 100%;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                            <p>Price: PKR <?php echo $product['rate']; ?></p>
                            <p>Quantity Available: <?php echo $product['quantity']; ?></p>
                            <button class="btn btn-primary add-to-cart" data-id="<?php echo $product['product_id']; ?>"
                                data-name="<?php echo $product['product_name']; ?>"
                                data-price="<?php echo $product['rate']; ?>"
                                data-quantity="<?php echo $product['quantity']; ?>">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include('./constant/layout/footer.php'); ?>

<script>
    // Function to update the checkout button with the number of unique products in the cart
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const uniqueProductsCount = cart.length; // This will count the number of unique products added
        const checkoutButton = document.getElementById('checkout-btn');
        checkoutButton.textContent = `Go to Checkout (${uniqueProductsCount} products)`; // Update button text with unique product count
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const productPrice = parseFloat(this.dataset.price);
            const availableQuantity = parseInt(this.dataset.quantity);

            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            const existingProductIndex = cart.findIndex(item => item.productId === productId);

            if (existingProductIndex === -1) {
                cart.push({
                    productId,
                    productName,
                    productPrice,
                    availableQuantity: availableQuantity,
                    quantity: 1
                });
            } else {
                const existingProduct = cart[existingProductIndex];
                if (existingProduct.quantity < availableQuantity) {
                    existingProduct.quantity++;
                } else {
                    alert('Max quantity reached for this product!');
                    return;
                }
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            alert('Product added to cart!');

            // Update the cart count on the checkout button
            updateCartCount();
        });
    });

    // Update the cart count on page load
    window.onload = updateCartCount;
</script>