<?php
namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Status;

class StatusGateway {
    private PDO $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(string $desc): bool {
        $sql = "INSERT INTO status (statusdesc) VALUES (:desc)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    public function findAll(): array {
        $sql = "SELECT * FROM status";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $statuses = array();

            foreach ($result as $res) {
                $statuses[] = Status::of($res);
            }

            return $statuses;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findById(int $id): ?Status {
        $sql = "SELECT * FROM status WHERE statusid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Status::of($result);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateDesc(Status|int $status, string $desc): Status|false {
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

    public function delete(Status|int $status): bool {
        $sql = "DELETE FROM disposition WHERE dispositionid = :id";
        $id = $status instanceof Status ? $status->getId() : $status;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}