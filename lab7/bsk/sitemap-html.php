<?php

include_once './utils/env.php';
include_once './utils/templates.php';

header("Content-Type: text/html; charset=UTF-8");


$domain = "https://" . $_SERVER['HTTP_HOST'];
$pages = [
    ['link' => "$domain/", 'title' => "Moderato - Home"],
    ['link' => "$domain/about.php", 'title' => "Moderato - About us"],
];

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categories_sql = "SELECT id, catName FROM categories";
$categories_result = $conn->query($categories_sql);
if ($categories_result) {
    while ($row = $categories_result->fetch_assoc()) {
        $pages[] = [
            'link' => "$domain/category.php?category=".$row['id'],
            'title' => "Category - ". htmlspecialchars($row['catName'])
        ];
    }
} else {
    die("Query failed: " . $conn->error);
}

renderHeader("Moderato - Sitemap");

foreach ($pages as $page) {
    echo "<li><a href='" . $page['link'] . "'>" . $page['title'] . "</a></li>";
}

renderFooter(); 

$conn->close();
?>
