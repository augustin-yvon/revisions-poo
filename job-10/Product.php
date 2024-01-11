<?php
require_once './Database.php';
class Product
{

    private null|int $id;
    private string $name;
    private array|string $photos;
    private int $price;
    private string $description;
    private int $quantity;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private int $category_id;

    public function __construct(
        null|int $id = null,
        string $name = "Nom de produit",
        array|string $photos = ['https://picsum.photos/200/300'],
        int $price = 15,
        string $description = "Un super produit",
        int $quantity = 10,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        int $category_id = 1
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
        $this->category_id = $category_id;
    }

    public function getCategory(): ?Category
    {
        $database = new Database();
        $pdo = $database->getPdo();
        $query = "SELECT * FROM category WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $this->category_id, PDO::PARAM_INT);
        $stmt->execute();

        $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($categoryData) {
            return new Category(
                $categoryData['id'],
                $categoryData['name'],
                $categoryData['description'],
                new DateTime($categoryData['createdAt']),
                new DateTime($categoryData['updatedAt'])
            );
        }

        return null;
    }

    public function findOneById(int $id): false|Product
    {
        $database = new Database();
        $pdo = $database->getPdo();
        $query = "SELECT * FROM product WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $productData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($productData) {
            return new Product(
                $productData['id'],
                $productData['name'],
                $productData['photos'] = json_decode($productData['photos'], true),
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
        $query = "SELECT * FROM product";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];

        foreach ($productsData as $productData) {
            $products[] = new Product(
                $productData['id'],
                $productData['name'],
                $productData['photos'] = json_decode($productData['photos'], true),
                $productData['price'],
                $productData['description'],
                $productData['quantity'],
                new DateTime($productData['createdAt']),
                new DateTime($productData['updatedAt']),
                $productData['categoryId']
            );
        }

        return $products;
    }

    public function create(): false|Product
    {
        $database = new Database();
        $pdo = $database->getPdo();
        $query = "INSERT INTO product (name, photos, price, description, quantity, createdAt, updatedAt, categoryId) VALUES (:name, :photos, :price, :description, :quantity, :createdAt, :updatedAt, :categoryId)";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':photos', json_encode($this->photos), PDO::PARAM_STR);
        $stmt->bindValue(':price', $this->price, PDO::PARAM_INT);
        $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $this->quantity, PDO::PARAM_INT);
        $stmt->bindValue(':createdAt', $this->createdAt->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':updatedAt', $this->updatedAt->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':categoryId', $this->category_id, PDO::PARAM_INT);
        $stmt->execute();

        $lastInsertQuery = "SELECT id FROM product WHERE id = LAST_INSERT_ID()";
        $lastInsertStmt = $pdo->prepare($lastInsertQuery);
        $lastInsertStmt->execute();
        $id = $lastInsertStmt->fetch(PDO::FETCH_ASSOC);
        $this->setId($id['id']);

        return $this;
    }

    public function update(): false|Product
    {
        $database = new Database();
        $pdo = $database->getPdo();
        $updateQuery = "
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

        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':photos', json_encode($this->photos), PDO::PARAM_STR);
        $stmt->bindValue(':price', $this->price, PDO::PARAM_INT);
        $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $this->quantity, PDO::PARAM_INT);
        $stmt->bindValue(':updatedAt', $this->updatedAt->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':categoryId', $this->category_id, PDO::PARAM_INT);
        $stmt->bindValue(':productId', $this->id, PDO::PARAM_INT); // Ajoutez cette ligne pour spécifier l'ID de la ligne à mettre à jour
        $stmt->execute();

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getCategory_id(): int
    {
        return $this->category_id;
    }

    public function setId(int $id): Product
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    public function setPhotos(array $photos): Product
    {
        $this->photos = $photos;
        return $this;
    }

    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt): Product
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt): Product
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setCategory_id(int $category_id): Product
    {
        $this->category_id = $category_id;
        return $this;
    }
}