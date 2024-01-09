<?php
require './SqlRequest.php';
require './Product.php';

$sqlRequest = new SqlRequest();
$productData = $sqlRequest->getProductById(4);

$createdAt = $productData['createdAt'] ?? null;
$updatedAt = $productData['updatedAt'] ?? null;

$product = new Product();
$product->setId($productData['id'] ?? 0)
    ->setName($productData['name'] ?? "Nom de produit")
    ->setPhotos(json_decode($productData['photos'] ?? '["https://picsum.photos/200/300"]', true))
    ->setPrice($productData['price'] ?? 15)
    ->setDescription($productData['description'] ?? "Un super produit")
    ->setQuantity($productData['quantity'] ?? 10)
    ->setCreatedAt($createdAt ? new DateTime($createdAt) : new DateTime())
    ->setUpdatedAt($updatedAt ? new DateTime($updatedAt) : new DateTime())
    ->setCategory_id($productData['categoryId'] ?? 1);

var_dump($product);
