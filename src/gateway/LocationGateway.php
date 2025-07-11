<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\Location;
use puzzlethings\src\object\Source;

class LocationGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create record in location table
    public function create(string $desc): Location|false
    {
        $sql = "INSERT INTO location (locationdesc) VALUES (:desc)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new Location($id, $desc);
        } catch (PDOException) {
            return false;
        }
    }

    // Count total number of records in location table
    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM location";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting locations: " . $e->getMessage());
            return -1;
        }
    }

    // Find all records in location table
    public function findAll(): array
    {
        $sql = "SELECT * FROM location";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $sources = array();

            foreach ($result as $res) {
                $sources[] = Location::of($res);
            }

            return $sources;
        } catch (PDOException) {
            return [];
        }
    }

    // Find specific record in location table based on locationid
    public function findById(int $id): ?Location
    {
        $sql = "SELECT * FROM location WHERE locationid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return Location::of($result);
        } catch (PDOException) {
            return null;
        }
    }

    // Update description in location table based on locationid
    public function updateDesc(Location|int $location, string $name): bool
    {
        $sql = "UPDATE location SET locationdesc = :name WHERE locationid = :id";
        $id = $location instanceof Location ? $location->getId() : $location;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Delete record from location table based on locationid
    public function delete(Location|int $location): bool
    {
        $sql = "DELETE FROM location WHERE locationid = :id";
        $id = $location instanceof Location ? $location->getId() : $location;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }

    // Function to return location id by looking up a description
    public function findByName(string $desc): int
    {
        $sql = "SELECT locationid FROM location WHERE locationdesc = :desc";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while looking up location id: " . $e->getMessage());
            return -1;
        }
    }
}
