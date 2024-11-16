<?php

include_once './utils/env.php';

header("Content-Type: application/xml; charset=UTF-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$domain = "https://" . $_SERVER['HTTP_HOST'];
$pages = [
    ['loc' => "$domain/", 'priority' => '1.0'],
    ['loc' => "$domain/about.php", 'priority' => '0.8'],
];

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$products_sql = "SELECT id FROM products";
$products_result = $conn->query($products_sql);
if ($products_result) {
    while ($row = $products_result->fetch_assoc()) {
        $id = $row['id'];
        $pages[] = [
            'loc' => "$domain/product.php?id=$id",
            'priority' => '0.7'
        ];
    }
} else {
    die("Query failed: " . $conn->error);
}

$categories_sql = "SELECT catName FROM categories";
$categories_result = $conn->query($categories_sql);
if ($categories_result) {
    while ($row = $categories_result->fetch_assoc()) {
        $category = urlencode($row['catName']);
        $pages[] = [
            'loc' => "$domain/category.php?category=$category",
            'priority' => '0.6'
        ];
    }
} else {
    die("Query failed: " . $conn->error);
}

foreach ($pages as $page) {
    echo '<url>';
    echo '<loc>' . htmlspecialchars($page['loc']) . '</loc>';
    echo '<priority>' . $page['priority'] . '</priority>';
    echo '</url>';
}

echo '</urlset>';

$conn->close();
?>
