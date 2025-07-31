<?php

namespace puzzlethings\src\object;

use PDO;
use puzzlethings\src\gateway\UserGateway;

class APIToken implements \JsonSerializable
{
    private string $name, $expiration;
    private int $id, $permissions;
    private User $user;

    public function __construct(int $id, string $name, User $user, int $permissions, string $expiration)
    {
        $this->name = $name;
        $this->id = $id;
        $this->user = $user;
        $this->permissions = $permissions;
        $this->expiration = $expiration;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPermissions(): int
    {
        return $this->permissions;
    }

    public function getExpiration(): string
    {
        return $this->expiration;
    }

    public static function of(mixed $res, PDO $db): APIToken
    {
        $user = new UserGateway($db)->findById($res['userid']);
        return new APIToken($res['tokenid'], $res['tokenname'], $user, $res['permissions'], $res['expiration']);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => $this->user,
            'permissions' => $this->permissions,
            'expiration' => $this->expiration,
        ];
    }
}
