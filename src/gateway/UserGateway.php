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

    public function create(string $username, string $fullname, string $email, string $password, bool $returnuser = true, int $group = USER_GROUP_ID): User|PDOException|true|int
    {
        $sql = "INSERT INTO user (user_name, full_name, email, emailconfirmed, user_password, user_hash, usergroupid, themeid, lastlogin) VALUES (:username, :fullname, :email, 0, :password, :hash, :usergroup, 1, NOW())";

        $hash = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1, 16))), 1, 32);
        $hashedPassword = password_hash($password . $hash, PASSWORD_BCRYPT);

        if (!preg_match('/^[0-9a-zA-Z_]{5,16}$/', $username)) {
            error_log("Invalid username $username");
            return INVALID_USERNAME;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log("Invalid email $email");
            return INVALID_EMAIL;
        }

        $usernameSql = "SELECT COUNT(*) FROM user WHERE user_name = :username";
        try {
            $stmt = $this->db->prepare($usernameSql);
            $stmt->execute([':username' => $username]);
            if ($stmt->fetchColumn() > 0) {
                error_log("Username $username in use!");
                return USERNAME_IN_USE;
            }
        } catch (PDOException $e) {
            error_log("Database error while checking usernames! " .  $e->getMessage());
            return USERNAME_DB_ERROR;
        }

        $emailSql = "SELECT COUNT(*) FROM user WHERE email = :email";
        try {
            $stmt = $this->db->prepare($emailSql);
            $stmt->execute([':email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                error_log("Email $email in use!");
                return EMAIL_IN_USE;
            }
        } catch (PDOException $e) {
            error_log("Database error while checking emails! " .  $e->getMessage());
            return EMAIL_DB_ERROR;
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':usergroup', $group);
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
        $sql = "UPDATE user SET user_password = :password, user_hash = :hash WHERE userid = :id";
        $id = $user instanceof User ? $user->getId() : $user;

        $hash = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1, 16))), 1, 32);
        $hashedPassword = password_hash($password . $hash, PASSWORD_BCRYPT);

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':hash', $hash);

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

    public function usernameInUse(string $username): bool {
        $sql = "SELECT COUNT(*) FROM user where user_name = :username";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Database error validating username: " . $e->getMessage());
            // Just to be on the safe side
            return true;
        }
    }

    public function emailInUse(string $email): bool {
        $sql = "SELECT COUNT(*) FROM user where email = :email";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Database error validating email: " . $e->getMessage());
            // Just to be on the safe side
            return true;
        }
    }


    public function attemptLogin(string $username, string $password, bool $setlastlogin = true): User|false {
        $sql = "SELECT * FROM user WHERE user_name = :username";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $success = $stmt->execute();

            if (!$success || $stmt->rowCount() == 0) {
                return false;
            }

            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $record['user_password'];

            // UNCOMMENT THIS TO CHECK YOUR HASH
            //error_log(password_hash($password . $record['user_hash'], PASSWORD_BCRYPT));

            if (password_verify($password . $record['user_hash'], $hashedPassword)) {
                if ($setlastlogin) {
                    try {
                        $llsql = "UPDATE user SET lastlogin = CURRENT_TIMESTAMP WHERE userid = :id";

                        $llstmt = $this->db->prepare($llsql);
                        $llstmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $llstmt->execute();
                    } catch (PDOException $e) {
                        error_log("Database error on updating last login: " . $e->getMessage());
                    }
                }

                return User::of($record);
            } else return false;
        } catch (PDOException $e) {
            error_log("Database error on signing in: " . $e->getMessage());
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
