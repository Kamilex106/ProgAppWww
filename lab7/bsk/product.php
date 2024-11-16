<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './utils/env.php';
include_once './utils/templates.php';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id > 0) {
    $product_sql = "SELECT products.*, categories.catName 
                    FROM products 
                    JOIN categories ON products.category = categories.id 
                    WHERE products.id = $product_id";
    $product_result = $conn->query($product_sql);
    if (!$product_result) {
        die("Query failed: " . $conn->error);
    }

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
    } else {
        echo "<p>Product not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid product ID.</p>";
    exit;
}

renderHeader(urlencode($product['prodName']));
?>

<div class="product-details">
    <img src='https://picsum.photos/400' alt='placeholder image'>
    <p><strong>Price:</strong> <?php echo htmlspecialchars($product['price']); ?> PLN</p>
    <p><strong>Description:</strong> This is a placeholder description for <?php echo htmlspecialchars($product['prodName']); ?>.</p>
</div>
        
<?php 
renderFooter(); 
$conn->close();
?>

