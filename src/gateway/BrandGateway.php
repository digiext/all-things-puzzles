<?php
namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Brand;

class BrandGateway {
    private PDO $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM brand";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $brands = array();

            foreach ($result as $res) {
                $brands[] = Brand::of($res);
            }

            return $brands;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findById(int $id): ?Brand {
        $sql = "SELECT * FROM brand WHERE brandid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Brand::of($result);
        } catch (PDOException $e) {
            return null;
        }
    }
}