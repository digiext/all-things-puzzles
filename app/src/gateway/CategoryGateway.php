<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\gateway\interfaces\IGatewayWithID;
use puzzlethings\src\object\Category;


class CategoryGateway implements IGatewayWithID
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
    public function count(mixed $options = []): int
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
    public function findAll(mixed $options = [], bool $verbose = false): array|null|PDOException
    {
        $sort = $options[SORT] ?? CATEGORY_DESCRIPTION;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;
        $page = $options[PAGE] ?? 0;
        $maxPerPage = $options[MAX_PER_PAGE] ?? 10;

        $offset = $page * $maxPerPage;

        $sql = "SELECT * FROM categories ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categories = array();

            foreach ($result as $res) {
                $categories[] = Category::of($res);
            }

            return $categories;
        } catch (PDOException $e) {
            return $verbose ? $e : null;
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
    public function updateDesc(Category|int $category, string $name): Category|false
    {
        $sql = "UPDATE categories SET categorydesc = :name WHERE categoryid = :id";
        $id = $category instanceof Category ? $category->getId() : $category;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $success = $stmt->execute();

            if ($success) return new Category($id, $name);
            else return false;
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
