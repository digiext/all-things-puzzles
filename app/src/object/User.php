<?php
namespace puzzlethings\src\object;

use DateTime;
use PDO;
use puzzlethings\src\gateway\ThemeGateway;

class User implements \JsonSerializable {
    private int $id;
    private string $username;
    private ?string $fullname, $email, $password, $hash, $lastlogin;
    private ?int $groupid;
    private ?Theme $theme;
    private bool $emailconfirmed;

    public function __construct(int $id, ?string $username, ?string $fullname, ?string $email, ?bool $emailconfirmed, ?string $password, ?string $hash, ?int $groupid, ?Theme $theme, ?string $lastlogin) {
        $this->id = $id;
        $this->username = $username;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->emailconfirmed = $emailconfirmed;
        $this->password = $password;
        $this->hash = $hash;
        $this->groupid = $groupid;
        $this->theme = $theme;
        $this->lastlogin = $lastlogin;
    }

    public static function of(mixed $res, PDO $db): User {
        $theme = new ThemeGateway($db)->findById($res['themeid']);
        return new User($res["userid"], $res["user_name"], $res['full_name'], $res['email'], $res['emailconfirmed'] ?? false, $res['user_password'], $res['user_hash'], $res['usergroupid'], $theme, $res['lastlogin']);
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getFullname(): ?string {
        return $this->fullname;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function emailConfirmed(): bool {
        return $this->emailconfirmed;
    }

    public function getHash(): ?string {
        return $this->hash;
    }

    public function getGroupId(): ?int {
        return $this->groupid;
    }

    public function getTheme(): ?Theme {
        return $this->theme;
    }

    public function getLastLogin(): ?string {
        return $this->lastlogin;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "username" => $this->username,
            "display_name" => $this->fullname,
            "email" => $this->email,
            "email_confirmed" => $this->emailconfirmed,
            "group" => GROUPS[$this->groupid],
            "theme" => $this->theme,
            "last_login" => $this->lastlogin
        ];
    }

    public function jsonSerializeMin(): mixed{
        return [
            "id" => $this->id,
            "username" => $this->username,
            "display_name" => $this->fullname,
            "last_login" => $this->lastlogin,
        ];
    }
}