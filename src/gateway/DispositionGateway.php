<?php
namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Disposition;

class DispositionGateway {
    private PDO $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll(): array {
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
            exit($e->getMessage());
        }
    }

    public function findById(int $id): ?Disposition {
        $sql = "SELECT * FROM disposition WHERE dispositionid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Disposition::of($result);
        } catch (PDOException $e) {
            return null;
        }
    }
}