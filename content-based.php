<?php

$sql_current = "SELECT * FROM tbl_items WHERE id = $current_item_id AND active='Yes'";
$res_current = mysqli_query($conn, $sql_current);

if($res_current && mysqli_num_rows($res_current) > 0) {
    $current_item = mysqli_fetch_assoc($res_current);
    $current_category_id = $current_item['category_id'];
    $current_price = $current_item['price'];

    
    $min_price = $current_price * 0.8;
    $max_price = $current_price * 1.2;

    $sql_similar = "SELECT * FROM tbl_items 
                    WHERE category_id = $current_category_id 
                    AND id != $current_item_id 
                    AND active='Yes' 
                    AND price BETWEEN $min_price AND $max_price
                    LIMIT 6";

    $res_similar = mysqli_query($conn, $sql_similar);

    if($res_similar && mysqli_num_rows($res_similar) > 0) {
        while($item = mysqli_fetch_assoc($res_similar)) {
            
            ?>
            <div class="recommend-card">
                <?php if ($item['image_name'] != "") { ?>
                    <img src="<?php echo SITEURL; ?>images/item/<?php echo $item['image_name']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <?php } else { ?>
                    <div class="no-image">Image not available</div>
                <?php } ?>
                <div class="item-details">
                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                    <p class="price">Rs. <?php echo $item['price']; ?></p>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                    <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $item['id']; ?>" class="btn-primary">Order Now</a>
                    <a href="<?php echo SITEURL; ?>add_to_cart.php?item_id=<?php echo $item['id']; ?>" class="btn-primary">Add to Cart 🛒</a>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No similar items found.</p>";
    }
} else {
    echo "<p>Current item not found.</p>";
}
?>
