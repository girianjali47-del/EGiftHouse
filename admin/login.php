<?php
session_start();
include('../config/constants.php'); 

$err = [];
$loginMessage = "";


if (isset($_POST['submit'])) {
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $err['username'] = "Enter username";
    }
    if ($password === '') {
        $err['password'] = "Enter password";
    }

    if (empty($err)) {
        
        $sql = "SELECT * FROM tbl_admin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if (password_verify($password, $row['password'])) {
                    // Successful login
                    $_SESSION['login'] = "Login Successful";
                    $_SESSION['user'] = $username;
                    $_SESSION['admin_logged_in'] = true;

                    header('Location: ' . SITEURL . 'admin/index.php');
                    exit;
                } else {
                    $loginMessage = "<span class='error'>Incorrect username or password.</span>";
                }
            } else {
                $loginMessage = "<span class='error'>Incorrect username or password.</span>";
            }
            mysqli_stmt_close($stmt);
        } else {
            $loginMessage = "<span class='error'>Database query error.</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .error { color: red; }
        .login {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: Arial, sans-serif;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        input[type=submit]:hover {
            background-color: #0056b3;
        }
        h1.text-center {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login">
    <h1 class="text-center">Admin Login</h1>

    <?php if ($loginMessage): ?>
        <div><?php echo $loginMessage; ?></div>
    <?php endif; ?>

    <form action="" method="POST" autocomplete="off">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
        <?php if(isset($err['username'])): ?>
            <div class="error"><?php echo $err['username']; ?></div>
        <?php endif; ?>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password">
        <?php if(isset($err['password'])): ?>
            <div class="error"><?php echo $err['password']; ?></div>
        <?php endif; ?>

        <input type="submit" name="submit" value="Login">
    </form>
</div>

</body>
</html>
