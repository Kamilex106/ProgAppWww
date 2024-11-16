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

$category_name = isset($_GET['category']) ? $_GET['category'] : '';

if ($category_name) {
    $category_name = $conn->real_escape_string($category_name);

    $category_sql = "SELECT id FROM categories WHERE catName = '$category_name'";
    $category_result = $conn->query($category_sql);
    if (!$category_result) {
        die("Query failed: " . $conn->error);
    }

    if ($category_result->num_rows > 0) {
        $category_row = $category_result->fetch_assoc();
        $category_id = $category_row['id'];

        $products_sql = "SELECT * FROM products WHERE category = '$category_id'";
        $products_result = $conn->query($products_sql);
        if (!$products_result) {
            die("Query failed: " . $conn->error);
        }        
    } else {
        echo "<p>Invalid category.</p>";
        exit;
    }
} else {
    echo "<p>No category specified.</p>";
    exit;
}

renderHeader(htmlspecialchars($category_name));
?>

<h2>Products in "<?php echo htmlspecialchars($category_name); ?>"</h2>
<div class='product-list'>
    <?php
    if ($products_result->num_rows > 0) {
        while ($product_row = $products_result->fetch_assoc()) {
            $product_id = $product_row["id"];
            $product_name = htmlspecialchars($product_row["prodName"]);
            $product_price = htmlspecialchars($product_row["price"]);

            echo "<a href='product.php?id=$product_id' class='product-item'>
                    <img src='https://picsum.photos/200' alt='placeholder image'>
                    <h3>$product_name</h3>
                    <p>$product_price PLN</p>
                    </a>";
        }
    } else {
        echo "<p>No products in this category.</p>";
    }
    ?>
</div>

<?php 
renderFooter();
$conn->close(); 
?>
