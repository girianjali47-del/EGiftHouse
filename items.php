<?php
session_start();
include('config.php');
include('partials-front/menu.php');


if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $sql = "SELECT * FROM tbl_items WHERE id = $item_id";
    $res = mysqli_query($conn, $sql);
    $item = mysqli_fetch_assoc($res);
} else {
    header("Location: index.php");
}
?>

<section class="item-details">
    <div class="container">
        <h2 class="section-title text-center">Item Details</h2>

        <?php if ($item) { ?>
            <div class="item-card">
                <div class="item-img">
                    <?php if ($item['image_name'] != "") { ?>
                        <img src="<?php echo SITEURL; ?>images/item/<?php echo $item['image_name']; ?>" alt="<?php echo $item['title']; ?>">
                    <?php } else { ?>
                        <div class="no-image">Image not available</div>
                    <?php } ?>
                </div>

                <div class="item-info">
                    <h4><?php echo $item['title']; ?></h4>
                    <p class="price">Rs. <?php echo $item['price']; ?></p>
                    <p class="description"><?php echo $item['description']; ?></p>

                    
                    <button class="btn btn-primary add-to-cart-btn" data-id="<?php echo $item['id']; ?>">Add to Cart 🛒</button>

                    
                    <a href="order.php?item_id=<?php echo $item['id']; ?>" class="btn btn-primary">Order Now</a>
                </div>
            </div>
        <?php } else { ?>
            <div class="error">Item not found!</div>
        <?php } ?>
    </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const addToCartBtn = document.querySelector('.add-to-cart-btn');

    addToCartBtn.addEventListener('click', function () {
        const itemId = this.getAttribute('data-id');

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'item_id=' + itemId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.innerText = data.cart_count;
                }

                
                alert("Item added to cart!");
            } else {
                alert("Failed to add to cart.");
            }
        });
    });
});
</script>

<?php include('partials-front/footer.php'); ?>
