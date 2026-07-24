<?php include('partials-front/menu.php'); ?>

<?php

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("location: login.php");
    exit();
}

require_once 'config/constants.php';

if (!isset($_GET['item_id'])) {
    header('location:' . SITEURL);
    exit();
}

$item_id = intval($_GET['item_id']);
$sql = "SELECT * FROM tbl_items WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $item_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) !== 1) {
    header('location:' . SITEURL);
    exit();
}

$item = mysqli_fetch_assoc($res);


$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM tbl_users WHERE user_id=?";
$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$res_user = mysqli_stmt_get_result($stmt_user);

if (!$res_user || mysqli_num_rows($res_user) !== 1) {
    $_SESSION['order'] = "<div class='error text-center'>User details not found.</div>";
    header('location:' . SITEURL);
    exit();
}

$user = mysqli_fetch_assoc($res_user);


$errors = [];

if (isset($_POST['submit'])) {
    $quantity = intval($_POST['qty']);
    $address = trim($_POST['address']);

    if ($quantity < 1) {
        $errors[] = "Quantity must be at least 1.";
    }

    if (empty($address)) {
        $errors[] = "Delivery address is required.";
    }

    if (empty($errors)) {
        $total = $item['price'] * $quantity;
        $order_date = date("Y-m-d H:i:s");
        $status = "Ordered";

        $sql_insert = "INSERT INTO tbl_order (item, price, qty, total, order_date, status, customer_name, customer_contact, customer_email, customer_address, uid)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_insert = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "siddssssssi",
            $item['title'], $item['price'], $quantity, $total,
            $order_date, $status,
            $user['full_name'], $user['phone'], $user['email'],
            $address, $user_id
        );

        if (mysqli_stmt_execute($stmt_insert)) {
            $_SESSION['order'] = "<div class='success text-center'>Order placed successfully!</div>";
            header('location:' . SITEURL);
            exit();
        } else {
            $errors[] = "Failed to place the order. Please try again.";
        }
    }
}
?>


<section style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container" style="max-width: 900px; margin: auto;">

        <h2 class="text-center" style="margin-bottom: 40px; color: #333;">Confirm Your Order</h2>

        <?php if (!empty($errors)) : ?>
            <div style="background: #ffe5e5; color: #cc0000; padding: 15px; margin-bottom: 20px; border-left: 5px solid #cc0000; border-radius:5px;">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- <form action="" method="POST" style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

            <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">

               
                <div style="flex: 1; text-align: center;">
                    <?php if (!empty($item['image_name'])) : ?>
                        <img src="<?php echo SITEURL . 'images/item/' . htmlspecialchars($item['image_name']); ?>" alt="Item Image" style="width:100%; max-width:300px; height:auto; border-radius:10px; object-fit:cover;">
                    <?php else : ?>
                        <div style="color:#888;">Image not available</div>
                    <?php endif; ?>
                </div>

                
                <div style="flex: 2;">
                    <h3 style="margin-bottom: 10px; color: #222;"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p style="font-size: 18px; color: #666;">Price: <strong>Rs. <?php echo htmlspecialchars($item['price']); ?></strong></p>

                    <input type="hidden" name="item" value="<?php echo htmlspecialchars($item['title']); ?>">
                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($item['price']); ?>">

                    <div style="margin: 20px 0;">
                        <label style="font-weight:bold;">Quantity</label>
                        <input type="number" name="qty" value="1" min="1" required style="width: 100%; padding:10px; margin-top:5px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <h4 style="margin-top:30px; margin-bottom:10px; color:#333;">Delivery Details</h4>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight:bold;">Full Name</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>"  style="width: 100%; padding:10px; border-radius:5px; background-color: #f0f0f0; border:1px solid #ccc; margin-top:5px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight:bold;">Phone Number</label>
                        <input type="tel" name="contact" value="<?php echo htmlspecialchars($user['phone']); ?>" style="width: 100%; padding:10px; border-radius:5px; background-color: #f0f0f0; border:1px solid #ccc; margin-top:5px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight:bold;">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" style="width: 100%; padding:10px; border-radius:5px; background-color: #f0f0f0; border:1px solid #ccc; margin-top:5px;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-weight:bold;">Address</label>
                        <textarea name="address" rows="4" placeholder="Street, City" required style="width: 100%; padding:10px; border-radius:5px; border:1px solid #ccc; margin-top:5px;"></textarea>
                    </div>

                    <button type="submit" name="submit" style="width: 100%; padding: 12px; background-color: #28a745; color: white; font-size:16px; border:none; border-radius:5px; cursor:pointer; transition:0.3s;">
                        Confirm Order
                    </button>
                </div>

            </div>

        </form> -->
        <form action="" method="POST" style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

            <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">

               
                <div style="flex: 1; text-align: center;">
                    <?php if (!empty($item['image_name'])) : ?>
                        <img src="<?php echo SITEURL . 'images/item/' . htmlspecialchars($item['image_name']); ?>" alt="Item Image" style="width:100%; max-width:300px; height:auto; border-radius:10px; object-fit:cover;">
                    <?php else : ?>
                        <div style="color:#888;">Image not available</div>
                    <?php endif; ?>
                </div>

                
                <div style="flex: 2;">
                    <h3 style="margin-bottom: 10px; color: #222;"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p style="font-size: 18px; color: #666;">Price: <strong>Rs. <?php echo htmlspecialchars($item['price']); ?></strong></p>

                    <input type="hidden" name="item" value="<?php echo htmlspecialchars($item['title']); ?>">
                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($item['price']); ?>">

                    <div style="margin: 20px 0;">
                        <label style="font-weight:bold;">Quantity</label>
                        <input type="number" name="qty" value="1" min="1" required style="width: 100%; padding:10px; margin-top:5px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <h4 style="margin-top:30px; margin-bottom:10px; color:#333;">Delivery Details</h4>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight:bold;">Full Name</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>"  style="width: 100%; padding:10px; border-radius:5px; background-color: #f0f0f0; border:1px solid #ccc; margin-top:5px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight:bold;">Phone Number</label>
                        <input type="tel" name="contact" value="<?php echo htmlspecialchars($user['phone']); ?>" style="width: 100%; padding:10px; border-radius:5px; background-color: #f0f0f0; border:1px solid #ccc; margin-top:5px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight:bold;">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" style="width: 100%; padding:10px; border-radius:5px; background-color: #f0f0f0; border:1px solid #ccc; margin-top:5px;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-weight:bold;">Address</label>
                        <textarea name="address" rows="4" placeholder="Street, City" required style="width: 100%; padding:10px; border-radius:5px; border:1px solid #ccc; margin-top:5px;"></textarea>
                    </div>

                    <button type="submit" name="submit" style="width: 100%; padding: 12px; background-color: #28a745; color: white; font-size:16px; border:none; border-radius:5px; cursor:pointer; transition:0.3s;">
                        Confirm Order
                    </button>
                </div>

            </div>

        </form>

    </div>
</section>

<?php include('partials-front/footer.php'); ?>
