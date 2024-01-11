<?php
require_once './Category.php';
require_once './Product.php';

$product = new Product();
$p = $product->create();
var_dump($p);
$p->setName('Super produit');
var_dump($p);
$p->update();
var_dump($p);