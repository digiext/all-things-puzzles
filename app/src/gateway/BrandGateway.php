<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Brand;

class BrandGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create new brand in brand table
    public function create(string $name): Brand|false
    {
        $sql = "INSERT INTO brand (brandname) VALUES (:name)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new Brand($id, $name);
        } catch (PDOException) {
            return false;
        }
    }

    // Count total records in brand table
    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM brand";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting brands: " . $e->getMessage());
            return -1;
        }
    }

    // Find all records in brand table
    public function findAll(): array
    {
        $sql = "SELECT * FROM brand";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $brands = array();

            foreach ($result as $res) {
                $brands[] = Brand::of($res);
            }

            return $brands;
        } catch (PDOException) {
            return [];
        }
    }

    // Find record by brand id
    public function findById(int $id): ?Brand
    {
        $sql = "SELECT * FROM brand WHERE brandid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Brand::of($result);
        } catch (PDOException) {
            return null;
        }
    }

    // Update brand name in brand table based on brand id
    public function updateName(Brand|int $brand, string $name): bool
    {
        $sql = "UPDATE brand SET brandname = :name WHERE brandid = :id";
        $id = $brand instanceof Brand ? $brand->getId() : $brand;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Delete brand in brand table based on brand id
    public function delete(Brand|int $brand): bool
    {
        $sql = "DELETE FROM brand WHERE brandid = :id";
        $id = $brand instanceof Brand ? $brand->getId() : $brand;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}
