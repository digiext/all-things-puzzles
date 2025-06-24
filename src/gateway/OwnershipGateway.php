<?php
namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Ownership;

class OwnershipGateway {
    private PDO $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll(): array {
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

    public function findById(int $id): ?Ownership {
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
}