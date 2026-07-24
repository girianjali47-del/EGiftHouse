<?php include('partials/menu.php');?>

<div class="main">
            <div class="wrapper">
                <h1>Order</h1>

                <br>

                <br>

                <?php
                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }
                ?>
                <br><br>
                <table class="tbl-full">
                    <tr>
                        <th>S.N.</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty.</th>
                        <th>Total</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Customer Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                        //get all the order from db
                        $sql = "SELECT * FROM tbl_order ORDER BY id DESC"; //display latest order at top
                        //execute
                        $res = mysqli_query($conn, $sql);
                        //counrt
                        $count = mysqli_num_rows($res);

                        $sn = 1;

                        if($count>0)
                        {
                            //order avi
                            while($row=mysqli_fetch_assoc($res))
                            {
                                //get all the order detali
                                $id = $row['id'];
                                $item = $row['item'];
                                $price = $row['price'];
                                $qty = $row['qty'];
                                $total = $row['total'];
                                $order_date = $row['order_date'];
                                $status = $row['status'];
                                $customer_name = $row['customer_name'];
                                $customer_contact = $row['customer_contact'];
                                $customer_email = $row['customer_email'];
                                $customer_address = $row['customer_address'];

                                ?>
                                    
                                    <tr>
                                        <td><?php echo $sn++; ?></td>
                                        <td><?php echo $item; ?></td>
                                        <td>Rs.<?php echo $price; ?></td>
                                        <td><?php echo $qty; ?></td>
                                        <td>Rs.<?php echo $total; ?></td>
                                        <td><?php echo $order_date; ?></td>
                                        <td><?php echo $status; ?></td>
                                        <td><?php echo $customer_name; ?></td>
                                        <td><?php echo $customer_contact; ?></td>
                                        <td><?php echo $customer_email; ?></td>
                                        <td><?php echo $customer_address; ?></td>
                                        <td>
                                            <a href="<?php echo SITEURL; ?>admin/update-order.php?id=<?php echo $id; ?>" class="btn-secondary">Update Order</a>
                                            
                                        </td>
                                    </tr>

                                <?php
                            }
                        }
                        else
                        {
                            //order not avi
                            echo "<tr><td colspan='12' class='error'>Order Not Anailable</td></tr>";
                        }
                    ?>
                    


                </table>

            </div>
            
</div>







<?php include('partials/footer.php')?>