<?php include('partials/menu.php'); ?>

<?php 


$err = [];


if(isset($_POST['submit'])) {
    
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';

    
    if(empty($full_name)) {
        $err['full_name'] = "Enter Full Name";
    } elseif(!preg_match("/^[A-Za-z\s]+$/", $full_name)) {
        $err['full_name'] = "Full Name must only contain letters and spaces";
    }

    
    if(empty($username)) {
        $err['username'] = "Enter Username";
    } elseif(!preg_match("/^[a-zA-Z0-9]{4,29}$/", $username)) {
        $err['username'] = "Username must be alphanumeric and between 4 to 29 characters";
    }

    if(empty($_POST['password'])) {
        $err['password'] = "Enter Password";
    }

    
    if(empty($err)) {
        
        $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';

        
        $sql = "INSERT INTO tbl_admin (full_name, username, password) VALUES (?, ?, ?)";

        
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            
            mysqli_stmt_bind_param($stmt, "sss", $full_name, $username, $password);

            
            if(mysqli_stmt_execute($stmt)) {
                
                $_SESSION['add'] = "Admin Added Successfully";
                
                header("location:".SITEURL.'admin/manage-admin.php');
                exit;
            } else {
                
                $_SESSION['add'] = "Failed to add Admin";
                
                header("location:".SITEURL.'admin/add-admin.php');
                exit;
            }
        } else {
            
            $_SESSION['add'] = "Database error: Unable to prepare statement.";
            header("location:".SITEURL.'admin/add-admin.php');
            exit;
        }
    }
}
?>

<div class="main">
    <div class="wrapper">
        <h1>Add Admin</h1>
        <br>

        <?php
        if(isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }
        ?>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr><td colspan="2"><span class="error"><?php if(isset($err['full_name'])) echo $err['full_name']; ?></span></td></tr>
                <tr>
                    <td>Full Name:</td>
                    <td>
                        <input type="text" name="full_name" placeholder="Enter your name" value="<?php if(isset($full_name)) echo htmlspecialchars($full_name); ?>">
                    </td>
                </tr>

                <tr><td colspan="2"><span class="error"><?php if(isset($err['username'])) echo $err['username']; ?></span></td></tr>
                <tr>
                    <td>Username:</td>
                    <td>
                        <input type="text" name="username" placeholder="Your Username" value="<?php if(isset($username)) echo htmlspecialchars($username); ?>">
                    </td>
                </tr>

                <tr><td colspan="2"><span class="error"><?php if(isset($err['password'])) echo $err['password']; ?></span></td></tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password" placeholder="Your Password"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
