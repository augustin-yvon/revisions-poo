<?php
require_once './Category.php';
require_once './Product.php';

$product = new Product();
$productId5 = $product->findOneById(5);
var_dump($productId5);

