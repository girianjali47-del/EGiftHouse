<?php 
    include('partials-front/menu.php'); 
    // include('config.php'); 
?>


<section class="search text-center">
    <div class="container">
        <?php
            
            if (isset($_POST['search'])) {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
            }
        ?>
        <h2>Items on Your Search: <a href="#" class="text-white">"<?php echo $search; ?>"</a></h2>
    </div>
</section>



<section class="menu">
    <div class="container">
        <h2 class="text-center">Item Menu</h2>

        <?php
        
        $sql = "SELECT * FROM tbl_items WHERE title LIKE '%$search%' OR description LIKE '%$search%'";

        
        $res = mysqli_query($conn, $sql);

       
        $count = mysqli_num_rows($res);

        
        if ($count > 0) {
            
            while ($row = mysqli_fetch_assoc($res)) {
                
                $id = $row['id'];
                $title = $row['title'];
                $price = $row['price'];
                $description = $row['description'];
                $image_name = $row['image_name'];
                ?>
                <div class="explore-box box-3">
                    <div class="explore-menu-img">
                        <?php
                            
                            if ($image_name == "") {
                               
                                echo "<div class='error'>Image Not Available</div>";
                            } else {
                                
                                ?>
                                <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                                <?php
                            }
                        ?>
                    </div>

                    <div class="explore-menu-desc">
                        <h4><?php echo $title; ?></h4>
                        <p class="price">Rs.<?php echo $price; ?></p>
                        <p class="item-detail">
                            <?php echo $description; ?>
                        </p>
                        <br>

                        
                     <form method="POST" action="cart.php">
                       <input type="hidden" name="item_id" value="<?php echo $id; ?>">
                       <input type="hidden" name="title" value="<?php echo $title; ?>">
                       <input type="hidden" name="price" value="<?php echo $price; ?>">
                       <input type="hidden" name="image_name" value="<?php echo $image_name; ?>">
                       <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart 🛒</button>
                     </form>


                        <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                    </div>
                </div>
                <?php
            }
        } else {
            
            echo "<div class='error'>Item Not Found</div>";
        }
        ?>

        <div class="clearfix"></div>
    </div>
</section>


<?php include('partials-front/footer.php'); ?>
