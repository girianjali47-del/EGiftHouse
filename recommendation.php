<?php

if (session_status() === PHP_SESSION_NONE) {
    //session_start();
}

include('config/constants.php');
include('partials-front/menu.php');


if (!isset($_SESSION['user_id'])) {
    echo "<div class='error text-center'>Please log in to see recommendations.</div>";
    include('partials-front/footer.php');
    exit();
}

$user_id = $_SESSION['user_id'];


function tokenize($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9 ]/', ' ', $text); 
    $words = array_filter(explode(' ', $text)); 
    return $words;
}


$sql_user_orders = "SELECT item FROM tbl_order WHERE uid=$user_id AND status='Delivered'";
$res_orders = mysqli_query($conn, $sql_user_orders);

$user_items = [];
if ($res_orders && mysqli_num_rows($res_orders) > 0) {
    while ($row = mysqli_fetch_assoc($res_orders)) {
        $user_items[] = $row['item'];
    }
} else {
    echo "<div class='error text-center'>You have no previous orders for recommendations.</div>";
    include('partials-front/footer.php');
    exit();
}



$user_features = [
    'categories' => [],
    'keywords' => [],
];

foreach ($user_items as $title) {
    $title_esc = mysqli_real_escape_string($conn, $title);
    $sql_item = "SELECT category_id, description FROM tbl_items WHERE title='$title_esc' AND active='Yes' LIMIT 1";
    $res_item = mysqli_query($conn, $sql_item);
    if ($res_item && mysqli_num_rows($res_item) == 1) {
        $item = mysqli_fetch_assoc($res_item);
        $user_features['categories'][] = $item['category_id'];
        $tokens = tokenize($item['description']);
        $user_features['keywords'] = array_merge($user_features['keywords'], $tokens);
    }
}


$user_features['categories'] = array_unique($user_features['categories']);
$user_features['keywords'] = array_unique($user_features['keywords']);


$user_items_esc = array_map(function($i) use ($conn) {
    return "'" . mysqli_real_escape_string($conn, $i) . "'";
}, $user_items);

$user_items_str = implode(',', $user_items_esc);

$sql_all_items = "SELECT * FROM tbl_items WHERE active='Yes'";

if (!empty($user_items)) {
    $sql_all_items .= " AND title NOT IN ($user_items_str)";
}

$res_all = mysqli_query($conn, $sql_all_items);

$recommendations = [];

if ($res_all && mysqli_num_rows($res_all) > 0) {
    while ($item = mysqli_fetch_assoc($res_all)) {
       
        $score = 0;

      
        if (in_array($item['category_id'], $user_features['categories'])) {
            $score += 3;
        }

        
        $item_tokens = tokenize($item['description']);
        $common_keywords = array_intersect($user_features['keywords'], $item_tokens);
        $score += count($common_keywords);

        if ($score > 0) {
            $recommendations[] = [
                'item' => $item,
                'score' => $score
            ];
        }
    }
}


usort($recommendations, function($a, $b) {
    return $b['score'] <=> $a['score'];
});


$recommendations = array_slice($recommendations, 0, 4);
?>

<style>
.recommend-section {
    padding: 50px 0;
    background: #f9f9f9;
}
.recommend-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 30px;
    margin-top: 30px;
}
.recommend-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    transition: transform 0.3s ease;
}
.recommend-card:hover {
    transform: translateY(-5px);
}
.recommend-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}
.item-details .price {
    color: #ff6b6b;
    font-weight: bold;
    margin-top: 10px;
}
.btn-primary {
    background: #ff6b6b;
    padding: 10px 20px;
    border-radius: 30px;
    color: white;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    margin: 5px;
}
</style>

<section class="recommend-section">
    <div class="container">
        <h2 class="text-center section-title">🎯 Recommended for You (Content-Based)</h2>
        <div class="recommend-grid">

            <?php if (count($recommendations) > 0): ?>
                <?php foreach ($recommendations as $rec): 
                    $item = $rec['item']; ?>
                    <div class="recommend-card">
                        <?php if ($item['image_name'] != "") { ?>
                            <img src="<?php echo SITEURL; ?>images/item/<?php echo $item['image_name']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <?php } else { ?>
                            <div class="no-image">Image not available</div>
                        <?php } ?>
                        <div class="item-details">
                            <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                            <p class="price">Rs. <?php echo $item['price']; ?></p>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                            <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $item['id']; ?>" class="btn-primary">Order Now</a>
                            <a href="<?php echo SITEURL; ?>add_to_cart.php?item_id=<?php echo $item['id']; ?>" class="btn-primary">Add to Cart 🛒</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="error text-center">No recommendations found based on your previous orders.</div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>