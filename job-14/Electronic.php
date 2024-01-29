<?php
require_once './Database.php';
require_once './AbstractProduct.php';
require_once './StockableInterface.php';

class Electronic extends AbstractProduct
{
    private string $brand;
    private int $warranty_fee;
    private int $product_id;

    public function __construct(
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
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);

        $this->brand = $brand;
        $this->warranty_fee = $warranty_fee;
        $this->product_id = $product_id;
    }

    public function addStocks(int $stock): self {
        $quantity = $this->getQuantity();
        $this->setQuantity($quantity + $stock);
        return $this;
    }

    public function removeStocks(int $stock): self {
        $quantity = $this->getQuantity();
        $this->setQuantity(max(0, $quantity - $stock)); // Pas de stock négatif
        return $this;
    }

    public function findOneById(int $id): false|Electronic
    {
        $electronicData = $this->fetchProductDataById($id, 'electronic');

        if (!$electronicData) {
            return false;
        }

        $productId = $electronicData['product_id'];
        $productQuery = "SELECT * FROM product WHERE id = :productId";
        $productStmt = $this->pdo->prepare($productQuery);
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
        $electronicsData = $this->fetchProductData('electronic');

        $electronics = [];

        foreach ($electronicsData as $electronicData) {
            $productId = $electronicData['product_id'];

            $productQuery = "SELECT * FROM product WHERE id = :productId";
            $productStmt = $this->pdo->prepare($productQuery);
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
        $productQuery = "INSERT INTO product (name, photos, price, description, quantity, createdAt, updatedAt, categoryId) VALUES (:name, :photos, :price, :description, :quantity, :createdAt, :updatedAt, :categoryId)";
        $productStmt = $this->pdo->prepare($productQuery);
        $productStmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $productStmt->bindValue(':photos', json_encode($this->getPhotos()), PDO::PARAM_STR);
        $productStmt->bindValue(':price', $this->getPrice(), PDO::PARAM_INT);
        $productStmt->bindValue(':description', $this->getDescription(), PDO::PARAM_STR);
        $productStmt->bindValue(':quantity', $this->getQuantity(), PDO::PARAM_INT);
        $productStmt->bindValue(':createdAt', $this->getCreatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $productStmt->bindValue(':updatedAt', $this->getUpdatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $productStmt->bindValue(':categoryId', $this->getCategory_id(), PDO::PARAM_INT);

        if ($productStmt->execute()) {
            $productId = $this->pdo->lastInsertId();

            $electronicQuery = "INSERT INTO electronic (brand, warranty_fee, product_id) VALUES (:brand, :warranty_fee, :product_id)";
            $electronicStmt = $this->pdo->prepare($electronicQuery);
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

        $updateProductStmt = $this->pdo->prepare($updateProductQuery);
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

            $updateElectronicStmt = $this->pdo->prepare($updateElectronicQuery);
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

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getWarranty_fee(): int
    {
        return $this->warranty_fee;
    }

    public function setProduct_id(int $product_id): Electronic
    {
        $this->product_id = $product_id;
        return $this;
    }

    public function setBrand(string $brand): Electronic
    {
        $this->brand = $brand;
        return $this;
    }

    public function setWarranty_fee(int $warranty_fee): Electronic
    {
        $this->warranty_fee = $warranty_fee;
        return $this;
    }
}
