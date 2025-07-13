<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Category;


class CategoryGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create new category in categories table
    public function create(string $desc): Category|false
    {
        $sql = "INSERT INTO categories (categorydesc) VALUES (:desc)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new Category($id, $desc);
        } catch (PDOException) {
            return false;
        }
    }

    // Create a new record that ties category and puzzle together in puzcat table
    public function createPuzzle(string $puzzleid, $categoryid): bool
    {
        $sql = "INSERT INTO puzcat (puzzleid,categoryid) VALUES (:puzzleid,:categoryid)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':puzzleid', $puzzleid);
            $stmt->bindParam(':categoryid', $categoryid);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Delete record from puzcat table based on puzzle id and category id
    public function deletePuzzle(string $puzzleid, $categoryid): bool
    {
        $sql = "DELETE FROM puzcat WHERE puzzleid = :puzzleid AND categoryid = :categoryid";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':puzzleid', $puzzleid);
            $stmt->bindParam(':categoryid', $categoryid);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Count total number of records in categories table
    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM categories";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting categories: " . $e->getMessage());
            return -1;
        }
    }

    // Return all records from the categories table
    public function findAll(): array
    {
        $sql = "SELECT * FROM categories ORDER BY categorydesc";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categories = array();

            foreach ($result as $res) {
                $categories[] = Category::of($res);
            }

            return $categories;
        } catch (PDOException) {
            return [];
        }
    }

    // Return record from categories table based on category id
    public function findById(int $id): ?Category
    {
        $sql = "SELECT * FROM categories WHERE categoryid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Category::of($result);
        } catch (PDOException) {
            return null;
        }
    }

    // Update category description in categories table based on category id
    public function updateDesc(Category|int $category, string $name): bool
    {
        $sql = "UPDATE categories SET categorydesc = :name WHERE categoryid = :id";
        $id = $category instanceof Category ? $category->getId() : $category;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Delete categories record based on category id
    public function delete(Category|int $category): bool
    {
        $sql = "DELETE FROM categories WHERE categoryid = :id";
        $id = $category instanceof Category ? $category->getId() : $category;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}
