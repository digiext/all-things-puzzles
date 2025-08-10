<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\gateway\interfaces\IGatewayWithID;
use puzzlethings\src\object\Source;

class SourceGateway implements IGatewayWithID
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create new record in source table
    public function create(string $desc): Source|false
    {
        $sql = "INSERT INTO source (sourcedesc) VALUES (:desc)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new Source($id, $desc);
        } catch (PDOException) {
            return false;
        }
    }

    // Count total records in source table
    public function count(mixed $options = []): int
    {
        $sql = "SELECT COUNT(*) FROM source";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting sources: " . $e->getMessage());
            return -1;
        }
    }

    // Find all records in source table
    public function findAll(mixed $options = [], bool $verbose = false): array|null|PDOException
    {
        $sort = $options[SORT] ?? SOURCE_ID;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;
        $page = $options[PAGE] ?? 0;
        $maxPerPage = $options[MAX_PER_PAGE] ?? 10;

        $offset = $page * $maxPerPage;

        $sql = "SELECT * FROM source ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $sources = array();

            foreach ($result as $res) {
                $sources[] = Source::of($res);
            }

            return $sources;
        } catch (PDOException $e) {
            return $verbose ? $e : null;
        }
    }

    // Find specific record from source table based on sourceid
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

    // Update description in source table based on sourceid
    public function updateDesc(Source|int $source, string $name): Source|false
    {
        $sql = "UPDATE source SET sourcedesc = :name WHERE sourceid = :id";
        $id = $source instanceof Source ? $source->getId() : $source;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $success = $stmt->execute();

            if ($success) return new Source($id, $name);
            else return false;
        } catch (PDOException) {
            return false;
        }
    }

    // Delete specific record in source table based on sourceid
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

    // Function to return source id by looking up a description
    public function findByName(string $desc): int
    {
        $sql = "SELECT sourceid FROM source WHERE sourcedesc = :desc";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while looking up source id: " . $e->getMessage());
            return -1;
        }
    }
}
