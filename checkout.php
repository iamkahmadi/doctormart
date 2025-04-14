<?php include('./constant/layout/head.php'); ?>
<?php include('./constant/layout/header.php'); ?>
<?php include('./constant/layout/client_sidebar.php'); ?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Checkout</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Available Quantity</th>
                                    <th>Quantity to Order</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <!-- Cart items will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Address Input Field -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="shipping-address">Shipping Address</label>
                            <textarea id="shipping-address" class="form-control" rows="3" placeholder="Enter your shipping address"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-dark">Grand Total: <span id="grand-total">0</span> PKR</h4>
                            </div>
                            <div class="col-md-6">
                                <button id="place-order" class="btn btn-success float-right">Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<?php include('./constant/layout/footer.php'); ?>

<script>
    window.onload = function() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartItemsContainer = document.getElementById('cart-items');
        let grandTotal = 0;

        cart.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.productName}</td>
                <td>PKR ${item.productPrice}</td>
                <td><span class="available-quantity">${item.availableQuantity}</span></td>
                <td><input type="number" class="order-quantity" data-id="${item.productId}" value="${item.quantity}" min="1" max="${item.availableQuantity}" /></td>
                <td class="total-price">PKR ${item.productPrice * item.quantity}</td>
                <td><button class="btn btn-danger remove-item" data-id="${item.productId}">Remove</button></td>
            `;
            cartItemsContainer.appendChild(row);

            grandTotal += item.productPrice * item.quantity;
        });

        document.getElementById('grand-total').textContent = grandTotal;

        document.querySelectorAll('.order-quantity').forEach(input => {
            input.addEventListener('input', function() {
                const productId = this.dataset.id;
                let newQuantity = parseInt(this.value);
                const availableQuantity = parseInt(this.closest('tr').querySelector('.available-quantity').textContent);
                const totalPriceCell = this.closest('tr').querySelector('.total-price');
                const product = cart.find(item => item.productId === productId);

                if (isNaN(newQuantity) || newQuantity < 1) {
                    newQuantity = 1;
                    this.value = newQuantity;
                } else if (newQuantity > availableQuantity) {
                    newQuantity = availableQuantity;
                    this.value = newQuantity;
                }

                const oldTotal = product.productPrice * product.quantity;
                const newTotal = product.productPrice * newQuantity;

                totalPriceCell.textContent = 'PKR ' + newTotal;

                grandTotal = grandTotal - oldTotal + newTotal;
                document.getElementById('grand-total').textContent = grandTotal;

                product.quantity = newQuantity;
                localStorage.setItem('cart', JSON.stringify(cart));
            });
        });

        document.getElementById('place-order').addEventListener('click', function() {
            const orderData = {
                cart: cart,
                totalAmount: grandTotal,
                shippingAddress: document.getElementById('shipping-address').value // Add address to the order data
            };

            // Send orderData to client_order.php using Fetch API
            fetch('php_action/client_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(orderData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    if (data.success) {
                        alert('Order Placed Successfully!');
                        localStorage.removeItem('cart'); // Clear cart after successful order
                        window.location.href = 'client_orders.php'; // Redirect to confirmation page
                    } else {
                        alert('Error placing the order. Please try again.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });


        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.id;
                cart = cart.filter(item => item.productId !== productId);
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCart();
            });
        });

        function renderCart() {
            cartItemsContainer.innerHTML = '';
            grandTotal = 0;

            cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.productName}</td>
                    <td>PKR ${item.productPrice}</td>
                    <td><span class="available-quantity">${item.availableQuantity}</span></td>
                    <td><input type="number" class="order-quantity" data-id="${item.productId}" value="${item.quantity}" min="1" max="${item.availableQuantity}" /></td>
                    <td class="total-price">PKR ${item.productPrice * item.quantity}</td>
                    <td><button class="btn btn-danger remove-item" data-id="${item.productId}">Remove</button></td>
                `;
                cartItemsContainer.appendChild(row);
                grandTotal += item.productPrice * item.quantity;
            });

            document.getElementById('grand-total').textContent = grandTotal;
            attachEventListeners();
        }

        function attachEventListeners() {
            document.querySelectorAll('.order-quantity').forEach(input => {
                input.addEventListener('input', function() {
                    const productId = this.dataset.id;
                    let newQuantity = parseInt(this.value);
                    const availableQuantity = parseInt(this.closest('tr').querySelector('.available-quantity').textContent);
                    const totalPriceCell = this.closest('tr').querySelector('.total-price');
                    const product = cart.find(item => item.productId === productId);

                    if (isNaN(newQuantity) || newQuantity < 1) {
                        newQuantity = 1;
                        this.value = newQuantity;
                    } else if (newQuantity > availableQuantity) {
                        newQuantity = availableQuantity;
                        this.value = newQuantity;
                    }

                    const oldTotal = product.productPrice * product.quantity;
                    const newTotal = product.productPrice * newQuantity;

                    totalPriceCell.textContent = 'PKR ' + newTotal;

                    grandTotal = grandTotal - oldTotal + newTotal;
                    document.getElementById('grand-total').textContent = grandTotal;

                    product.quantity = newQuantity;
                    localStorage.setItem('cart', JSON.stringify(cart));
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.id;
                    cart = cart.filter(item => item.productId !== productId);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    renderCart();
                });
            });
        }
    };
</script>

</body>

</html>