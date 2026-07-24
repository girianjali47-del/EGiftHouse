<?php

session_start();


include('config/constants.php'); 


if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    
    $item_id = mysqli_real_escape_string($conn, $item_id);

    
    $sql = "SELECT * FROM tbl_items WHERE id = '$item_id' AND active = 'Yes'";
    $res = mysqli_query($conn, $sql);

    
    if ($res) {
        $item = mysqli_fetch_assoc($res);

       
        if ($item) {
           
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }

            
            if (isset($_SESSION['cart'][$item_id])) {
                
                $_SESSION['cart'][$item_id]['quantity'] += 1;
            } else {
               
                $_SESSION['cart'][$item_id] = array(
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'quantity' => 1, 
                    'image' => $item['image_name']
                );
            }

            
            header("Location:cart.php?added_to_cart=1");  
            exit();
        } else {
            
            echo "Item not found.";
        }
    } else {
       
        echo "Error: " . mysqli_error($conn);
    }
} else {
    
    header("Location:index.php");
    exit();
}
?>
