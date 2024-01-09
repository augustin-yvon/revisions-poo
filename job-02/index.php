<?php
require './Category.php';
require './Product.php';

$category = new Category(1, 'T-shirt', 'Catégorie de T-shirt', new DateTime(), new DateTime());

$categoryId = $category->getId();
$categoryName = $category->getName();
$categoryDescription = $category->getDescription();
$categoryCreatedAt = $category->getCreatedAt();
$categoryUpdatedAt = $category->getUpdatedAt();

var_dump($categoryId, $categoryName, $categoryDescription, $categoryCreatedAt, $categoryUpdatedAt);

$category->setId(2)
    ->setName('Another T-shirt')
    ->setDescription('Another category of T-shirt')
    ->setUpdatedAt(new DateTime());

var_dump($category);

$product = new Product(
    1,
    'T-shirt',
    ['https://picsum.photos/200/300'],
    12,
    'A beautiful T-shirt',
    10,
    new DateTime(),
    new DateTime(),
    $categoryId
);

var_dump($product->getCategory_id());
$product->setCategory_id(2);
var_dump($product->getCategory_id());