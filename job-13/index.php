<?php
require_once './Category.php';
require_once './AbstractProduct.php';
require_once './Clothing.php';
require_once './Electronic.php';


$clothing = new Clothing(
    'M',
    'Noir',
    'Chinos',
    20,
    1,
    null,
    'Chinos',
    ['https://example.com/image.jpg'],
    25,
    'Chinos noir',
    50,
    new DateTime(),
    new DateTime()
);

$product = $clothing->findOneById(3);
var_dump($product);

$products = $clothing->findAll();
var_dump($products);

$create = $clothing->create();
var_dump($create);

print_r($clothing);

$clothing->setName('Chinos updated');
$clothing->setSize('XXL');

$update = $clothing->update();

var_dump($update);

$electronic = new Electronic(
    'Sony',
    15,
    2,
    null,
    'Headphones',
    ['https://example.com/headphones.jpg'],
    50,
    'High-quality headphones',
    20,
    new DateTime(),
    new DateTime()
);

$foundElectronic = $electronic->findOneById(4);
var_dump($foundElectronic);

$allElectronics = $electronic->findAll();
var_dump($allElectronics);

$createdElectronic = $electronic->create();
var_dump($createdElectronic);

print_r($electronic);

$electronic->setName('Updated Headphones');
$electronic->setBrand('Beats');
$electronic->setWaranty_fee(25);

$updatedElectronic = $electronic->update();
var_dump($updatedElectronic);