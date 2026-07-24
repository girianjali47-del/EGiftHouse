<?php
session_start();
include('config/constants.php');  


session_destroy();


header('Location: ' . SITEURL . 'index.php');
exit();
?>
