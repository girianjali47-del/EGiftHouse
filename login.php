<?php
include('partials-front/menu.php');
//session_start();

if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) {
    header("location: user-dashboard.php");
    exit();
}


$err = [];


if(isset($_POST['submit'])) {
    if(isset($_POST['username']) && !empty(trim($_POST['username']))) {
        $username = trim($_POST['username']);
    } else {
        $err['username'] = "Enter username";
    }

    if(isset($_POST['password']) && !empty($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $err['password'] = "Enter password";
    }

    if(empty($err)) {
        $sql = "SELECT * FROM tbl_users WHERE username=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res) {
            $row = mysqli_fetch_assoc($res);
            if ($row && password_verify($password, $row['password'])) {
                $_SESSION['login'] = "Login Successful";
                $_SESSION['user'] = $username;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['user_logged_in'] = true;
                header('Location: ' . SITEURL . 'user-dashboard.php');
                exit;
            } else {
                $_SESSION['login'] = "<div class='error-message'>Username or Password didn't Match</div>";
                header('Location: ' . SITEURL . 'login.php');
                exit;
            }
        } else {
            $_SESSION['login'] = "<div class='error-message'>Database error. Try again.</div>";
            header('Location: ' . SITEURL . 'login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            background: #2a2e2eff;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 60px auto;
            padding: 15px;
            background: #5d9ec7ff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 12px;
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
            width: 90%;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #007bff;
        }

        .btn-submit {
            width: 90%;
            background: #007bff;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 15px;
            font-size: 18px;
            cursor: pointer;
            
        }

        .btn-submit:hover {
            background: #0056b3;
        }

        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
            font-size: 14px;
        }

    </style>
</head>

<body>

<div class="login-container">
    <h1>Login</h1>

    <?php 
    if(isset($_SESSION['login'])) {
        echo $_SESSION['login'];
        unset($_SESSION['login']);
    }
    ?>

    <form action="" method="POST">
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Enter Username" value="<?php if(isset($username)) echo $username; ?>">
            <?php if(isset($err['username'])) echo "<div class='error-message'>{$err['username']}</div>"; ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter Password">
            <?php if(isset($err['password'])) echo "<div class='error-message'>{$err['password']}</div>"; ?>
        </div>

        <button type="submit" name="submit" class="btn-submit">Login</button>

    </form>
</div>

<?php include('partials-front/footer.php'); ?>

</body>
</html>
