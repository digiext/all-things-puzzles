<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\{
    Ownership,
    Puzzle,
    Status,
    User,
    UserPuzzle
};

class UserPuzzleGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create new record in userinv table
    public function create(User|int $user, Puzzle|int $puzzle, Status|int $status, int $missingpieces, string $startdate, string $enddate, int $totaldays, float $difficultyrating, float $qualityrating, float $overallrating, Ownership|int $ownership, string $loanedoutto): UserPuzzle|false
    {
        $sql = "INSERT INTO userinv (userid, puzzleid, statusid, missingpieces, startdate, enddate, totaldays, difficultyrating, qualityrating, overallrating, ownershipid, loanedoutto) VALUES (:userid, :puzzleid, :statusid, :missingpieces, :startdate, :enddate, :totaldays, :difficultyrating, :qualityrating, :overallrating, :ownershipid, :loanedoutto)";

        $startdate = date("Y-m-d H:i:s", strtotime($startdate));
        $enddate = date("Y-m-d H:i:s", strtotime($enddate));
        $userId = $user instanceof User ? $user->getId() : $user;
        $puzzleId = $puzzle instanceof Puzzle ? $puzzle->getId() : $puzzle;
        $statusId = $status instanceof Status ? $status->getId() : $status;
        $ownershipId = $ownership instanceof Ownership ? $ownership->getId() : $ownership;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userid', $userId);
            $stmt->bindParam(':puzzleid', $puzzleId);
            $stmt->bindParam(':statusid', $statusId);
            $stmt->bindParam(':missingpieces', $missingpieces);
            $stmt->bindParam(':startdate', $startdate);
            $stmt->bindParam(':enddate', $enddate);
            $stmt->bindParam(':totaldays', $totaldays);
            $stmt->bindParam(':difficultyrating', $difficultyrating);
            $stmt->bindParam(':qualityrating', $qualityrating);
            $stmt->bindParam(':overallrating', $overallrating);
            $stmt->bindParam(':ownershipid', $ownershipId);
            $stmt->bindParam(':loanedoutto', $loanedoutto);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return UserPuzzle::of([
                "userinvid" => $id,
                "userid" => $userId,
                "puzzleid" => $puzzleId,
                "statusid" => $statusId,
                "missingpieces" => $missingpieces,
                "startdate" => $startdate,
                "enddate" => $enddate,
                "totaldays" => $totaldays,
                "difficultyrating" => $difficultyrating,
                "qualityrating" => $qualityrating,
                "overallrating" => $overallrating,
                "ownershipid" => $ownershipId,
                "loanedoutto" => $loanedoutto
            ], $this->db);
        } catch (PDOException $e) {
            error_log("Database error while adding puzzle: " . $e->getMessage());
            return false;
        }
    }

    // Count total records from userinv table based on filters passed
    public function count($options = [
        FILTERS => []
    ]): int
    {
        $filters = $this->determineFilters($options[FILTERS] ?? []);
        $sql = "SELECT COUNT(*) FROM userinv $filters";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting puzzles: " . $e->getMessage());
            return -1;
        }
    }

    // Find all records from userinv table based on options passed
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

        $sql = "SELECT * FROM userinv $filters ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $puzzles = array();

            foreach ($result as $res) {
                $puzzles[] = UserPuzzle::of($res, $this->db);
            }

            return $puzzles;
        } catch (PDOException $e) {
            error_log('Error while fetching puzzles: ' . $e->getMessage());
            die();
        }
    }

    // Determine filter to apply to userinv table
    private function determineFilters(mixed $filters = []): string
    {
        $res = "";

        foreach ($filters as $filter => $val) {
            switch ($filter) {
                case USR_FILTER_USER: {
                        if ($val instanceof User) {
                            $id = $val->getId();
                        } else $id = $val;
                        $res .= "AND userid = $id ";
                        break;
                    }
                case USR_FILTER_STATUS: {
                        if ($val instanceof Status) {
                            $id = $val->getId();
                            $res .= "AND statusid = $id";
                        } else {
                            $res .= "AND statusid = $val";
                        }
                        break;
                    }
                case USR_FILTER_MISSING: {
                        $res .= "AND missingpieces > 0";
                        break;
                    }
                case USR_FILTER_DIFFICULTY: {
                        $res .= "AND difficultyrating > 0";
                        break;
                    }
                case USR_FILTER_QUALITY: {
                        $res .= "AND qualityrating > 0";
                        break;
                    }
                case USR_FILTER_OVERALL: {
                        $res .= "AND overallrating > 0";
                        break;
                    }
                case USR_FILTER_OWNERSHIP: {
                        if ($val instanceof Ownership) {
                            $id = $val->getId();
                            $res .= "AND ownershipid = $id";
                        } else {
                            $res .= "AND ownershipid = $val";
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

    // Find specific record from userinv table based on userinvid
    public function findById(int $id, mixed $options = []): ?UserPuzzle
    {
        $sql = "SELECT * FROM userinv WHERE userinvid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($stmt->rowCount() == 0) return null;

            return UserPuzzle::of($result, $this->db);
        } catch (PDOException $e) {
            return null;
        }
    }


    // public function findByName(string $name, mixed $options = []): ?Puzzle
    // {
    //     $sql = "SELECT * FROM puzzleinv WHERE name LIKE :name";

    //     $before = null;
    //     $after = null;
    //     if ($options['before'] != null && $options['after'] != null) {
    //         $before = $options['before']->format('Y-m-d H:i:s');
    //         $after = $options['after']->format('Y-m-d H:i:s');
    //         $sql .= " AND dateacquired BETWEEN :after AND :before";
    //     } else if ($options['before'] != null) {
    //         $before = $options['before']->format('Y-m-d H:i:s');
    //         $sql .= " AND dateacquired < :before";
    //     } else if ($options['after'] != null) {
    //         $after = $options['after']->format('Y-m-d H:i:s');
    //         $sql .= " AND dateacquired > :after";
    //     }

    //     try {
    //         $likeName = "%" . $name . "%";

    //         $stmt = $this->db->prepare($sql);
    //         $stmt->bindParam(':name', $likeName);
    //         if ($before != null) $stmt->bindParam(':before', $before);
    //         if ($after != null) $stmt->bindParam(':after', $after);

    //         $stmt->execute();
    //         $result = $stmt->fetch(PDO::FETCH_ASSOC);

    //         if ($stmt->rowCount() == 0) return null;

    //         return Puzzle::of($result, $this->db);
    //     } catch (PDOException $e) {
    //         print($e->getMessage());
    //         return null;
    //     }
    // }

    // Update record in userinv based on userinvid
    public function update(UserPuzzle|int $userinvid, array $values): bool
    {
        if (empty($userinvid)) return false;
        $id = $userinvid instanceof UserPuzzle ? $userinvid->getId() : $userinvid;

        $sets = "";
        foreach ($values as $key => $value) {
            $sets .= "$key = :$key, ";
        }

        if ($sets == '') return false;

        $sql = "UPDATE userinv SET $sets WHERE userinvid = :userinvid";

        $pos = strrpos($sql, ", ");
        if ($pos !== false) {
            $sql = substr_replace($sql, "", $pos, 2);
        }

        try {
            $stmt = $this->db->prepare($sql);
            // $stmt->bindParam(':puzzleid', $id, PDO::PARAM_INT);

            $exec = [
                ":userinvid" => $id,
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

    // Delete record from userinv based on userinvid
    public function delete(UserPuzzle|int $id): bool
    {
        $sql = "DELETE FROM userinv WHERE userinvid = :userinvid";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userinvid', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error while deleting puzzle: " . $e->getMessage());
            return false;
        }
    }

    // Return top 5 recently completed puzzles
    public function completed(): array
    {
        $sql = "SELECT * FROM userinv WHERE enddate != '1970-01-01' ORDER BY enddate DESC LIMIT 5";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $completed = array();

            foreach ($result as $res) {
                $completed[] = UserPuzzle::of($res, $this->db);
            }

            return $completed;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function userCompleted(int $id): array
    {
        $sql = "SELECT * FROM userinv WHERE enddate != '1970-01-01' AND userid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $usercompleted = array();

            foreach ($result as $res) {
                $usercompleted[] = UserPuzzle::of($res, $this->db);
            }

            return $usercompleted;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    // Return count of user completed puzzles
    public function userCountCompleted(int $id): int
    {
        $sql = "SELECT count(*) FROM userinv WHERE userid = :id AND enddate != '1970-01-01'";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting puzzles: " . $e->getMessage());
            return -1;
        }
    }

    // Return last user completed puzzle
    public function userLastCompleted(int $id): ?int
    {
        $sql = "SELECT puzzleid FROM userinv WHERE userid = :id AND enddate != '1970-01-01' ORDER BY enddate DESC LIMIT 1";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while retrieveing last completed puzzle: " . $e->getMessage());
            return null;
        }
    }

    // Find specific records from userinv based on userid
    public function findByUserId(int $id, mixed $options = [
        SORT => null,
        SORT_DIRECTION => SQL_SORT_ASC,
    ]): ?array
    {
        require_once __DIR__ . '/../../util/constants.php';

        $sort = $options[SORT] ?? USR_INV_ID;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;

        $sql = "SELECT * FROM userinv WHERE userid = :id ORDER BY $sort $sortDirection";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            $upuzzles = array();
            foreach ($result as $res) {
                $upuzzles[] = UserPuzzle::of($res, $this->db);
            }

            return $upuzzles;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function findByPuzzleId(int $id): ?array
    {
        $sql = "SELECT * FROM userinv WHERE puzzleid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            $upuzzles = array();
            foreach ($result as $res) {
                $upuzzles[] = UserPuzzle::of($res, $this->db);
            }

            return $upuzzles;
        } catch (PDOException $e) {
            return null;
        }
    }

    // Return top 5 highest rated puzzle based on overallrating
    public function highestrated(): array
    {
        // $sql = "SELECT *, (difficultyrating+qualityrating)/2 as rating FROM userinv WHERE (difficultyrating+qualityrating)/2 !=0 ORDER BY (rating) DESC LIMIT 5";

        $sql = "SELECT *, SUM(overallrating)/count(puzzleid) As SumOverall FROM userinv WHERE overallrating !=0 GROUP BY puzzleid ORDER BY SumOverall DESC LIMIT 5";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $highestrated = array();

            foreach ($result as $res) {
                $highestrated[] = UserPuzzle::of($res, $this->db);
            }

            return $highestrated;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
