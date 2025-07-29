<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\gateway\interfaces\IGatewayWithID;
use puzzlethings\src\object\Status;

class StatusGateway implements IGatewayWithID
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create new record in status table
    public function create(string $desc): Status|false
    {
        $sql = "INSERT INTO status (statusdesc) VALUES (:desc)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new Status($id, $desc);
        } catch (PDOException) {
            return false;
        }
    }

    // Count total number of records in status table
    public function count(mixed $options = []): int
    {
        $sql = "SELECT COUNT(*) FROM status";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting statuses: " . $e->getMessage());
            return -1;
        }
    }

    // Find all records in status table
    public function findAll(array $options = [], bool $verbose = false): array|null|PDOException
    {
        $sort = $options[SORT] ?? USER_ID;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;
        $page = $options[PAGE] ?? 0;
        $maxPerPage = $options[MAX_PER_PAGE] ?? 10;

        $offset = $page * $maxPerPage;

        $sql = "SELECT * FROM status ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $statuses = array();

            foreach ($result as $res) {
                $statuses[] = Status::of($res);
            }

            return $statuses;
        } catch (PDOException $e) {
            error_log("Database error while finding statuses: " . $e->getMessage());
            return $verbose ? $e : null;
        }
    }

    // Find specific record from status table based on statusid
    public function findById(int $id): ?Status
    {
        $sql = "SELECT * FROM status WHERE statusid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Status::of($result);
        } catch (PDOException $e) {
            error_log("Database error while finding status with id $id: " . $e->getMessage());
            return null;
        }
    }

    // Update description in status table based on statusid
    public function updateDesc(Status|int $status, string $desc): Status|false
    {
        $sql = "UPDATE status SET statusdesc = :desc WHERE statusid = :id";
        $id = $status instanceof Status ? $status->getId() : $status;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':desc', $desc);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM status WHERE statusid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return Status::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    // Delete specific record in status table based on statusid
    public function delete(Status|int $status): bool
    {
        $sql = "DELETE FROM status WHERE statusid = :id";
        $id = $status instanceof Status ? $status->getId() : $status;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Function to return status id by looking up a description
    public function findByName(string $desc): int
    {
        $sql = "SELECT statusid FROM status WHERE statusdesc = :desc";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while looking up status id: " . $e->getMessage());
            return -1;
        }
    }
}
