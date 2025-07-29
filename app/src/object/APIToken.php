<?php
namespace puzzlethings\src\object;

use PDO;
use puzzlethings\src\gateway\UserGateway;

class APIToken implements \JsonSerializable
{
    private int $id;
    private User $user;
    private string $token;
    private int $permissions;

    public function __construct(int $id, User $user, string $token, int $permissions) {
        $this->id = $id;
        $this->user = $user;
        $this->token = $token;
        $this->permissions = $permissions;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPermissions(): int
    {
        return $this->permissions;
    }

    public static function of(mixed $res, PDO $db): APIToken {
        $user = new UserGateway($db)->findById($res['userid']);
        return new APIToken($res['tokenid'], $user, $res['apitoken'], $res['permissions']);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'permissions' => $this->permissions,
        ];
    }
}