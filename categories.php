<?php include('partials-front/menu.php');?>



    
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Items</h2>

            <?php

                
                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";

                
                $res = mysqli_query($conn, $sql);

                
                $count = mysqli_num_rows($res);
                

                if($count>0)
                {
                    
                    while($row=mysqli_fetch_assoc($res))
                    {
                         
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        ?>

                        <a href="<?php echo SITEURL; ?>category-items.php?category_id=<?php echo $id; ?>">

                            <div class="box-3 float-container">
                                <?php
                                    
                                    if($image_name=="")
                                    {
                                        
                                        echo " <div class='error'>Image not available<?div> ";
                                    }
                                    else
                                    {
                                        
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name;?>" alt="Camera" class="img-responsive">
                                        <?php
                                    }
                                ?>
                                

                                <h3 class="float-text "><?php echo $title; ?></h3>
                            </div>
                            </a>
                        
                        <?php

                    }

                }
                else
                {
                    
                    echo "<div class='error'>Category not Found</div>";
                }
                ?>


            <div class="clearfix"></div>
        </div>
    </section>
    


    <?php include('partials-front/footer.php');?>