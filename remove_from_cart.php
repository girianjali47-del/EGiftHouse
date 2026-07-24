<?php
// Start the session
session_start();


if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }

   
    header("Location: cart.php");
    exit();
} else {
    
    header("Location: cart.php");
    exit();
}
?>
