<?php

namespace puzzlethings\src\gateway;

use DateTime;
use PDO;
use PDOException;
use puzzlethings\src\object\User;

const INVALID_USERNAME = 1;
const INVALID_EMAIL = 2;
const USERNAME_IN_USE = 3;
const EMAIL_IN_USE = 4;
const USERNAME_DB_ERROR = 5;
const EMAIL_DB_ERROR = 6;

class UserGateway
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create(string $username, string $fullname, string $email, string $password, bool $returnuser = true): User|PDOException|true|int
    {
        $sql = "INSERT INTO user (user_name, full_name, email, emailconfirmed, user_password, user_hash, usergroupid, themeid, lastlogin) VALUES (:username, :fullname, :email, 0, :password, :hash, 1, 1, NOW())";

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if (!preg_match('/^[0-9a-zA-Z_]{5,32}$/', $username)) {
            return INVALID_USERNAME;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return INVALID_EMAIL;
        }

        $usernameSql = "SELECT COUNT(*) FROM user WHERE user_name = :username";
        try {
            $stmt = $this->db->prepare($usernameSql);
            $stmt->execute([':username' => $username]);
            if ($stmt->fetchColumn() > 0) {
                return USERNAME_IN_USE;
            }
        } catch (PDOException) {
            return USERNAME_DB_ERROR;
        }

        $emailSql = "SELECT COUNT(*) FROM user WHERE email = :email";
        try {
            $stmt = $this->db->prepare($emailSql);
            $stmt->execute([':email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                return EMAIL_IN_USE;
            }
        } catch (PDOException) {
            return EMAIL_DB_ERROR;
        }

        $hash = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1, 16))), 1, 32);

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':hash', $hash);
            $success = $stmt->execute();

            if ($success && $returnuser) {
                return true; //new User($id, $username, $fullname, $email, false, $hashedPassword, $hash, 0, 0, new DateTime("now"));
            } else return $success;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM user";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $users = array();

            foreach ($result as $res) {
                $users[] = User::of($res);
            }

            return $users;
        } catch (PDOException) {
            return [];
        }
    }

    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE userid = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 0) return null;

            return User::of($result);
        } catch (PDOException) {
            return null;
        }
    }

    public function findByName(string $name): array {
        $sql = "SELECT * FROM user WHERE user_name = :username";

        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $users = array();

            foreach ($result as $res) {
                $users[] = User::of($res);
            }

            return $users;
        } catch (PDOException) {
            return [];
        }
    }

    public function updateUsername(User|int $user, string $name): User|false
    {
        $sql = "UPDATE user SET user_name = :name WHERE userid = :id";
        $id = $user instanceof User ? $user->getId() : $user;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM user WHERE userid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return User::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function updateFullName(User|int $user, string $fullname): User|false
    {
        $sql = "UPDATE user SET full_name = :fullname WHERE userid = :id";
        $id = $user instanceof User ? $user->getId() : $user;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':fullname', $fullname);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM user WHERE userid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return User::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function updateEmail(User|int $user, string $email): User|false
    {
        $sql = "UPDATE user SET email = :email WHERE userid = :id";
        $id = $user instanceof User ? $user->getId() : $user;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM user WHERE userid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return User::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function updatePassword(User|int $user, string $password): User|false
    {
        $sql = "UPDATE user SET user_password = :password WHERE userid = :id";
        $id = $user instanceof User ? $user->getId() : $user;
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM user WHERE userid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return User::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function updateGroup(User|int $user, int $groupid): User|false
    {
        $sql = "UPDATE user SET usergroupid = :groupid WHERE userid = :id";
        $id = $user instanceof User ? $user->getId() : $user;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':groupid', $groupid, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM user WHERE userid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return User::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function setEmailConfirmed(User|int $user): User|false
    {
        $sql = "UPDATE user SET emailconfirmed = TRUE WHERE userid = :id";
        $id = $user instanceof User ? $user->getId() : $user;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $userSql =  "SELECT * FROM user WHERE userid = :id";
                $userStmt = $this->db->prepare($userSql);
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                return User::of($userStmt->fetch(PDO::FETCH_ASSOC));
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function attemptLogin(string $username, string $password, bool $setlastlogin = true): User|false
    {
        $sql = "SELECT * FROM user WHERE user_name = :username";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $success = $stmt->execute();

            if (!$success || $stmt->rowCount() == 0) {
                return false;
            }

            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $record['user_pass'];

            if (password_verify($password, $hashedPassword)) {
                if ($setlastlogin) {
                    $now = (new DateTime("now"))->format("Y-m-d H:i:s");
                    $llsql = "UPDATE user SET lastlogin = :lastlogin WHERE userid = :id";

                    $llstmt = $this->db->prepare($llsql);
                    $llstmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $llstmt->bindParam(':lastlogin', $now);
                    $llstmt->execute();
                }

                return User::of($record);
            } else return false;
        } catch (PDOException) {
            return false;
        }
    }

    public function delete(User|int $user): bool
    {
        $sql = "DELETE FROM user WHERE userid = :id";
        $id = $user instanceof User ? $user->getId() : $user;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException) {
            return false;
        }
    }
}
