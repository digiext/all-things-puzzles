<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Source;

class SourceGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create(string $desc): bool
    {
        $sql = "INSERT INTO source (sourcedesc) VALUES (:desc)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    public function findAll($sortBy = null): array
    {
        $sql = "SELECT * FROM source";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $sources = array();

            foreach ($result as $res) {
                $sources[] = Source::of($res);
            }

            return $sources;
        } catch (PDOException) {
            return [];
        }
    }

    public function findById(int $id): ?Source
    {
        $sql = "SELECT * FROM source WHERE sourceid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Source::of($result);
        } catch (PDOException) {
            return null;
        }
    }

    public function updateName(Source|int $source, string $name): bool
    {
        $sql = "UPDATE source SET sourcedesc = :name WHERE sourceid = :id";
        $id = $source instanceof Source ? $source->getId() : $source;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    public function delete(Source|int $source): bool
    {
        $sql = "DELETE FROM source WHERE sourceid = :id";
        $id = $source instanceof Source ? $source->getId() : $source;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}
