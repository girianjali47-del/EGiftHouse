<?php
    
    include('../config/constants.php');
    
    if(isset($_GET['id']) AND isset($_GET['image_name']))
    {
        
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        
        if($image_name !== "")

        {
            
            $path = "../images/category/".$image_name;

            
            $remove = unlink($path);

            
            if($remove==false)
            {
                
                $_SESSION['remove'] = "Failed to remove category image";


                
                header('location:'.SITEURL.'admin/category.php');

            
                die();
            }
        }
        
        $sql = "DELETE FROM tbl_category WHERE id=$id";

        
        $res = mysqli_query($conn, $sql);

        
        if($res==true)
        {
            
            $_SESSION['delete'] = "Category deleted sucessfully";
            
            header('location:'.SITEURL.'admin/category.php');
            
        }
        else
        {
            
            $_SESSION['delete'] = "Failed to delete Category";
            
            header('location:'.SITEURL.'admin/category.php');
        }
  

    }

    else
    {
        
        header('location:'.SITEURL.'admin/category.php');

    }
?>