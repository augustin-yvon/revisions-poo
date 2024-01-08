<?php
require './Category.php';
require './Product.php';

$category = new Category(1, 'T-shirt', 'Catégorie de T-shirt', new DateTime(), new DateTime());
$categoryId = $category->getId();
$product = new Product(1, 'T-shirt blanc', ['https://picsum.photos/200/300'], 10.99, 'T-shirt blanc en coton', 10, new DateTime(), new DateTime(), $categoryId);
var_dump($product);
var_dump($category);
$product2 = new Product();
var_dump($product2);