<?php include('partials/menu.php'); ?>

<?php
    
    $errors = [];

    
    if(isset($_POST['submit'])) {
        
        if(empty($_POST['title'])) {
            $errors['title'] = "Title is required";
        } else {
            $title = $_POST['title'];
        }

        
        $image_name = $_FILES['image']['name'];
        if(!empty($image_name)) {
            $image_tmp = $_FILES['image']['tmp_name'];

            
            $max_size = 5 * 1024 * 1024; 
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            
            if($_FILES['image']['size'] > $max_size) {
                $errors['image'] = "File size exceeds limit (5MB)";
            }

            
            if(!in_array($_FILES['image']['type'], $allowed_types)) {
                $errors['image'] = "Only JPEG, PNG, and GIF files are allowed";
            }
        }

        
        if(empty($errors)) {
            
            if(isset($_POST['id'])) {
                $id = $_POST['id'];
                
               
                $sql = "SELECT * FROM tbl_category WHERE id=?";
                $stmt = mysqli_prepare($conn, $sql);

                
                mysqli_stmt_bind_param($stmt, "i", $id);

                
                mysqli_stmt_execute($stmt);

                
                $res = mysqli_stmt_get_result($stmt);

                
                if(mysqli_num_rows($res) == 1) {
                    
                    $row = mysqli_fetch_assoc($res);
                    $current_image = $row['image_name'];
                    $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";
                    $active = isset($_POST['active']) ? $_POST['active'] : "No";

                    
                    if(!empty($image_name)) {
                        
                        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                        $image_name = "Category_" . rand(000, 999) . '.' . $ext;

                        
                        $upload_dir = "../images/category/";
                        $upload_path = $upload_dir . $image_name;

                        if(move_uploaded_file($image_tmp, $upload_path)) {
                            
                            if($current_image != "") {
                                $remove_path = "../images/category/".$current_image;
                                unlink($remove_path);
                            }
                        } else {
                            
                            $_SESSION['upload'] = "Failed to Upload Image";
                            header('location:'.SITEURL.'admin/category.php');
                            exit;
                        }
                    } else {
                        
                        $image_name = $current_image;
                    }

                    
                    $sql_update = "UPDATE tbl_category SET title=?, image_name=?, featured=?, active=? WHERE id=?";
                    $stmt_update = mysqli_prepare($conn, $sql_update);

                    
                    mysqli_stmt_bind_param($stmt_update, "ssssi", $title, $image_name, $featured, $active, $id);

                   
                    if(mysqli_stmt_execute($stmt_update)) {
                        
                        $_SESSION['update'] = "Category Updated Successfully";
                        header('location:'.SITEURL.'admin/category.php');
                        exit;
                    } else {
                        
                        $_SESSION['update'] = "Failed to Update Category";
                        header('location:'.SITEURL.'admin/category.php');
                        exit;
                    }
                } else {
                    
                    $_SESSION['no-category-found'] = "Category not found";
                    header('location:'.SITEURL.'admin/category.php');
                    exit;
                }
            } else {
                
                header('location:'.SITEURL.'admin/category.php');
                exit;
            }
        }
    } else {
        
        if(isset($_GET['id'])) {
            
            $id = $_GET['id'];
            
            
            $sql = "SELECT * FROM tbl_category WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);

            
            mysqli_stmt_bind_param($stmt, "i", $id);

            
            mysqli_stmt_execute($stmt);

           
            $res = mysqli_stmt_get_result($stmt);

            
            if(mysqli_num_rows($res) == 1) {
                
                $row = mysqli_fetch_assoc($res);
                $title = $row['title'];
                $current_image = $row['image_name'];
                $featured = $row['featured'];
                $active = $row['active'];
            } else {
                
                $_SESSION['no-category-found'] = "Category not found";
                header('location:'.SITEURL.'admin/category.php');
                exit;
            }
        } else {
            
            header('location:'.SITEURL.'admin/category.php');
            exit;
        }
    }
?>

<div class="main">
    <div class="wrapper">
        <h1>Update Category</h1>

        <br><br>

        
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
                    <td>
                        <input type="text" name="title" value="<?php echo isset($title) ? $title : ''; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if($current_image != ""): ?>
                            <img src="<?php echo SITEURL; ?>images/category/<?php echo $current_image; ?>" width="150px">
                        <?php else: ?>
                            Image not added
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image:</td>
                    <td>
                        <input type="file" name="image"> 
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?php if(isset($featured) && $featured=="Yes"){echo "checked";} ?>> Yes
                        <input type="radio" name="featured" value="No" <?php if(isset($featured) && $featured=="No"){echo "checked";} ?>> No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php if(isset($active) && $active=="Yes"){echo "checked";} ?>> Yes
                        <input type="radio" name="active" value="No" <?php if(isset($active) && $active=="No"){echo "checked";} ?>> No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
