<?php
include('config/constants.php');
include('partials-front/menu.php');
?>

<style>
.search-results {
    padding: 40px 0;
}
.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 30px;
}
.item-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
}
.item-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}
.item-details .price {
    color: #ff6b6b;
    font-weight: bold;
    margin-top: 10px;
}
.btn-primary {
    background: #ff6b6b;
    padding: 10px 20px;
    border-radius: 30px;
    color: white;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    margin: 5px 0;
}
</style>

<div class="search-results container">
    <h2 class="section-title text-center">
        <?php 
            if(isset($_POST['submit'])) {
                $search = trim($_POST['search']);
                echo "Search Results for: <em>" . htmlspecialchars($search) . "</em>";
            } else {
                echo "Please enter a search keyword.";
            }
        ?>
    </h2>

    <div class="items-grid">
        <?php
        if(isset($_POST['submit'])) {
            $search = mysqli_real_escape_string($conn, $_POST['search']);

            
            $sql = "SELECT * FROM tbl_items 
                    WHERE active='Yes' AND 
                    (title LIKE '%$search%' OR description LIKE '%$search%')";

            $res = mysqli_query($conn, $sql);

            if($res && mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    ?>
                    <div class="item-card">
                        <?php if($row['image_name'] != "") { ?>
                            <img src="<?php echo SITEURL; ?>images/item/<?php echo $row['image_name']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <?php } else { ?>
                            <div class="no-image">Image not available</div>
                        <?php } ?>
                        <div class="item-details">
                            <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p class="price">Rs. <?php echo $row['price']; ?></p>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $row['id']; ?>" class="btn-primary">Order Now</a>
                            <a href="<?php echo SITEURL; ?>add_to_cart.php?item_id=<?php echo $row['id']; ?>" class="btn-primary">Add to Cart 🛒</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No items found matching your search.</p>";
            }
        }
        ?>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>
