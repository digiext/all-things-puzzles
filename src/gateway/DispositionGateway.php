<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Disposition;

class DispositionGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create(string $desc): Disposition|false
    {
        $sql = "INSERT INTO disposition (dispositiondesc) VALUES (:desc)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new Disposition($id, $desc);
        } catch (PDOException) {
            return false;
        }
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM disposition";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting dispositions: " . $e->getMessage());
            return -1;
        }
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM disposition";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $dispositions = array();

            foreach ($result as $res) {
                $dispositions[] = Disposition::of($res);
            }

            return $dispositions;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function findById(int $id): ?Disposition
    {
        $sql = "SELECT * FROM disposition WHERE dispositionid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Disposition::of($result);
        } catch (PDOException) {
            return null;
        }
    }

    public function updateDesc(Disposition|int $disposition, string $desc): Disposition|false
    {
        $sql = "UPDATE disposition SET dispositiondesc = :desc WHERE dispositionid = :id";
        $id = $disposition instanceof Disposition ? $disposition->getId() : $disposition;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':desc', $desc);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM disposition WHERE dispositionid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return Disposition::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function delete(Disposition|int $disposition): bool
    {
        $sql = "DELETE FROM disposition WHERE dispositionid = :id";
        $id = $disposition instanceof Disposition ? $disposition->getId() : $disposition;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Function to return disposition id by looking up a description
    public function findByName($desc): int
    {
        $sql = "SELECT dispositionid FROM disposition WHERE dispositiondesc = :desc";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while looking up disposition id: " . $e->getMessage());
            return -1;
        }
    }
}
