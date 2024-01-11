<?php
require_once './Category.php';
require_once './Product.php';

$product = new Product();
$products = $product->findAll();
var_dump($products);
foreach ($products as $product) {
    echo $product->getName() . ' ' . $product->getCategory()->getName() . '<br>';
}

