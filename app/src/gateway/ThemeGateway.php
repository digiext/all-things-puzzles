<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\gateway\interfaces\IGatewayWithID;
use puzzlethings\src\object\Theme;

class ThemeGateway implements IGatewayWithID
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create new record in theme table
    public function create(string $name): Theme|false
    {
        $sql = "INSERT INTO theme (themedesc) VALUES (:name)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new Theme($id, $name);
        } catch (PDOException) {
            return false;
        }
    }

    // Count total number of records in theme table
    public function count(mixed $options = []): int
    {
        $sql = "SELECT COUNT(*) FROM theme";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting themees: " . $e->getMessage());
            return -1;
        }
    }

    // Find all records in theme table
    public function findAll(array $options = [], bool $verbose = false): array|null|PDOException
    {
        $sort = $options[SORT] ?? THEME_ID;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;
        $page = $options[PAGE] ?? 0;
        $maxPerPage = $options[MAX_PER_PAGE] ?? 10;

        $offset = $page * $maxPerPage;

        $sql = "SELECT * FROM theme ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $themees = array();

            foreach ($result as $res) {
                $themees[] = Theme::of($res);
            }

            return $themees;
        } catch (PDOException $e) {
            error_log("Database error while finding themees: " . $e->getMessage());
            return $verbose ? $e : null;
        }
    }

    // Find specific record from theme table based on themeid
    public function findById(int $id): ?Theme
    {
        $sql = "SELECT * FROM theme WHERE themeid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Theme::of($result);
        } catch (PDOException $e) {
            error_log("Database error while finding theme with id $id: " . $e->getMessage());
            return null;
        }
    }

    // Update description in theme table based on themeid
    public function updateName(Theme|int $theme, string $name): Theme|false
    {
        $sql = "UPDATE theme SET themedesc = :name WHERE themeid = :id";
        $id = $theme instanceof Theme ? $theme->getId() : $theme;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM theme WHERE themeid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return Theme::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    // Delete specific record in theme table based on themeid
    public function delete(Theme|int $theme): bool
    {
        $sql = "DELETE FROM theme WHERE themeid = :id";
        $id = $theme instanceof Theme ? $theme->getId() : $theme;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Function to return theme id by looking up a description
    public function findByName(string $name): int
    {
        $sql = "SELECT themeid FROM theme WHERE themedesc = :name";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while looking up theme id: " . $e->getMessage());
            return -1;
        }
    }
}
