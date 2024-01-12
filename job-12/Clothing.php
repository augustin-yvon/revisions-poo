<?php
class Clothing extends Product
{
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;
    private int $product_id;

    public function __construct
    (
        string $size,
        string $color,
        string $type,
        int $material_fee,
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

        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
        $this->product_id = $product_id;
    }

    public function findOneById(int $id): false|Clothing
    {
        $database = new Database();
        $pdo = $database->getPdo();
        $clothingQuery = "SELECT * FROM clothing WHERE id = :id";
        $stmt = $pdo->prepare($clothingQuery);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $clothingData = $stmt->fetch(PDO::FETCH_ASSOC);

        $productId = $clothingData['product_id'];

        $productQuery = "SELECT * FROM product WHERE id = :id";
        $stmt = $pdo->prepare($productQuery);
        $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $productData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($clothingData && $productData) {
            return new Clothing(
                $clothingData['size'],
                $clothingData['color'],
                $clothingData['type'],
                $clothingData['material_fee'],
                $clothingData['product_id'],
                $clothingData['product_id'],
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
        $query = "SELECT * FROM clothing";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $clothingsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clothings = [];

        foreach ($clothingsData as $clothingData) {
            $productId = $clothingData['product_id'];

            $productQuery = "SELECT * FROM product WHERE id = :id";
            $stmt = $pdo->prepare($productQuery);
            $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
            $stmt->execute();

            $productData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($productData) {
                $clothings[] = new Clothing(
                    $clothingData['size'],
                    $clothingData['color'],
                    $clothingData['type'],
                    $clothingData['material_fee'],
                    $clothingData['product_id'],
                    $clothingData['product_id'],
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

        return $clothings;
    }

    public function create(): false|Clothing
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

            $clothingQuery = "INSERT INTO clothing (size, color, type, material_fee, product_id) VALUES (:size, :color, :type, :material_fee, :product_id)";
            $clothingStmt = $pdo->prepare($clothingQuery);
            $clothingStmt->bindValue(':size', $this->size, PDO::PARAM_STR);
            $clothingStmt->bindValue(':color', $this->color, PDO::PARAM_STR);
            $clothingStmt->bindValue(':type', $this->type, PDO::PARAM_STR);
            $clothingStmt->bindValue(':material_fee', $this->material_fee, PDO::PARAM_INT);
            $clothingStmt->bindValue(':product_id', $productId, PDO::PARAM_INT);

            if ($clothingStmt->execute()) {
                $this->setId($productId);
                $this->setProduct_id($productId);
                return $this;
            }
        }

        return false;
    }

    public function update(): false|Clothing
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
            $updateClothingQuery = "
            UPDATE clothing SET 
                size = :size, 
                color = :color, 
                type = :type, 
                material_fee = :material_fee
            WHERE 
                product_id = :productId
        ";

            $updateClothingStmt = $pdo->prepare($updateClothingQuery);
            $updateClothingStmt->bindValue(':size', $this->size, PDO::PARAM_STR);
            $updateClothingStmt->bindValue(':color', $this->color, PDO::PARAM_STR);
            $updateClothingStmt->bindValue(':type', $this->type, PDO::PARAM_STR);
            $updateClothingStmt->bindValue(':material_fee', $this->material_fee, PDO::PARAM_INT);
            $updateClothingStmt->bindValue(':productId', $this->getId(), PDO::PARAM_INT);

            if ($updateClothingStmt->execute()) {
                return $this;
            }
        }

        return false;
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

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): Clothing
    {
        $this->size = $size;
        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): Clothing
    {
        $this->color = $color;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Clothing
    {
        $this->type = $type;
        return $this;
    }

    public function getMaterial_fee(): int
    {
        return $this->material_fee;
    }

    public function setMaterial_fee(int $material_fee): Clothing
    {
        $this->material_fee = $material_fee;
        return $this;
    }
}
