<?php

namespace puzzlethings\src\gateway;
use PDO;
use PDOException;
use puzzlethings\src\gateway\interfaces\IGatewayWithID;
use puzzlethings\src\object\APIToken;
use puzzlethings\src\object\User;

class APITokenGateway implements IGatewayWithID
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(User|int $user, string $token, int $permissions): APIToken|false
    {
        $userid = $user instanceof User ? $user->getId() : $user;
        $sql = "INSERT INTO apitokens (apitoken, userid, permissions) VALUES (:apitoken, :userid, :permissions)";

        $apitoken = hash('sha512', $token);

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':apitoken', $apitoken);
            $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
            $stmt->bindParam(':permissions', $permissions, PDO::PARAM_INT);
            $stmt->execute();

            $id = $this->db->lastInsertId();
            return new APIToken($id, $user, $permissions);
        } catch (PDOException $e) {
            error_log("Database error generating new API Token: " . $e->getMessage());
            return false;
        }
    }

    public function count(mixed $options = []): int
    {
        $sql = "SELECT COUNT(*) FROM apitokens";

        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error while counting API tokens: " . $e->getMessage());
            return -1;
        }
    }

    public function findAll(array $options = [], bool $verbose = false): array|null|PDOException
    {
        $sort = $options[SORT] ?? USER_ID;
        $sortDirection = $options[SORT_DIRECTION] ?? SQL_SORT_ASC;
        $page = $options[PAGE] ?? 0;
        $maxPerPage = $options[MAX_PER_PAGE] ?? 10;

        $offset = $page * $maxPerPage;

        $sql = "SELECT * FROM apitokens ORDER BY $sort $sortDirection LIMIT $offset, $maxPerPage";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tokens = array();

            global $db;
            foreach ($result as $res) {
                $tokens[] = APIToken::of($res, $db);
            }

            return $tokens;
        } catch (PDOException $e) {
            error_log("Database error while finding API tokens: " . $e->getMessage());
            return $verbose ? $e : null;
        }
    }

    public function findById(int $id): ?APIToken
    {
        $sql = "SELECT * FROM apitokens WHERE tokenid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) return null;

            global $db;
            return APIToken::of($stmt->fetch(PDO::FETCH_ASSOC), $db);
        } catch (PDOException $e) {
            error_log("Database error while finding API token: " . $e->getMessage());
            return null;
        }
    }

    public function findByToken(string $token): ?APIToken
    {
        $sql = "SELECT * FROM apitokens WHERE apitoken = :token";
        $hashed = hash('sha512', $token);

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":token", $hashed);
            $stmt->execute();

            if ($stmt->rowCount() == 0) return null;

            global $db;
            return APIToken::of($stmt->fetch(PDO::FETCH_ASSOC), $db);
        } catch (PDOException $e) {
            error_log("Database error while finding API token: " . $e->getMessage());
            return null;
        }
    }

    public function delete(int|string|APIToken $id): bool
    {
        $field = "tokenid";
        $value = $id;
        if ($id instanceof APIToken) {
            $value = $id->getId();
        } else if (is_string($id)) {
            $field = 'apitoken';
        }

        $sql = "DELETE FROM apitokens WHERE $field = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $value);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error while deleting API token: " . $e->getMessage());
            return false;
        }
    }
}