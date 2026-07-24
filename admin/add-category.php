<?php include('partials/menu.php'); ?>

<?php
   
    if(isset($_POST['submit'])) {
        
        $err_title = '';
        $err = 0;

        
        if(isset($_POST['title']) && !empty(trim($_POST['title']))) {
            $title = trim($_POST['title']);
            
            
            $sql_check = "SELECT * FROM tbl_category WHERE title = ?";
            $stmt_check = mysqli_prepare($conn, $sql_check);
            mysqli_stmt_bind_param($stmt_check, "s", $title);
            mysqli_stmt_execute($stmt_check);
            $res_check = mysqli_stmt_get_result($stmt_check);
            if(mysqli_num_rows($res_check) > 0) {
                $err_title = 'Category title already exists';
                $err++;
            }
        } else {
            $err_title = 'Enter title';
            $err++;
        }

        
        $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";
        $active = isset($_POST['active']) ? $_POST['active'] : "No";

        
        if(isset($_FILES['image']['name'])) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];

            
            if(!empty($image_name)) {
                
                $img_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

                
                $image_name = "Category_" . rand(1000, 9999) . '.' . $img_ext;

               
                $upload_dir = "../images/category/";
                $upload_path = $upload_dir . $image_name;

                if(move_uploaded_file($image_tmp, $upload_path)) {
                   
                } else {
                    
                    $_SESSION['upload'] = "Failed to Upload Image";
                    header('location:'.SITEURL.'admin/add-category.php');
                    exit;
                }
            } else {
                
                $image_name = "";
                $err++;
            }
        } else {
            
            $image_name = "";
            $err++;
        }

        
        if($err == 0) {
            
            $sql = "INSERT INTO tbl_category (title, image_name, featured, active) VALUES (?, ?, ?, ?)";
            
            
            $stmt = mysqli_prepare($conn, $sql);
            
            
            mysqli_stmt_bind_param($stmt, "ssss", $title, $image_name, $featured, $active);
            
            
            if(mysqli_stmt_execute($stmt)) {
                
                $_SESSION['add'] = "Category Added Successfully";
                header('location:'.SITEURL.'admin/category.php');
                exit;
            } else {
                
                $_SESSION['add'] = "Failed to Add Category";
                header('location:'.SITEURL.'admin/add-category.php');
                exit;
            }
        }
    }
?>

<div class="main">
    <div class="wrapper">
        <h1>Add Category</h1>
        <br><br>

        <?php
            if(isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
            
            if(isset($_SESSION['upload'])) {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        ?>

        <br><br>

        
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="Category Title">
                        <span class="error"><?php if(isset($err_title)) echo $err_title; ?></span>
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes"> Yes
                        <input type="radio" name="featured" value="No" checked> No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes
                        <input type="radio" name="active" value="No" checked> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
        
    </div>
</div>

<?php include('partials/footer.php') ?>
