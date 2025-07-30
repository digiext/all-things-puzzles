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
use puzzlethings\src\gateway\interfaces\IGatewayWithFilters;
use puzzlethings\src\gateway\interfaces\IGatewayWithID;

class PuzzleGateway implements IGatewayWithID, IGatewayWithFilters
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create new record in puzzleinv table
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

    // Count records based on filter in puzzleinv table
    public function count($options = [
        FILTERS => []
    ]): int
    {
        $filters = $this->filtersToSQL($options[FILTERS] ?? []);
        $sql = "SELECT COUNT(*) FROM puzzleinv $filters";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting puzzles: " . $e->getMessage());
            return -1;
        }
    }

    // Find all records with sort and filter options on puzzleinv table
    public function findAll(mixed $options = [
        PAGE => 0,
        MAX_PER_PAGE => 10,
        SORT => PUZ_ID,
        SORT_DIRECTION => SQL_SORT_ASC,
        FILTERS => []
    ], bool $verbose = false): array|null|PDOException
    {
        require_once __DIR__ . '/../../util/constants.php';

        $sort = $options[SORT] ?? PUZ_ID;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;
        $page = $options[PAGE] ?? 0;
        $maxPerPage = $options[MAX_PER_PAGE] ?? 10;
        $filters = $options[FILTERS] ?? [];

        $offset = $page * $maxPerPage;
        $filters = $this->filtersToSQL($filters);

        $sql = "SELECT puzzleinv.*, brand.brandname FROM puzzleinv INNER JOIN brand ON puzzleinv.brandid = brand.brandid $filters ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $puzzles = array();

            foreach ($result as $res) {
                $puzzles[] = Puzzle::of($res, $this->db);
            }

            return $puzzles;
        } catch (PDOException $e) {
            error_log('Error while fetching puzzles: ' . $e->getMessage());
            return $verbose ? $e : null;
        }
    }

    // Determine what filter to apply to puzzleinv table based on what options are passed
    public function filtersToSQL(mixed $filters = []): string
    {
        if (empty($filters)) {
            return "";
        }

        $res = "";

        foreach ($filters as $filter => $val) {
            switch ($filter) {
                case PUZ_FILTER_NAME: {
                        $res .= "AND puzname LIKE '%" . $val . "%' ";
                        break;
                    }
                case PUZ_FILTER_PIECES: {
                        if (is_array($val)) {
                            $res .= "AND pieces BETWEEN $val[0] AND $val[1] ";
                        } else {
                            $res .= "AND pieces = $val ";
                        }
                        break;
                    }
                case PUZ_FILTER_BRAND: {
                        if ($val instanceof Brand) {
                            $id = $val->getId();
                            $res .= "AND puzzleinv.brandid = $id ";
                        } else {
                            $res .= "AND puzzleinv.brandid = $val ";
                        }
                        break;
                    }
                case PUZ_FILTER_COST: {
                        if (is_array($val)) {
                            $res .= "AND cost BETWEEN $val[0] AND $val[1] ";
                        } else {
                            $res .= "AND cost = $val ";
                        }
                        break;
                    }
                case PUZ_FILTER_SOURCE: {
                        if ($val instanceof Source) {
                            $id = $val->getId();
                            $res .= "AND sourceid = $id ";
                        } else {
                            $res .= "AND sourceid = $val ";
                        }
                        break;
                    }
                case PUZ_FILTER_LOCATION: {
                        if ($val instanceof Location) {
                            $id = $val->getId();
                            $res .= "AND locationid = $id ";
                        } else {
                            $res .= "AND locationid = $val ";
                        }
                        break;
                    }
                case PUZ_FILTER_DISPOSITION: {
                        if ($val instanceof Disposition) {
                            $id = $val->getId();
                            $res .= "AND dispositionid = $id ";
                        } else {
                            $res .= "AND dispositionid = $val ";
                        }
                        break;
                    }
            }
        }

        $pos = strpos($res, "AND");
        if ($pos !== false) {
            $res = substr_replace($res, "WHERE", $pos, 3);
            $res = rtrim($res);
        }

        return $res;
    }

    // Find record in puzzleinv table based on puzzleid
    public function findById(int $id): ?Puzzle
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

    // Find categoryid from puzcat table based on puzzleid
    public function findCatId(int $id): ?array
    {
        $sql = "SELECT puzcat.categoryid FROM puzcat WHERE puzcat.puzzleid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if ($stmt->rowCount() == 0) return null;

            $catids = array();
            foreach ($result as $res) {
                $catids[] = $res;
            }

            return $catids;
        } catch (PDOException $e) {
            error_log("Database error while finding category IDs: " . $e->getMessage());
            return null;
        }
    }

    // Find category descriptions from puzcat table based on puzzleid
    public function findCatNames(int $id): ?array
    {
        $sql = "SELECT categories.categorydesc FROM puzcat INNER JOIN categories ON puzcat.categoryid = categories.categoryid WHERE puzcat.puzzleid = :id ORDER BY categories.categorydesc";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if ($stmt->rowCount() == 0) return null;

            $catids = array();
            foreach ($result as $res) {
                $catids[] = $res;
            }

            return $catids;
        } catch (PDOException $e) {
            return null;
        }
    }

    // Find record from puzzleinv table based on puzzle name
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

    // Update puzzle record in puzzleinv table
    public function update(Puzzle|int $puzzle, array $values): bool
    {
        if (empty($puzzle)) return false;
        $id = $puzzle instanceof Puzzle ? $puzzle->getId() : $puzzle;

        $sets = "";
        foreach ($values as $key => $value) {
            $sets .= "$key = :$key, ";
        }

        if ($sets == '') return false;

        $sql = "UPDATE puzzleinv SET $sets WHERE puzzleid = :puzzleid";

        $pos = strrpos($sql, ", ");
        if ($pos !== false) {
            $sql = substr_replace($sql, "", $pos, 2);
        }

        try {
            $stmt = $this->db->prepare($sql);
            // $stmt->bindParam(':puzzleid', $id, PDO::PARAM_INT);

            $exec = [
                ":puzzleid" => $id,
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

    // Delete record from table puzzleinv based on puzzleid
    public function delete(Puzzle|int $puz): bool
    {
        $id = $puz instanceof Puzzle ? $puz->getId() : $puz;
        $sql = "DELETE FROM puzzleinv WHERE puzzleid = :puzzleid";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':puzzleid', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error while deleting puzzle: " . $e->getMessage());
            return false;
        }
    }

    // List top 5 most recent puzzles added based on addeddate
    public function recent(): array
    {
        $sql = "SELECT * FROM puzzleinv ORDER BY addeddate DESC LIMIT 5";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $recents = array();

            foreach ($result as $res) {
                $recents[] = Puzzle::of($res, $this->db);
            }

            return $recents;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
