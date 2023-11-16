<?php

class Product {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function addProduct($naam, $beschrijving, $prijs, $aantal) {
        try {
            $stmt = $this->db->prepare("INSERT INTO product (naam, beschrijving, prijs, aantal) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$naam, $beschrijving, $prijs, $aantal]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getAllProducts() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM product");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteProduct($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM product WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateProduct($id, $naam, $beschrijving, $prijs, $aantal) {
        try {
            $stmt = $this->db->prepare("UPDATE product SET naam=?, beschrijving=?, prijs=?, aantal=? WHERE id=?");
            return $stmt->execute([$naam, $beschrijving, $prijs, $aantal, $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getProductById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM product WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
}
?>
