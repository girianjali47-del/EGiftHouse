<?php include('partials/menu.php');?>

<div class="main">
    <div class="wrapper">
        <h1>Change Password</h1>
        <br><br>

        <?php 
            if(isset($_GET['id']))
            {
                $id=$_GET['id'];
            }        
        ?>

        <form action="" method="POST">

        <table class="tbl-30">
            <tr>
                <td>Old Password:</td>
                <td>
                    <input type="password" name="current_password" placeholder="Current Password">
                </td>
            </tr>
            
            <tr>
                <td>New Password:</td>
                <td>
                    <input type="password" name="new_password" placeholder="New Password">
                </td>
            </tr>

            <tr>
                <td>Confirm Password</td>
                <td>
                    <input type="password" name="confirm_password" placeholder="Confirm Password">
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="submit" name="submit" value="Change Password" class="btn-secondary">
                </td>
            </tr>
        </table>
        </form>
    </div>
</div>

<?php
    
    if(isset($_POST['submit']))
    {
        //get data from form
        $id=$_POST['id'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        //check whether the user with current id exists or not
        $sql = "SELECT * FROM tbl_admin WHERE id=$id";

        //execute the query
        $res = mysqli_query($conn, $sql);

        if ($res==true)
        {
            //check whether data is available or not
            $count=mysqli_num_rows($res);
            
            if($count==1)
            {
                //user exists, verify old password
                $row = mysqli_fetch_assoc($res);
                $stored_password = $row['password'];

                if(password_verify($current_password, $stored_password))
                {
                    //check if new password and confirm password match
                    if($new_password==$confirm_password)
                    {
                        //hash the new password
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                        //update password
                        $sql2 = "UPDATE tbl_admin SET
                            password='$hashed_password'
                            WHERE id=$id
                        ";

                        //execute the query
                        $res2 = mysqli_query($conn, $sql2);

                        if($res2==true)
                        {
                            //password changed successfully
                            $_SESSION['change-password'] = "Password Changed Successfully";
                            header('location:'.SITEURL.'admin/manage-admin.php');
                        }
                        else
                        {
                            //failed to change password
                            $_SESSION['change-password'] = "Failed to Change Password";
                            header('location:'.SITEURL.'admin/manage-admin.php');
                        }
                    }
                    else
                    {
                        //new password and confirm password do not match
                        $_SESSION['password-not-match'] = "Passwords didn't match";
                        header('location:'.SITEURL.'admin/manage-admin.php');
                    }
                }
                else
                {
                    //current password is incorrect
                    $_SESSION['current-password-error'] = "Incorrect Current Password";
                    header('location:'.SITEURL.'admin/manage-admin.php');
                }
            }
            else
            {
                //user does not exist
                $_SESSION['user-not-found'] = "User Not Found";
                header('location:'.SITEURL.'admin/manage-admin.php');
            }
        }
        else
        {
            //query execution failed
            $_SESSION['query-error'] = "Query Execution Failed";
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
    }
?>

<?php include('partials/footer.php')?>
