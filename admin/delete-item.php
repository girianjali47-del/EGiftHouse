<?php
include('../config/constants.php');
session_start(); 

if (isset($_GET['id']) && isset($_GET['image_name'])) {
    
    $id = $_GET['id'];
    $image_name = $_GET['image_name'];

    
    if ($image_name != "") {
        
        $path = "../images/item/" . $image_name;

        
        $remove = unlink($path);

        
        if ($remove == false) {
            
            $_SESSION['upload'] = "<div class='error'>Failed to remove Image file</div>";
            
            header('location:' . SITEURL . 'admin/add-item.php');
            
            die();
        }
    }

    
    $sql = "DELETE FROM tbl_items WHERE id=$id";
    
    $res = mysqli_query($conn, $sql);

    
    if ($res == true) {
        
        $_SESSION['delete'] = "<div class='success'>Item Deleted Successfully.</div>";
    } else {
        
        $_SESSION['delete'] = "<div class='error'>Failed to Delete Item.</div>";
    }

   
    header('location:' . SITEURL . 'admin/item.php');
} else {
   
    $_SESSION['unauthorize'] = "<div class='error'>Unauthorized Access</div>";
    header('location:' . SITEURL . 'admin/item.php');
}
?>
