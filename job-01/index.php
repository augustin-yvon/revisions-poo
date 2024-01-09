<?php
require './Product.php';

$product = new Product(
    1,
    'T-shirt',
    ['https://picsum.photos/200/300'],
    12,
    'A beautiful T-shirt',
    10,
    new DateTime(),
    new DateTime()
);

var_dump($product);

$id = $product->getId();
$name = $product->getName();
$photos = $product->getPhotos();
$price = $product->getPrice();
$description = $product->getDescription();
$quantity = $product->getQuantity();
$createdAt = $product->getCreatedAt();
$updatedAt = $product->getUpdatedAt();

var_dump($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt);

$product->setId(2)
    ->setName('Another T-shirt ')
    ->setPhotos(['https://picsum.photos/200/400', 'https://picsum.photos/200/200'])
    ->setPrice(22)
    ->setDescription('Another beautiful T-shirt')
    ->setQuantity(20)
    ->setUpdatedAt(new DateTime());

var_dump($product);