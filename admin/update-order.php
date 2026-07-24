<?php include('partials/menu.php'); ?>
<div class="main">
    <div class="wrapper">
        <h1>Update Order</h1>
        <br><br>

        <?php
            
            if(isset($_GET['id'])) {
                
                $id = $_GET['id'];
                
                $sql = "SELECT * FROM tbl_order WHERE id=$id";
                // Execute the query
                $res = mysqli_query($conn, $sql);
                // Count the rows
                $count = mysqli_num_rows($res);
                if($count == 1) {
                    // Get the details
                    $row = mysqli_fetch_assoc($res);

                    $item = $row['item'];
                    $price = $row['price'];
                    $qty = $row['qty'];
                    $status = $row['status'];
                    $customer_name = $row['customer_name'];
                    $customer_contact = $row['customer_contact'];
                    $customer_email = $row['customer_email'];
                    $customer_address = $row['customer_address'];

                } else {
                    
                    header('location:'.SITEURL.'admin/order.php');
                }
            } else {
                
                header('location:'.SITEURL.'admin/order.php');
            }
        ?>

<?php
            
            $err = [];
            if(isset($_POST['submit'])) {
                // Get all values from form
                $id = $_POST['id'];
                $price = $_POST['price'];
                $qty = $_POST['qty'];
                $total = $price * $qty;
                $status = $_POST['status'];
                $customer_name = $_POST['customer_name'];
                $customer_contact = $_POST['customer_contact'];
                $customer_email = $_POST['customer_email'];
                $customer_address = $_POST['customer_address'];

                if(empty($qty) || !is_numeric($qty) || $qty <= 0) {
                    $err['quantity'] = "Quantity must be greater than zero.";
                }

                if(empty($customer_name)){
                    $err['customer_name'] = "Customer name is required";
                }
                if(empty($customer_email)){
                    $err['customer_email'] = "Customer email is required";
                } else if(!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
                    $err['customer_email'] = "Enter valid email";
                }


                // Update values
                if(empty($err)){
                    
                    $sql2 = "UPDATE tbl_order SET
                        qty = $qty,
                        total = $total,
                        status = '$status',
                        customer_name = '$customer_name',
                        customer_contact = '$customer_contact',
                        customer_email = '$customer_email',
                        customer_address = '$customer_address'
                        WHERE id=$id";
                    
                    // Execute the query
                    $res2 = mysqli_query($conn, $sql2);
    
                    
                    if($res2 == true) {
                        // Updated
                        $_SESSION['update'] = "<div class='success'>Order Updated Successfully</div>";
                        header('location:'.SITEURL.'admin/order.php');
                    } else {
                        // Failed to update
                        $_SESSION['update'] = "<div class='error'>Failed to Update Order</div>";
                        header('location:'.SITEURL.'admin/order.php');
                    }
                }
            }
        ?>

        <form action="" method="POST">

            <?php if(!empty($err)): ?>
                <div class="error">
                    <?php foreach($err as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>   

            <table class="tbl-30">
                <tr>
                    <td>Item Name</td>
                    <td><b><?php echo $item; ?></b></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><b>$<?php echo $price; ?></b></td>
                </tr>
                <tr>
                    <td>Qty</td>
                    <td>
                        <input type="number" name="qty" value="<?php echo $qty; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <select name="status">
                            <option <?php if($status=="Ordered"){echo "selected";} ?> value="Ordered">Ordered</option>
                            <option <?php if($status=="On Delivery"){echo "selected";} ?> value="On Delivery">On Delivery</option>
                            <option <?php if($status=="Delivered"){echo "selected";} ?> value="Delivered">Delivered</option>
                            <option <?php if($status=="Cancelled"){echo "selected";} ?> value="Cancelled">Cancelled</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td>
                        <input type="text" name="customer_name" value="<?php echo $customer_name; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Customer Contact</td>
                    <td>
                        <input type="text" name="customer_contact" value="<?php echo $customer_contact; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Customer Email</td>
                    <td>
                        <input type="text" name="customer_email" value="<?php echo $customer_email; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Customer Address</td>
                    <td>
                       <textarea name="customer_address" cols="30" rows="5"><?php echo $customer_address; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="price" value="<?php echo $price; ?>">
                        <input type="submit" name="submit" value="Update Order" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
        
    </div>
</div>

<?php include('partials/footer.php'); ?>
