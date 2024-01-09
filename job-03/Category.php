<?php

class Category
{
    private int $id;
    private string $name;
    private string $description;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        int $id,
        string $name,
        string $description,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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
