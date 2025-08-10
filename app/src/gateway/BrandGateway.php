<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\gateway\interfaces\IGatewayWithID;
use puzzlethings\src\object\Brand;

class BrandGateway implements IGatewayWithID
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
    public function count(mixed $options = []): int
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
    public function findAll(array $options = [], bool $verbose = false): array|null|PDOException
    {
        $sort = $options[SORT] ?? BRAND_ID;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;
        $page = $options[PAGE] ?? 0;
        $maxPerPage = $options[MAX_PER_PAGE] ?? 10;

        $offset = $page * $maxPerPage;

        $sql = "SELECT * FROM brand ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $brands = array();

            foreach ($result as $res) {
                $brands[] = Brand::of($res);
            }

            return $brands;
        } catch (PDOException $e) {
            error_log("Database error while finding brands: " . $e->getMessage());
            return $verbose ? $e : null;
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
    public function updateName(Brand|int $brand, string $name): Brand|false
    {
        $sql = "UPDATE brand SET brandname = :name WHERE brandid = :id";
        $id = $brand instanceof Brand ? $brand->getId() : $brand;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $success = $stmt->execute();

            if ($success) {
                return new Brand($id, $name);
            } else return false;
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
