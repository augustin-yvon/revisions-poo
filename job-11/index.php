<?php
require_once './Category.php';
require_once './Product.php';
require_once './Clothing.php';

// Créer une instance de la classe Clothing
$clothing = new Clothing(
    'XL',                      // Taille
    'Bleu',                    // Couleur
    'Chemise',                 // Type
    20,                        // Frais de matériau
    16,                       // ID du produit
    null,                      // ID (facultatif, laisser null pour générer automatiquement)
    'Chemise cool',            // Nom
    ['https://example.com/image.jpg'],  // Photos
    25,                        // Prix
    'Une chemise très cool',   // Description
    50,                        // Quantité
    new DateTime('2024-01-11'), // Date de création
    new DateTime('2024-01-12')  // Date de mise à jour
);

// Accéder aux propriétés de la classe Clothing
echo "Nom: " . $clothing->getName() . "<br>";
echo "Taille: " . $clothing->getSize() . "<br>";
echo "Couleur: " . $clothing->getColor() . "<br>";
echo "Type: " . $clothing->getType() . "<br>";
echo "Frais de matériau: " . $clothing->getMaterial_fee() . "<br>";
echo "ID du produit: " . $clothing->getProduct_id() . "<br>";
var_dump($clothing);