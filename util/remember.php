<?php

use puzzlethings\src\gateway\AuthGateway;
use puzzlethings\src\object\User;

require_once __DIR__ . '/constants.php';

function genTokens(): array {
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));

    return [$selector, $validator, $selector . "::" . $validator];
}

function parseToken(string $token): ?array {
    $parts = explode("::", $token);

    if ($parts && count($parts) == 2) {
        return [$parts[0], $parts[1]];
    }
    return null;
}

function tokenIsValid(AuthGateway $gateway, string $token): bool {
    [$selector, $validator] = parseToken($token);
    $tokens = $gateway->findTokenBySelector($selector);

    if (!$tokens) {
        return false;
    }

    return password_verify($validator, $tokens['hashed_validator']);
}

function remember(AuthGateway $gateway, User|int $user, int $days = 30): void {
    [$selector, $validator, $token] = genTokens();

    $gateway->deleteToken($user);

    $expiry_seconds = time() + 60 * 60 * 24 * $days;

    $hash_validator = password_hash($validator, PASSWORD_BCRYPT);
    $expiry = date("Y-m-d H:i:s", $expiry_seconds);

    if ($gateway->insertUserToken($user, $selector, $hash_validator, $expiry)) {
        $options = [
            'expires' => $expiry_seconds,
            'path' => '/',
            'httponly' => true,
        ];
        setcookie(REMEMBER_ME, $token, $options);
    }
}