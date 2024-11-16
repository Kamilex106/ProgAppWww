<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'env.php';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_term = "";
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];

    // Use the correct column name in the query
    $sql = "SELECT * FROM products WHERE prodName LIKE ?";
    $stmt = $conn->prepare($sql);
    
    // Prepare the search value
    $search_value = "%" . $search_term . "%";
    $stmt->bind_param("s", $search_value); 

    $stmt->execute(); 
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            // Create links to product pages
            echo "<li><a href='product.php?id=" . htmlspecialchars($row["id"]) . "'>" . 
                 htmlspecialchars($row["prodName"]) . " - " . 
                 htmlspecialchars($row["price"]) . " PLN</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No products found.</p>";
    }
    $stmt->close();
}

$conn->close();
?>
