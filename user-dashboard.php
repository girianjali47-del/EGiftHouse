<?php
include('config/constants.php');
include('partials-front/menu.php');
//session_start();


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>


<div class="main">
    <div class="wrapper">
        <h1>Dashboard</h1>
        <br>

        <?php 
        if (isset($_SESSION['login'])) {
            echo $_SESSION['login'];
            unset($_SESSION['login']);
        }
        ?>
        <br>

        <div class="col-4 text-center">
            <?php
            $user_id = (int)$_SESSION['user_id'];

            
            $sql = "SELECT * FROM tbl_order WHERE uid = $user_id ORDER BY order_date DESC";
            $res = mysqli_query($conn, $sql);

            $count = ($res) ? mysqli_num_rows($res) : 0;
            ?>
            <h1><?php echo $count; ?></h1>
            Total Orders
        </div>

        <div class="clearfix"></div>

        <table class="tbl-full">
            <tr>
                <th>SN</th>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>

            <?php 
            if ($res && $count > 0) {
                $sn = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($row['item']); ?></td>
                        <td>Rs. <?php echo $row['price']; ?></td>
                        <td><?php echo $row['qty']; ?></td>
                        <td>Rs. <?php echo $row['total']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7">No orders found.</td>
                </tr>
                <?php
            }
            ?>
        </table>

    </div>
</div>


<?php include('partials-front/footer.php'); ?>
