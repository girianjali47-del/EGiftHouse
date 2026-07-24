<?php include('partials/menu.php'); ?>

<?php
   
    $errors = [];

   
    if(isset($_POST['submit'])) {
        
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $price = isset($_POST['price']) ? $_POST['price'] : '';

        
        if(empty($title)) {
            $errors['title'] = 'Title is required';
        }

        
        if(empty($description)) {
            $errors['description'] = 'Description is required';
        }

        
        if(empty($price)) {
            $errors['price'] = 'Price is required';
        } elseif(!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
            $errors['price'] = 'Invalid price format';
        }

        
        if(isset($_FILES['image']['name'])) {
            $image_name = $_FILES['image']['name'];
            if(empty($image_name)) {
                $errors['image'] = 'Please choose an image';
            }
        } else {
            $errors['image'] = 'Image upload error';
        }

       
        if(empty($errors)) {
            
            $image_name = '';
            if(isset($_FILES['image']['name'])) {
                $image_name = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $image_name = 'item-name-' . rand(0000, 9999) . '.' . $ext;
                $upload_dir = "../images/item/";
                $upload_path = $upload_dir . $image_name;

                
                if(move_uploaded_file($image_tmp, $upload_path)) {
                    
                } else {
                    
                    $_SESSION['upload'] = '<div class="error">Failed to upload image</div>';
                    header('location:'.SITEURL.'admin/add-item.php');
                    exit;
                }
            }

            
            $category = isset($_POST['category']) ? $_POST['category'] : '';
            $featured = isset($_POST['featured']) ? $_POST['featured'] : 'No';
            $active = isset($_POST['active']) ? $_POST['active'] : 'No';

            
            $sql = "INSERT INTO tbl_items (title, description, price, image_name, category_id, featured, active) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            
            mysqli_stmt_bind_param($stmt, "ssssiss", $title, $description, $price, $image_name, $category, $featured, $active);

            
            if(mysqli_stmt_execute($stmt)) {
              
                $_SESSION['add'] = '<div class="success">Item Added Successfully</div>';
                header('location:'.SITEURL.'admin/item.php');
                exit;
            } else {
                
                $_SESSION['add'] = '<div class="error">Failed to add Item</div>';
                header('location:'.SITEURL.'admin/item.php');
                exit;
            }
        }
    }
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Item</h1>

        <br><br>

        <?php
            
            if(isset($_SESSION['upload'])) {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        ?>

        
        <?php if(!empty($errors)): ?>
            <div class="error">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" placeholder="Title of the Item" value="<?php echo isset($title) ? $title : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" cols="30" rows="5" placeholder="Description of Item"><?php echo isset($description) ? $description : ''; ?></textarea></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><input type="text" name="price" placeholder="Price of the Item" value="<?php echo isset($price) ? $price : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Select Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category">
                            <?php
                                
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                $res = mysqli_query($conn, $sql);

                                if(mysqli_num_rows($res) > 0) {
                                    while($row = mysqli_fetch_assoc($res)) {
                                        $id = $row['id'];
                                        $title = $row['title'];
                                        echo "<option value='$id'>$title</option>";
                                    }
                                } else {
                                    echo "<option value='0'>No Category Found</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Item" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
