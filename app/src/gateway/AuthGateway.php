<?php

namespace puzzlethings\src\gateway;

use PDO;
use PDOException;
use puzzlethings\src\object\User;

class AuthGateway {
    private PDO $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findUserByToken(string $token): ?User {
        $tokens = parseToken($token);

        if (!$tokens) {
            return null;
        }

        $sql = 'SELECT * FROM user INNER JOIN auth ON auth.userid = user.userid WHERE selector = :selector AND expiry > NOW() LIMIT 1';

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':selector', $tokens[0]);
            $success = $stmt->execute();

            if ($success) {
                return User::of($stmt->fetch(PDO::FETCH_ASSOC));
            } else return null;
        } catch (PDOException $e) {
            error_log("Database error while fetching user by token: " . $e->getMessage());
            return null;
        }
    }

    public function findTokenBySelector(string $selector): ?array {
        $sql = 'SELECT * FROM auth WHERE selector = :selector AND expiry >= NOW() LIMIT 1';

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':selector', $selector);
            $success = $stmt->execute();

            if ($success) {
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                return $res === false ? null : $res;
            } else return null;
        } catch (PDOException $e) {
            error_log("Database error while fetching token by selector: " . $e->getMessage());
            return null;
        }
    }

    public function insertUserToken(User|int $user, string $selector, string $hashed_validator, string $expiry): bool {
        $sql = 'INSERT INTO auth (userid, selector, hashed_validator, expiry) VALUES (:userid, :selector, :hashed_validator, :expiry)';
        $id = $user instanceof User ? $user->getId() : $user;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':userid', $id, PDO::PARAM_INT);
            $stmt->bindValue(':selector', $selector);
            $stmt->bindValue(':hashed_validator', $hashed_validator);
            $stmt->bindValue(':expiry', $expiry);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error while inserting token for user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteToken(User|int $user): bool {
        $sql = 'DELETE FROM auth WHERE userid = :userid';
        $id = $user instanceof User ? $user->getId() : $user;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':userid', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error while deleting token for user: " . $e->getMessage());
            return false;
        }
    }

}