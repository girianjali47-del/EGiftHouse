<?php
include('partials-front/menu.php');

if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) {
    header("location: user-dashboard.php");
    exit();
}

$full_name = $username = $phone = $email = $address = '';
$err = [];

if (isset($_POST['submit'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($full_name) || !preg_match("/^[A-Za-z\s]+$/", $full_name)) $err['full_name'] = "Enter valid Full Name";
    if (empty($username) || !preg_match("/^[a-zA-Z0-9]{4,29}$/", $username)) $err['username'] = "Username must be alphanumeric (4-29 characters)";
    if (empty($phone) || !preg_match("/^9[0-9]{9}$/", $phone)) $err['phone'] = "Enter valid Phone (starts with 9)";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $err['email'] = "Enter valid Email";
    if (empty($address)) $err['address'] = "Enter Address";
    if (empty($password)) $err['password'] = "Enter Password";

    if (empty($err)) {
        $checks = [
            ['sql' => "SELECT * FROM tbl_users WHERE username=?", 'param' => $username, 'key' => 'username', 'msg' => 'Username already exists'],
            ['sql' => "SELECT * FROM tbl_users WHERE phone=?", 'param' => $phone, 'key' => 'phone', 'msg' => 'Phone already exists'],
            ['sql' => "SELECT * FROM tbl_users WHERE email=?", 'param' => $email, 'key' => 'email', 'msg' => 'Email already exists'],
        ];

        foreach ($checks as $check) {
            $stmt = mysqli_prepare($conn, $check['sql']);
            mysqli_stmt_bind_param($stmt, "s", $check['param']);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($res) > 0) {
                $err[$check['key']] = $check['msg'];
            }
        }
    }

    if (empty($err)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tbl_users (full_name, username, phone, email, address, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $full_name, $username, $phone, $email, $address, $password_hashed);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['add'] = "Registration Successful";
            header("location:" . SITEURL . 'login.php');
            exit;
        } else {
            $_SESSION['add'] = "Failed to register";
            header("location:" . SITEURL . 'register.php');
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
    <title>Register</title>

    <style>
        body {
            background: #646e78ff;
            font-family: 'Poppins', sans-serif;
        }

        .register-container {
            width: 100%;
            max-width: 400px;
            margin: 40px auto;
            padding: 30px;
            background: #99c4cdff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 15px;
            text-align: center;
        }

        .register-container h1 {
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
            width: 100%;
            padding: 5px 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 16px;
            
        }

        .form-group input:focus {
            border-color: #007bff;
        }

        .btn-submit {
            width: 80%;
            background: #007bff;
            color: #fff;
            padding: 5px;
            border: none;
            border-radius: 15px;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: #0056b3;
        }

        .error-message {
            color: #e74c3c;
            margin-top: 5px;
            font-size: 14px;
        }

        .success {
            color: green;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="register-container">
    <h1>User Registration</h1>

    <?php 
    if (isset($_SESSION['add'])) {
        echo "<div class='success'>{$_SESSION['add']}</div>";
        unset($_SESSION['add']);
    }
    ?>

    <form action="" method="POST">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>">
            <?php if(isset($err['full_name'])) echo "<div class='error-message'>{$err['full_name']}</div>"; ?>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <?php if(isset($err['username'])) echo "<div class='error-message'>{$err['username']}</div>"; ?>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            <?php if(isset($err['phone'])) echo "<div class='error-message'>{$err['phone']}</div>"; ?>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if(isset($err['email'])) echo "<div class='error-message'>{$err['email']}</div>"; ?>
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">
            <?php if(isset($err['address'])) echo "<div class='error-message'>{$err['address']}</div>"; ?>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
            <?php if(isset($err['password'])) echo "<div class='error-message'>{$err['password']}</div>"; ?>
        </div>

        <button type="submit" name="submit" class="btn-submit">Register</button>
    </form>
</div>

<?php include('partials-front/footer.php'); ?>

</body>
</html>
