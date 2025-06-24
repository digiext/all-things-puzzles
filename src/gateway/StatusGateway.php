<?php
namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Ownership;
use puzzlethings\src\object\Status;

class StatusGateway {
    private PDO $db;

    public function __construct($db) {
        $this->db = $db;
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
}