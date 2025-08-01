<?php

use Dotenv\Dotenv;
use puzzlethings\src\gateway\AuthGateway;
use puzzlethings\src\gateway\UserGateway;
use puzzlethings\src\object\User;

include_once 'constants.php';

require __DIR__ . '/../vendor/autoload.php';

session_start();
try {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
    $GLOBALS['DEV'] = ($_ENV['DEVELOPER_MODE'] ?? 'false') === 'true';
} catch (Exception $e) {
    die("Unable to load the dotenv file: " . $e->getMessage());
}

if (!isset($_ENV['BASE_URL'])) die("Base URL not set in .env!");
define("BASE_URL", rtrim($_ENV['BASE_URL'], '/'));

function isDev(): bool
{
    return $GLOBALS['DEV'] ?? false;
}

function returnToHome(): void
{
    returnTo('home.php');
}

function returnToIndex(): void
{
    returnTo('index.php');
}

function returnTo($string)
{
    header('Location: ' . $string);
}

function encrypt(string $value): string
{
    $key = md5(openssl_random_pseudo_bytes(4));

    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);

    $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);

    return base64_encode($ciphertext . '::' . $iv . '::' . $key);
}

// Decrypt cookie
function decrypt(string $ciphertext): string|false
{
    $cipher = "aes-256-cbc";

    [$encrypted_data, $iv, $key] = explode('::', base64_decode($ciphertext));
    return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}

function cookieSet(string $cookie): bool
{
    return isset($_COOKIE[$cookie]);
}

function deleteCookie(string $cookie): void
{
    if (cookieSet($cookie)) {
        setcookie($cookie, "", [
            'expires' => -1,
        ]);
    }
}

function getUserID(): int|false
{
    if (!isLoggedIn()) return false;
    return decrypt($_SESSION[SESS_USER_ID]);
}

function getUserName(): string|false
{
    if (!isLoggedIn()) return false;
    if (isset($_SESSION[SESS_USER_NAME])) {
        return decrypt($_SESSION[SESS_USER_NAME]);
    } else {
        $user = getLoggedInUser();
        if (!$user) return false;

        $uname = $user->getUsername();
        $_SESSION[SESS_USER_NAME] = encrypt($uname);
        return $uname;
    }
}

function getLoggedInUser(): User|false
{
    if (!isLoggedIn()) return false;

    global $db;
    require_once __DIR__ . '/db.php';

    $gateway = new UserGateway($db);
    return $gateway->findById(getUserID()) ?? false;
}

function isLoggedIn(): bool
{
    if (isset($_SESSION[SESS_USER_ID])) return true;

    // else check for rememberme
    $token = filter_input(INPUT_COOKIE, COOKIE_REMEMBER_ME, FILTER_SANITIZE_SPECIAL_CHARS);

    global $db;
    require_once __DIR__ . '/remember.php';
    require_once __DIR__ . '/db.php';

    $gateway = new AuthGateway($db);

    if ($token && tokenIsValid($gateway, $token)) {
        $user = $gateway->findUserByToken($token);

        if ($user) {
            $_SESSION[SESS_USER_ID] = $user->getId();
            $_SESSION[SESS_USER_GROUP] = $user->getGroupId();

            return true;
        }
    }

    return false;
}

function isAdmin(): bool
{
    return (isLoggedIn() && isset($_SESSION[SESS_USER_GROUP]) && decrypt($_SESSION[SESS_USER_GROUP]) == "" . GROUP_ID_ADMIN);
}

function successAlertNoRedir($value): void
{
    $_SESSION['success'] = $value;
}

function successAlert($value, $redir = "index.php"): void
{
    $_SESSION['success'] = $value;
    header("Location: " . $redir);
}

function warningAlertNoRedir($value): void
{
    $_SESSION['warning'] = $value;
}

function warningAlert($value, $redir = "index.php"): void
{
    $_SESSION['warning'] = $value;
    header("Location: " . $redir);
}

function failAlertNoRedir($value): void
{
    $_SESSION['fail'] = $value;
}

function failAlert($value, $redir = "index.php"): void
{
    $_SESSION['fail'] = $value;
    header("Location:" . $redir);
}

function hyphenate($str, array $noStrip = []): string
{
    // non-alpha and non-numeric characters become spaces
    $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
    $str = trim($str);
    $str = str_replace(" ", "-", $str);
    $str = strtolower($str);

    return $str;
}

function rrmdir($dir): void
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                    rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                else
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
            }
        }
        rmdir($dir);
    }
}

function getThumbnail($path)
{
    if (($path ?? '') === '') {
        return 'images/no-image-dark.svg';
    } else if (file_exists('images/uploads/thumbnails/' . $path)) {
        return 'images/uploads/thumbnails/' . $path;
    } else if (key_exists('IMAGE_MIRROR', $_ENV) && $_ENV['IMAGE_MIRROR'] != "" && fopen($_ENV['IMAGE_MIRROR'] . 'thumbnails/' . $path, "r")) {
        return $_ENV['IMAGE_MIRROR'] . 'thumbnails/' . $path;
    } else {
        return 'images/fail-load-image-dark.svg';
    }
}

function getThumbnailCompleted($path)
{
    if (($path ?? '') === '') {
        return 'images/no-image-dark.svg';
    } else if (file_exists('images/uploads/completed/' . $path)) {
        return 'images/uploads/completed/' . $path;
    } else if (key_exists('IMAGE_MIRROR', $_ENV) && $_ENV['IMAGE_MIRROR'] != "" && fopen($_ENV['IMAGE_MIRROR'] . 'completed/' . $path, "r")) {
        return $_ENV['IMAGE_MIRROR'] . 'completed/' . $path;
    } else {
        return 'images/fail-load-image-dark.svg';
    }
}

function getNiceDateRepresentation($date): string
{
    $timestamp = strtotime($date);
    if ((int) date("j", $timestamp) % 10 == 1 && (int) date("j", $timestamp) != 11) {
        // 1st, 21st, 31st
        return date("F j", $timestamp) . "st, " . date("Y", $timestamp);
    } else if ((int) date("j", $timestamp) % 10 == 2 && (int) date("j", $timestamp) != 12) {
        // 2nd, 22nd
        return date("F j", $timestamp) . "nd, " . date("Y", $timestamp);
    } else if ((int) date("j", $timestamp) % 10 == 3 && (int) date("j", $timestamp) != 13) {
        // 3rd, 23rd
        return date("F j", $timestamp) . "rd, " . date("Y", $timestamp);
    } else {
        // 4th, 5th, 6th, 7th... 11th, 12th, 13th, 14th...
        return date("F j", $timestamp) . "th, " . date("Y", $timestamp);
    }
}
