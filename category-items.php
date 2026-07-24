<?php include('partials-front/menu.php'); ?>

<?php

if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']); 

    
    $sql = "SELECT title, image_name FROM tbl_category WHERE id=$category_id";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $category_title = $row['title'];
        $category_image = $row['image_name'];
    } else {
        $category_title = 'Unknown';
        $category_image = '';
    }
} else {
    
    header('Location: ' . SITEURL);
    exit();
}
?>


<style>
.hero-banner {
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
    url('<?php echo (!empty($category_image)) ? SITEURL . "images/category/" . $category_image : SITEURL . "images/default-banner.jpg"; ?>') no-repeat center center/cover;
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-align: center;
}

.hero-banner h2 {
    font-size: 48px;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
}


.item-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    justify-content: center;
    padding: 50px 20px;
}

.item-box {
    width: 300px;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.item-box:hover {
    transform: translateY(-8px);
}

.item-box img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.item-content {
    padding: 20px;
}

.item-content h3 {
    margin: 10px 0;
    font-size: 22px;
}

.item-content .price {
    color: #ff6b6b;
    font-weight: bold;
    font-size: 20px;
    margin-bottom: 10px;
}

.item-content p {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

.button-group {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.order-btn, .cart-btn {
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.order-btn {
    background: #ff6b6b;
    color: #fff;
}

.order-btn:hover {
    background: #ff4757;
}

.cart-btn {
    background: #1e90ff;
    color: #fff;
}

.cart-btn:hover {
    background: #187bcd;
}

.error {
    text-align: center;
    color: red;
    margin: 20px 0;
}
</style>


<section class="hero-banner">
    <h2><?php echo htmlspecialchars($category_title); ?></h2>
</section>


<section class="item-menu">
    <div class="container">
        <div class="item-grid">

            <?php
            $sql2 = "SELECT * FROM tbl_items WHERE category_id=$category_id";
            $res2 = mysqli_query($conn, $sql2);

            if (mysqli_num_rows($res2) > 0) {
                while ($row2 = mysqli_fetch_assoc($res2)) {
                    $id = $row2['id'];
                    $title = $row2['title'];
                    $price = $row2['price'];
                    $description = $row2['description'];
                    $image_name = $row2['image_name'];
                    ?>

                    <div class="item-box">
                        <?php if (!empty($image_name)) { ?>
                            <img src="<?php echo SITEURL . "images/item/" . htmlspecialchars($image_name); ?>" alt="<?php echo htmlspecialchars($title); ?>">
                        <?php } else { ?>
                            <img src="<?php echo SITEURL . "images/default-item.png"; ?>" alt="Image not available">
                        <?php } ?>

                        <div class="item-content">
                            <h3><?php echo htmlspecialchars($title); ?></h3>
                            <div class="price">Rs. <?php echo number_format($price); ?></div>
                            <p><?php echo htmlspecialchars(mb_strimwidth($description, 0, 70, '...')); ?></p>

                            <div class="button-group">
                                <a href="<?php echo SITEURL . "order.php?item_id=" . $id; ?>" class="order-btn">Order Now</a>
                                <div class="button-group">
    
    
      <form method="POST" action="<?php echo SITEURL; ?>cart.php" style="display:inline;">
        <input type="hidden" name="add_to_cart" value="1">
        <input type="hidden" name="item_id" value="<?php echo $id; ?>">
        <input type="hidden" name="item_title" value="<?php echo htmlspecialchars($title); ?>">
        <input type="hidden" name="item_price" value="<?php echo $price; ?>">
        <input type="hidden" name="item_image" value="<?php echo htmlspecialchars($image_name); ?>">
        <button type="submit" class="cart-btn">Add to Cart 🛒</button>
    </form>
</div>

                            </div>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo "<div class='error'>No items available in this category!</div>";
            }
            ?>

        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>
