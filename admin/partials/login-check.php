<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user'])) {
    
    $_SESSION['no-login-msg'] = "<div class='text-center'>Please login to access admin panel</div>";

    
    header('Location: ' . SITEURL . 'admin/login.php');
    exit();
}
?>
