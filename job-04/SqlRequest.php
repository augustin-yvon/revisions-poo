<?php
require_once './Database.php';

session_start();

/**
 * Étend la classe Database et gère les requêtes SQL dans la base de données.
 */
class SqlRequest extends Database {

    public function getProductById(int $productId) {
        $query = "SELECT * FROM product WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
