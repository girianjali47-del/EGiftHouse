<?php

if (!defined('SITEURL')) {
    define('SITEURL', 'http://localhost/egifthouse/');
}

if (!defined('LOCALHOST')) {
    define('LOCALHOST', 'localhost');
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'db_egifthouse');
}


$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME) or die(mysqli_error($conn)); 
?>
