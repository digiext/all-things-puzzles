<?php
namespace puzzlethings\src\object;

use DateTime;

class User implements \JsonSerializable {
    private int $id;
    private string $username;
    private ?string $fullname, $email, $password, $hash;
    private ?int $groupid, $themeid;
    private bool $emailconfirmed;
    private ?DateTime $lastlogin;

    public function __construct(int $id, ?string $username, ?string $fullname, ?string $email, ?bool $emailconfirmed, ?string $password, ?string $hash, ?int $groupid, ?int $themeid, ?DateTime $lastlogin) {
        $this->id = $id;
        $this->username = $username;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->emailconfirmed = $emailconfirmed;
        $this->password = $password;
        $this->hash = $hash;
        $this->groupid = $groupid;
        $this->themeid = $themeid;
        $this->lastlogin = $lastlogin;
    }

    public static function of(mixed $res): User {
        return new User($res["userid"], $res["user_name"], $res['full_name'], $res['email'], $res['emailconfirmed'] ?? false, $res['password'], $res['user_hash'], $res['groupid'], $res['themeid'], new DateTime($res['lastlogin']));
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): ?string {
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

    public function getGroupId(): ?int {
        return $this->groupid;
    }

    public function getThemeId(): ?int {
        return $this->themeid;
    }

    public function getLastLogin(): ?DateTime {
        return $this->lastlogin;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "username" => $this->username,
            "fullname" => $this->fullname,
            "email" => $this->email,
            "emailconfirmed" => $this->emailconfirmed,
            "groupid" => $this->groupid,
            "themeid" => $this->themeid,
            "lastlogin" => $this->lastlogin
        ];
    }
}