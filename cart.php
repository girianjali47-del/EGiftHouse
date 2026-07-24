<?php 
include('partials-front/menu.php');
include('config/constants.php');


ini_set('display_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


if (isset($_GET['remove_item_id'])) {
    $remove_item_id = $_GET['remove_item_id'];
    if (isset($_SESSION['cart'][$remove_item_id])) {
        unset($_SESSION['cart'][$remove_item_id]);
    }
}


if (isset($_POST['update_cart']) && isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $item_id => $quantity) {
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]['qty'] = max(1, (int)$quantity);
        }
    }
}


$total_price = 0;
foreach ($_SESSION['cart'] as $item_id => $item_details) {
    $qty = isset($item_details['qty']) ? (int)$item_details['qty'] : 1;
    $price = isset($item_details['price']) ? (float)$item_details['price'] : 0;
    $total_price += $price * $qty;
}
?>


<style>
.cart {
    padding: 40px 0;
    background-color: #f9f9f9;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.cart h2 {
    margin-bottom: 30px;
    font-size: 32px;
    color: #333;
    text-align: center;
}
.cart-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.cart-item {
    display: flex;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    align-items: center;
}
.cart-item-img img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    margin-right: 20px;
    border: 1px solid #ddd;
}
.cart-item-details {
    flex: 1;
}
.cart-item-details h4 {
    margin: 0 0 10px;
    font-size: 20px;
    color: #222;
}
.cart-item-details p {
    margin: 5px 0;
    color: #555;
}
.qty-input {
    width: 60px;
    padding: 5px;
    font-size: 16px;
    margin-top: 10px;
    margin-right: 10px;
}
.remove-item {
    display: inline-block;
    color: #e74c3c;
    text-decoration: none;
    margin-left: 10px;
    font-weight: bold;
    transition: color 0.2s ease-in-out;
}
.remove-item:hover {
    color: #c0392b;
}
.cart-total {
    margin-top: 30px;
    text-align: right;
    font-size: 22px;
    color: #333;
}
.cart-total h3 {
    margin-bottom: 15px;
}
.btn {
    padding: 10px 25px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    border: none;
    text-decoration: none;
}
.btn-primary {
    background-color: #3498db;
    color: #fff;
    margin-top: 20px;
}
.btn-primary:hover {
    background-color: #2980b9;
}
.btn-success {
    background-color: #2ecc71;
    color: #fff;
}
.btn-success:hover {
    background-color: #27ae60;
}
.text-center {
    text-align: center;
}
.error {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}
@media screen and (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        text-align: center;
    }
    .cart-item-img img {
        margin: 0 auto 15px;
    }
    .cart-total {
        text-align: center;
    }
}
</style>


<section class="cart">
    <div class="container">
        <h2>Your Cart</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <form action="cart.php" method="POST">
                <div class="cart-items">
                    <?php
                    foreach ($_SESSION['cart'] as $item_id => $item_details):
                        $sql = "SELECT * FROM tbl_items WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $item_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $item = mysqli_fetch_assoc($result);

                        if (!$item) continue;
                    ?>
                        <div class="cart-item">
                            <div class="cart-item-img">
                                <?php if (!empty($item['image_name'])): ?>
                                    <img src="<?php echo SITEURL . 'images/item/' . $item['image_name']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="img-responsive img-curve">
                                <?php else: ?>
                                    <div class="error">Image Not Available</div>
                                <?php endif; ?>
                            </div>

                            <div class="cart-item-details">
                                <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                                <p>Price: Rs. <?php echo number_format($item['price'], 2); ?></p>

                                <input type="number" name="qty[<?php echo $item_id; ?>]" value="<?php echo isset($item_details['qty']) ? (int)$item_details['qty'] : 1; ?>" min="1" class="qty-input" required>
                                <a href="cart.php?remove_item_id=<?php echo $item_id; ?>" class="remove-item">Remove</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" name="update_cart" class="btn btn-primary">Update Cart</button>
            </form>

            <div class="cart-total">
                <h3>Total: Rs. <?php echo number_format($total_price, 2); ?></h3>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>

        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</section>


<?php include('partials-front/footer.php'); ?>
