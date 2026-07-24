<?php


include('../config/constants.php');


if (isset($_GET['username']) && !empty($_GET['username'])) {
    
    $username = trim($_GET['username']);
    
    if (!empty($username)) {
        
        $sql = "DELETE FROM tbl_users WHERE username = ?";
        
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            
            mysqli_stmt_bind_param($stmt, "s", $username);
            
           
            if (mysqli_stmt_execute($stmt)) {
                
                $_SESSION['delete'] = "User Deleted Successfully";
                header('location:'.SITEURL.'admin/manage-user.php');
                exit;
            } else {
                
                $_SESSION['delete'] = "Failed to Delete User. Try Again";
                header('location:'.SITEURL.'admin/manage-user.php');
                exit;
            }
        } else {
            
            $_SESSION['delete'] = "Failed to Prepare Statement";
            header('location:'.SITEURL.'admin/manage-user.php');
            exit;
        }
    } else {
        
        $_SESSION['delete'] = "Invalid Username";
        header('location:'.SITEURL.'admin/manage-user.php');
        exit;
    }
} else {
    
    $_SESSION['delete'] = "Username Missing";
    header('location:'.SITEURL.'admin/manage-user.php');
    exit;
}

?>
