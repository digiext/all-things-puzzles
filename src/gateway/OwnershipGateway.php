<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Ownership;

class OwnershipGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create(string $desc): Ownership|false
    {
        $sql = "INSERT INTO ownership (ownershipdesc) VALUES (:desc)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new Ownership($id, $desc);
        } catch (PDOException) {
            return false;
        }
    }

    public function count(): int {
        $sql = "SELECT COUNT(*) FROM ownership";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting ownerships: " . $e->getMessage());
            return -1;
        }
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM ownership";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $ownerships = array();

            foreach ($result as $res) {
                $ownerships[] = Ownership::of($res);
            }

            return $ownerships;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findById(int $id): ?Ownership
    {
        $sql = "SELECT * FROM ownership WHERE ownershipid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Ownership::of($result);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateDesc(Ownership|int $ownership, string $desc): Ownership|false
    {
        $sql = "UPDATE ownership SET ownershipdesc = :desc WHERE ownershipid = :id";
        $id = $ownership instanceof Ownership ? $ownership->getId() : $ownership;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':desc', $desc);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM ownership WHERE ownershipid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return Ownership::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function delete(Ownership|int $ownership): bool
    {
        $sql = "DELETE FROM ownership WHERE ownershipid = :id";
        $id = $ownership instanceof Ownership ? $ownership->getId() : $ownership;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}
