<?php include('partials-front/menu.php'); ?>

<style>

.hero-categories {
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('images/hero-bg.jpg') center/cover no-repeat;
    color: white;
    padding: 50px 0;
}

.split {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    align-items: center;
}

.hero-left, .hero-right {
    flex: 1;
}

.hero-content {
    max-width: 500px;
    margin: auto;
}

.hero-content h1 {
    font-size: 42px;
    margin-bottom: 30px;
}

.hero-content p {
    font-size: 18px;
    margin-bottom: 30px;
}

.search-form input {
    padding: 12px;
    width: 65%;
    border-radius: 30px 0 0 30px;
    border: none;
}

.search-form button {
    padding: 12px 20px;
    border-radius: 0 30px 30px 0;
    background: #ff6b6b;
    color: white;
    border: none;
    cursor: pointer;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 30px;
    margin-top: 20px;
}

.category-card {
    background: white;
    color: black;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    transition: transform 0.3s ease;
    text-decoration: none;
}

.category-card:hover {
    transform: scale(1.05);
}

.category-card img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
}

.featured-items {
    padding: 50px 0;
    background: #f7f7f7;
}

.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.item-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    transition: transform 0.3s ease;
}

.item-card:hover {
    transform: translateY(-5px);
}

.item-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}

.item-details {
    margin-top: 15px;
}

.item-details .price {
    color: #ff6b6b;
    font-weight: bold;
    margin-top: 10px;
}

.item-buttons {
    margin-top: 15px;
}

.btn-primary {
    background: #ff6b6b;
    padding: 10px 20px;
    border-radius: 30px;
    color: white;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    margin: 5px;
}

.btn-primary:hover {
    background: #ff4757;
}


.section-title {
    font-size: 32px;
    margin-bottom: 30px;
}

.text-center {
    text-align: center;
}
</style>


<section class="hero-categories">
    <div class="container split">
        
    
        <div class="hero-left">
            <div class="hero-content">
                <h1>Welcome to E-Gifthouse 🎁</h1>
                <p> Perfect Gifts for Every Occasion!!</p>
                <form action="<?php echo SITEURL; ?>item-search.php" method="POST" class="search-form">
                    <input type="search" name="search" placeholder="What are you looking for?">
                    <button type="submit" name="submit" class="btn-primary">Search</button>
                </form>
            </div>
        </div>
        
<div class="text-center" style="margin: 50px 0;">
    <a href="<?php echo SITEURL; ?>recommendation.php">
        <button style="padding: 12px 30px; font-size: 18px; background-color: #4CAF50; color: white; border: none; border-radius: 30px; cursor: pointer;">
            🎯 Get Recommendations
        </button>
    </a>
</div>


        
        <div class="hero-right">
            <h2 class="section-title">Categories</h2>
            <div class="categories-grid">
                <?php 
                    $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 6";
                    $res = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($res);

                    if($count > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $id = $row['id'];
                            $title = $row['title'];
                            $image_name = $row['image_name'];
                            ?>
                            <a href="<?php echo SITEURL; ?>category-items.php?category_id=<?php echo $id; ?>" class="category-card">
                                <?php if($image_name != "") { ?>
                                    <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" alt="<?php echo $title; ?>">
                                <?php } else { ?>
                                    <div class="no-image">Image not available</div>
                                <?php } ?>
                                <h4><?php echo $title; ?></h4>
                            </a>
                            <?php
                        }
                    } else {
                        echo "<div class='error'>No Categories Found</div>";
                    }
                ?>
            </div>
        </div>

    </div>
</section>



<section class="featured-items">
    <div class="container">
        <h2 class="section-title text-center">Explore Featured Items</h2>
        
        <div class="items-grid">
            <?php
            $sql2 = "SELECT * FROM tbl_items WHERE active='Yes' AND featured='Yes' LIMIT 6";
            $res2 = mysqli_query($conn, $sql2);
            $count2 = mysqli_num_rows($res2);

            if($count2 > 0) {
                while($row2 = mysqli_fetch_assoc($res2)) {
                    $id = $row2['id'];
                    $title = $row2['title'];
                    $price = $row2['price'];
                    $description = $row2['description'];
                    $image_name = $row2['image_name'];
                    ?>
                    <div class="item-card">
                        <?php if($image_name != "") { ?>
                            <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" alt="<?php echo $title; ?>">
                        <?php } else { ?>
                            <div class="no-image">Image not available</div>
                        <?php } ?>
                        <div class="item-details">
                            <h4><?php echo $title; ?></h4>
                            <p class="price">Rs. <?php echo $price; ?></p>
                            <p class="desc"><?php echo $description; ?></p>
                            <div class="item-buttons">
                                <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $id; ?>" class="btn-primary">Order Now </a>
                                <a href="<?php echo SITEURL; ?>add_to_cart.php?item_id=<?php echo $id; ?>" class="btn btn-primary">add to cart 🛒</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='error'>No Featured Items Found</div>";
            }
            ?>
        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?> 