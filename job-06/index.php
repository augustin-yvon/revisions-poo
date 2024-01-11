<?php
require_once './Category.php';
require_once './Product.php';

$category = new Category();
$products = $category->getProducts();
var_dump($products);