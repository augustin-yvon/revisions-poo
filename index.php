<?php
require 'Product.php';

$product = new Product(
    1,
    'T-shirt',
    ['https://picsum.photos/200/300'],
    10.99,
    'A beautiful T-shirt',
    10,
    new DateTime(),
    new DateTime()
);

var_dump($product);
