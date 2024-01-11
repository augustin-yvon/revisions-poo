<?php

class Clothing extends Product
{
    private string $brand;
    private int $waranty_fee;
    private int $product_id;

    public function __construct
    (
        string       $brand,
        int          $waranty_fee,
        int          $product_id,
        // Les autres paramètres du constructeur parent
        ?int         $id = null,
        string       $name = "Nom de produit",
        array|string $photos = ['https://picsum.photos/200/300'],
        int          $price = 15,
        string       $description = "Un super produit",
        int          $quantity = 10,
        ?DateTime    $createdAt = null,
        ?DateTime    $updatedAt = null,
        int          $category_id = 1
    )
    {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);

        $this->brand = $brand;
        $this->waranty_fee = $waranty_fee;
        $this->product_id = $product_id;
    }

    public function getProduct_id(): int
    {
        return $this->product_id;
    }

    public function setProduct_id(int $product_id): Clothing
    {
        $this->product_id = $product_id;
        return $this;
    }
}
