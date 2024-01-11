<?php
require_once './Database.php';
class Category
{
    private int $id;
    private string $name;
    private string $description;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        int $id = 1,
        string $name = "Nom de catégorie",
        string $description = "Une super catégorie",
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
    }

    public function getProducts(): ?array
    {
        $database = new Database();
        $pdo = $database->getPdo();

        $query = "SELECT * FROM product WHERE categoryId = :categoryId";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':categoryId', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];
        if ($productsData) {
            foreach ($productsData as $product) {
                $products[] = new Product(
                    $product['id'],
                    $product['name'],
                    $product['photos'],
                    $product['price'],
                    $product['description'],
                    $product['quantity'],
                    new DateTime($product['createdAt']),
                    new DateTime($product['updatedAt']),
                    $product['categoryId']
                );
            }
            return $products;
        }

        return $products;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
       return $this->name;
    }

    public function getDescription(): string
    {
       return $this->description;
    }

    public function getCreatedAt(): DateTime
    {
       return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
       return $this->updatedAt;
    }

    public function setId(int $id): Category
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription(string $description): Category
    {
        $this->description = $description;
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt): Category
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt): Category
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}