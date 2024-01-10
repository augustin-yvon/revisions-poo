<?php
require './SqlRequest.php';
require './Product.php';

$product = new Product();
$sqlRequest = new SqlRequest();
$category = $product->getCategory($sqlRequest);
var_dump($category);
var_dump($product);
