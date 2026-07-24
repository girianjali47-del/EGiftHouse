<?php
$mysqli = new mysqli("localhost", "username", "password", "e_gifthouse");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['query'])) {
    $query = $mysqli->real_escape_string($_GET['query']);

    $sql = "SELECT * FROM products WHERE MATCH(name, description) AGAINST('$query' IN NATURAL LANGUAGE MODE)";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Search Results for: <em>$query</em></h2>";
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<img src='images/" . $row['image'] . "' width='150'><br>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<p><strong>$" . $row['price'] . "</strong></p>";
            echo "</div><hr>";
        }
    } else {
        echo "No results found for: <em>$query</em>";
    }
}
?>
