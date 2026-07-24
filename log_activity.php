<?php
// log_activity.php
require 'db.php';

/**
 * Log a user activity into the user_activity table
 *
 * @param int $user_id - ID of the user
 * @param int $product_id - ID of the product
 * @param string $type - Type of activity (view, add_to_cart, wishlist, purchase, search)
 * @param mysqli $conn - Database connection
 */
function logActivity($user_id, $product_id, $type, $conn) {
    $stmt = $conn->prepare("
        INSERT INTO user_activity (user_id, product_id, activity_type) 
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iis", $user_id, $product_id, $type);
    $stmt->execute();
    $stmt->close();
}

// Example usage:
// logActivity(1, 3, 'view', $conn); // Uncomment to test
?>
