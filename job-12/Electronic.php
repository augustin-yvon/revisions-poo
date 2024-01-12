<?php

class Electronic extends Product
{
    private string $brand;
    private int $warranty_fee;
    private int $product_id;

    public function __construct
    (
        string $brand,
        int $warranty_fee,
        int $product_id,
        ?int $id = null,
        string $name = "Nom de produit",
        array|string $photos = ['https://picsum.photos/200/300'],
        int $price = 15,
        string $description = "Un super produit",
        int $quantity = 10,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        int $category_id = 1
    )
    {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);

        $this->brand = $brand;
        $this->warranty_fee = $warranty_fee;
        $this->product_id = $product_id;
    }

    public function findOneById(int $id): false|Electronic
    {
        $database = new Database();
        $pdo = $database->getPdo();
        $query = "SELECT * FROM electronic WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $electronicData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$electronicData) {
            return false;
        }

        $productId = $electronicData['product_id'];
        $productQuery = "SELECT * FROM product WHERE id = :productId";
        $productStmt = $pdo->prepare($productQuery);
        $productStmt->bindValue(':productId', $productId, PDO::PARAM_INT);
        $productStmt->execute();

        $productData = $productStmt->fetch(PDO::FETCH_ASSOC);

        if ($productData) {
            return new Electronic(
                $electronicData['brand'],
                $electronicData['warranty_fee'],
                $electronicData['product_id'],
                $electronicData['product_id'],
                $productData['name'],
                json_decode($productData['photos'], true),
                $productData['price'],
                $productData['description'],
                $productData['quantity'],
                new DateTime($productData['createdAt']),
                new DateTime($productData['updatedAt']),
                $productData['categoryId']
            );
        }

        return false;
    }


    public function findAll(): array
    {
        $database = new Database();
        $pdo = $database->getPdo();
        $query = "SELECT * FROM electronic";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $electronicsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $electronics = [];

        foreach ($electronicsData as $electronicData) {
            $productId = $electronicData['product_id'];

            $productQuery = "SELECT * FROM product WHERE id = :productId";
            $productStmt = $pdo->prepare($productQuery);
            $productStmt->bindValue(':productId', $productId, PDO::PARAM_INT);
            $productStmt->execute();

            $productData = $productStmt->fetch(PDO::FETCH_ASSOC);

            if ($productData) {
                $electronics[] = new Electronic(
                    $electronicData['brand'],
                    $electronicData['warranty_fee'],
                    $electronicData['product_id'],
                    $electronicData['product_id'],
                    $productData['name'],
                    json_decode($productData['photos'], true),
                    $productData['price'],
                    $productData['description'],
                    $productData['quantity'],
                    new DateTime($productData['createdAt']),
                    new DateTime($productData['updatedAt']),
                    $productData['categoryId']
                );
            }
        }

        return $electronics;
    }


    public function create(): false|Electronic
    {
        $database = new Database();
        $pdo = $database->getPdo();

        $productQuery = "INSERT INTO product (name, photos, price, description, quantity, createdAt, updatedAt, categoryId) VALUES (:name, :photos, :price, :description, :quantity, :createdAt, :updatedAt, :categoryId)";
        $productStmt = $pdo->prepare($productQuery);
        $productStmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $productStmt->bindValue(':photos', json_encode($this->getPhotos()), PDO::PARAM_STR);
        $productStmt->bindValue(':price', $this->getPrice(), PDO::PARAM_INT);
        $productStmt->bindValue(':description', $this->getDescription(), PDO::PARAM_STR);
        $productStmt->bindValue(':quantity', $this->getQuantity(), PDO::PARAM_INT);
        $productStmt->bindValue(':createdAt', $this->getCreatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $productStmt->bindValue(':updatedAt', $this->getUpdatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $productStmt->bindValue(':categoryId', $this->getCategory_id(), PDO::PARAM_INT);

        if ($productStmt->execute()) {
            $productId = $pdo->lastInsertId();

            $electronicQuery = "INSERT INTO electronic (brand, warranty_fee, product_id) VALUES (:brand, :warranty_fee, :product_id)";
            $electronicStmt = $pdo->prepare($electronicQuery);
            $electronicStmt->bindValue(':brand', $this->brand, PDO::PARAM_STR);
            $electronicStmt->bindValue(':warranty_fee', $this->warranty_fee, PDO::PARAM_INT);
            $electronicStmt->bindValue(':product_id', $productId, PDO::PARAM_INT);

            if ($electronicStmt->execute()) {
                $this->setId($productId);
                $this->setProduct_id($productId);
                return $this;
            }
        }

        return false;
    }


    public function update(): false|Electronic
    {
        $database = new Database();
        $pdo = $database->getPdo();

        $updateProductQuery = "
        UPDATE product SET 
            name = :name, 
            photos = :photos, 
            price = :price, 
            description = :description, 
            quantity = :quantity, 
            updatedAt = :updatedAt, 
            categoryId = :categoryId
        WHERE 
            id = :productId
    ";

        $updateProductStmt = $pdo->prepare($updateProductQuery);
        $updateProductStmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $updateProductStmt->bindValue(':photos', json_encode($this->getPhotos()), PDO::PARAM_STR);
        $updateProductStmt->bindValue(':price', $this->getPrice(), PDO::PARAM_INT);
        $updateProductStmt->bindValue(':description', $this->getDescription(), PDO::PARAM_STR);
        $updateProductStmt->bindValue(':quantity', $this->getQuantity(), PDO::PARAM_INT);
        $updateProductStmt->bindValue(':updatedAt', $this->getUpdatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $updateProductStmt->bindValue(':categoryId', $this->getCategory_id(), PDO::PARAM_INT);
        $updateProductStmt->bindValue(':productId', $this->getId(), PDO::PARAM_INT);

        if ($updateProductStmt->execute()) {
            $updateElectronicQuery = "
            UPDATE electronic SET 
                brand = :brand, 
                warranty_fee = :warranty_fee
            WHERE 
                product_id = :productId
        ";

            $updateElectronicStmt = $pdo->prepare($updateElectronicQuery);
            $updateElectronicStmt->bindValue(':brand', $this->brand, PDO::PARAM_STR);
            $updateElectronicStmt->bindValue(':warranty_fee', $this->warranty_fee, PDO::PARAM_INT);
            $updateElectronicStmt->bindValue(':productId', $this->getId(), PDO::PARAM_INT);

            if ($updateElectronicStmt->execute()) {
                return $this;
            }
        }

        return false;
    }

    public function getProduct_id(): int
    {
        return $this->product_id;
    }

    public function setProduct_id(int $product_id): Electronic
    {
        $this->product_id = $product_id;
        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): Electronic
    {
        $this->brand = $brand;
        return $this;
    }

    public function getWaranty_fee(): int
    {
        return $this->waranty_fee;
    }

    public function setWaranty_fee(int $waranty_fee): Electronic
    {
        $this->waranty_fee = $waranty_fee;
        return $this;
    }
}
