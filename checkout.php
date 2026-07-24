<?php include('partials-front/menu.php'); ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}


if (isset($_POST['place_order'])) {
    $customer_name = trim($_POST['name']);
    $customer_phone = trim($_POST['phone']);
    $customer_address = trim($_POST['address']);
    $grand_total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $grand_total += $item['price'] * $item['quantity'];
    }

    

    
    unset($_SESSION['cart']);
    exit();
}
?>

<section class="checkout-section" style="padding:50px 0;">
    <div class="container">
        <h2 class="text-center" style="margin-bottom:40px;">Checkout</h2>

        <div style="max-width:700px; margin:auto; background:#f9f9f9; padding:30px; border-radius:8px;">
            <h3>Order Summary:</h3>
            <table style="width:100%; margin-bottom:20px; border-collapse:collapse;">
                <thead>
                    <tr style="background:#eee;">
                        <th style="padding:10px;">Item</th>
                        <th style="padding:10px;">Quantity</th>
                        <th style="padding:10px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $item) {
                        $subtotal = $item['price'] * $item['quantity'];
                        $grand_total += $subtotal;
                        ?>
                        <tr style="text-align:center;">
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Rs. <?php echo number_format($subtotal); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h3 style="text-align:right;">Grand Total: Rs. <?php echo number_format($grand_total); ?></h3>

            <form action="checkout.php" method="POST" style="margin-top:30px;">
                <h3>Delivery Information</h3>
                <div style="margin-bottom:15px;">
                    <label>Full Name</label><br>
                    <input type="text" name="name" required style="width:100%; padding:10px; margin-top:5px;">
                </div>
                <div style="margin-bottom:15px;">
                    <label>Phone Number</label><br>
                    <input type="text" name="phone" required style="width:100%; padding:10px; margin-top:5px;">
                </div>
                <div style="margin-bottom:15px;">
                    <label>Delivery Address</label><br>
                    <textarea name="address" rows="4" required style="width:100%; padding:10px; margin-top:5px;"></textarea>
                </div>

                <div style="text-align:right;">
                    <button type="submit" name="place_order" class="btn" style="background:#28a745; padding:10px 20px; color:white; border:none; border-radius:5px;">Place Order</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>
