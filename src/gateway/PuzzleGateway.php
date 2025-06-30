<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\{
    Brand,
    Source,
    Location,
    Disposition,
    Puzzle
};

class PuzzleGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create(string $name, int $pieces, Brand|int $brand, float $cost, string $dateAcquired, Source|int $source, Location|int $location, Disposition|int $disposition, string $upc): Puzzle|false
    {
        $sql = "INSERT INTO puzzleinv (puzname, pieces, brandid, cost, dateacquired, sourceid, locationid, dispositionid, upc) VALUES (:name, :pieces, :brandid, :cost, :dateacquired, :sourceid, :locationid, :dispositionid, :upc)";

        $date = date("Y-m-d H:i:s", strtotime($dateAcquired));
        $brandId = $brand instanceof Brand ? $brand->getId() : $brand;
        $sourceId = $source instanceof Source ? $source->getId() : $source;
        $locationId = $location instanceof Location ? $location->getId() : $location;
        $dispositionId = $disposition instanceof Disposition ? $disposition->getId() : $disposition;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':pieces', $pieces);
            $stmt->bindParam(':brandid', $brandId);
            $stmt->bindParam(':cost', $cost);
            $stmt->bindParam(':dateacquired', $date);
            $stmt->bindParam(':sourceid', $sourceId);
            $stmt->bindParam(':locationid', $locationId);
            $stmt->bindParam(':dispositionid', $dispositionId);
            $stmt->bindParam(':upc', $upc);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return Puzzle::of([
                "puzzleid" => $id,
                "puzname" => $name,
                "pieces" => $pieces,
                "brandid" => $brandId,
                "cost" => $cost,
                "dateacquired" => $date,
                "sourceid" => $sourceId,
                "locationid" => $locationId,
                "dispositionid" => $dispositionId,
                "pictureurl" => '',
                "upc" => $upc
            ], $this->db);
        } catch (PDOException $e) {
            error_log("Database error while adding puzzle: " . $e->getMessage());
            return false;
        }
    }

    public function count(): int {
        $sql = "SELECT COUNT(*) FROM puzzleinv";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting puzzles: " . $e->getMessage());
            return -1;
        }
    }

    public function findAll(mixed $options = [
        "page" => 0,
        "maxperpage" => 10
    ]): array
    {
        $sql = "SELECT * FROM puzzleinv LIMIT " . intval($options['page'] * $options['maxperpage']) . ", " . intval($options['maxperpage']);

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $puzzles = array();

            foreach ($result as $res) {
                $puzzles[] = Puzzle::of($res, $this->db);
            }

            return $puzzles;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findById(int $id, mixed $options = []): ?Puzzle
    {
        $sql = "SELECT * FROM puzzleinv WHERE puzzleid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($stmt->rowCount() == 0) return null;

            return Puzzle::of($result, $this->db);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function findByName(string $name, mixed $options = []): ?Puzzle
    {
        $sql = "SELECT * FROM puzzleinv WHERE puzname LIKE :name";

        $before = null;
        $after = null;
        if ($options['before'] != null && $options['after'] != null) {
            $before = $options['before']->format('Y-m-d H:i:s');
            $after = $options['after']->format('Y-m-d H:i:s');
            $sql .= " AND dateacquired BETWEEN :after AND :before";
        } else if ($options['before'] != null) {
            $before = $options['before']->format('Y-m-d H:i:s');
            $sql .= " AND dateacquired < :before";
        } else if ($options['after'] != null) {
            $after = $options['after']->format('Y-m-d H:i:s');
            $sql .= " AND dateacquired > :after";
        }

        try {
            $likeName = "%" . $name . "%";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $likeName);
            if ($before != null) $stmt->bindParam(':before', $before);
            if ($after != null) $stmt->bindParam(':after', $after);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Puzzle::of($result, $this->db);
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }
}
