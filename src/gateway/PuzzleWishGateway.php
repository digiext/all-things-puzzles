<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\{
    Brand,
    PuzzleWish,
};

class PuzzleWishGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create new record in puzzlewish table
    public function create(int $userid, string $name, int $pieces, Brand|int $brand, string $upc): PuzzleWish|false
    {
        $sql = "INSERT INTO puzzlewish (userid, puzname, pieces, brandid, upc) VALUES (:userid, :name, :pieces, :brandid, :upc)";

        $brandId = $brand instanceof Brand ? $brand->getId() : $brand;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':pieces', $pieces);
            $stmt->bindParam(':brandid', $brandId);
            $stmt->bindParam(':upc', $upc);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return PuzzleWish::of([
                "wishid" => $id,
                "userid" => $userid,
                "puzname" => $name,
                "pieces" => $pieces,
                "brandid" => $brandId,
                "upc" => $upc
            ], $this->db);
        } catch (PDOException $e) {
            error_log("Database error while adding puzzle: " . $e->getMessage());
            return false;
        }
    }

    // Count total records in puzzlewish table based on filters
    public function count($options = [
        FILTERS => []
    ]): int
    {
        $filters = $this->determineFilters($options[FILTERS] ?? []);
        $sql = "SELECT COUNT(*) FROM puzzlewish $filters";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting puzzles: " . $e->getMessage());
            return -1;
        }
    }

    // Find all records in puzzlewish table based on sort and filter options passed
    public function findAll(mixed $options = [
        PAGE => 0,
        MAX_PER_PAGE => 10,
        SORT => null,
        SORT_DIRECTION => SQL_SORT_ASC,
        FILTERS => []
    ]): array
    {
        require_once __DIR__ . '/../../util/constants.php';

        $sort = $options[SORT] ?? PUZ_ID;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;
        $page = $options[PAGE] ?? 0;
        $maxPerPage = $options[MAX_PER_PAGE] ?? 10;
        $filters = $options[FILTERS] ?? [];

        $offset = $page * $maxPerPage;
        $filters = $this->determineFilters($filters);

        $sql = "SELECT * FROM puzzlewish $filters ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $puzzles = array();

            foreach ($result as $res) {
                $puzzles[] = PuzzleWish::of($res, $this->db);
            }

            return $puzzles;
        } catch (PDOException $e) {
            error_log('Error while fetching puzzles: ' . $e->getMessage());
            die();
        }
    }

    // Determine what filter to apply to puzzlewish table based on what options are passed 
    private function determineFilters(mixed $filters = []): string
    {
        $res = "";

        foreach ($filters as $filter => $val) {
            switch ($filter) {
                case PUZ_FILTER_NAME: {
                        $res .= "AND puzname LIKE %" . $val . "% ";
                        break;
                    }
                case PUZ_FILTER_PIECES: {
                        if (is_array($val)) {
                            $res .= "AND pieces BETWEEN $val[0] AND $val[1]";
                        } else {
                            $res .= "AND pieces = $val";
                        }
                        break;
                    }
                case PUZ_FILTER_BRAND: {
                        if ($val instanceof Brand) {
                            $id = $val->getId();
                            $res .= "AND brandid = $id";
                        } else {
                            $res .= "AND brandid = $val";
                        }
                        break;
                    }
            }
        }

        $pos = strpos($res, "AND");
        if ($pos !== false) {
            $res = substr_replace($res, "WHERE", $pos, 3);
        }

        return $res;
    }

    // Find specific record from puzzlewish table based on wishid
    public function findById(int $id, mixed $options = []): ?PuzzleWish
    {
        $sql = "SELECT * FROM puzzlewish WHERE wishid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($stmt->rowCount() == 0) return null;

            return PuzzleWish::of($result, $this->db);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Find records from puzzlewish table based on userid
    public function findByUserId(int $id): ?array
    {
        $sql = "SELECT * FROM puzzlewish WHERE userid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            $upuzzles = array();
            foreach ($result as $res) {
                $upuzzles[] = PuzzleWish::of($res, $this->db);
            }

            return $upuzzles;
        } catch (PDOException $e) {
            return null;
        }
    }

    // Find records from puzzlewish table based on puzzle name
    public function findByName(string $name, mixed $options = []): ?PuzzleWish
    {
        $sql = "SELECT * FROM puzzlewish WHERE puzname LIKE :name";

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

            return PuzzleWish::of($result, $this->db);
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

    // Update puzzlewish record based on wishid
    public function update(PuzzleWish|int $puzzle, array $values): bool
    {
        if (empty($puzzle)) return false;
        $id = $puzzle instanceof PuzzleWish ? $puzzle->getId() : $puzzle;

        $sets = "";
        foreach ($values as $key => $value) {
            $sets .= "$key = :$key, ";
        }

        if ($sets == '') return false;

        $sql = "UPDATE puzzlewish SET $sets WHERE wishid = :wishid";

        $pos = strrpos($sql, ", ");
        if ($pos !== false) {
            $sql = substr_replace($sql, "", $pos, 2);
        }

        try {
            $stmt = $this->db->prepare($sql);
            // $stmt->bindParam(':puzzleid', $id, PDO::PARAM_INT);

            $exec = [
                ":wishid" => $id,
            ];
            foreach ($values as $key => $value) {
                $exec[":$key"] = $value;
                // $stmt->bindParam(":$key", $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }

            return $stmt->execute($exec);
        } catch (PDOException $e) {
            error_log("Database error while updating puzzle: " . $e->getMessage());
            return false;
        }
    }

    // Delete record from puzzlewish table based on wishid
    public function delete(PuzzleWish|int $id): bool
    {
        $sql = "DELETE FROM puzzlewish WHERE wishid = :wishid";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':wishid', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error while deleting puzzle: " . $e->getMessage());
            return false;
        }
    }
}
