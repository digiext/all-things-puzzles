<?php
namespace puzzlethings\src\gateway;

use PDO;
use PDOException;

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
            $pResults = array();
            foreach ($result as $res) {
                $pResults[] = [
                    "id" => $res["brandid"],
                    "name" => $res["brandname"],
                ];
            }
            return $pResults;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}