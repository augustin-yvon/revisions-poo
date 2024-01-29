<?php
require_once './Database.php';

abstract class AbstractProduct extends Database
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
        int $category_id = 1,
    ) {
        parent::__construct();
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
        $query = "SELECT * FROM category WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
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

    protected function fetchProductDataById(int $id, string $tableName): ?array
    {
        $query = "SELECT * FROM $tableName WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function fetchProductData(string $tableName): ?array
    {
        $query = "SELECT * FROM $tableName";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    abstract public function findOneById(int $id): false|AbstractProduct;

    abstract public function findAll(): array;

    abstract public function create(): false|AbstractProduct;

    abstract public function update(): false|AbstractProduct;

    public function getId(): null|int
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

    public function setId(int $id): AbstractProduct
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): AbstractProduct
    {
        $this->name = $name;
        return $this;
    }

    public function setPhotos(array $photos): AbstractProduct
    {
        $this->photos = $photos;
        return $this;
    }

    public function setPrice(int $price): AbstractProduct
    {
        $this->price = $price;
        return $this;
    }

    public function setDescription(string $description): AbstractProduct
    {
        $this->description = $description;
        return $this;
    }

    public function setQuantity(int $quantity): AbstractProduct
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt): AbstractProduct
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt): AbstractProduct
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setCategory_id(int $category_id): AbstractProduct
    {
        $this->category_id = $category_id;
        return $this;
    }
}