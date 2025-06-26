<?php
include 'constants.php';
session_start();

function returnToHome(): void {
    returnTo('index.php');
}

function returnTo($string) {
    header('Location: ' . $string);
}

function encryptCookie(string $value): string {
    $key = md5(openssl_random_pseudo_bytes(4));

    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);

    $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);

    return base64_encode($ciphertext . '::' . $iv . '::' . $key);
}

// Decrypt cookie
function decryptCookie(string $ciphertext): string|false {
    $cipher = "aes-256-cbc";

    list($encrypted_data, $iv, $key) = explode('::', base64_decode($ciphertext));
    return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}

function cookieSet(string $cookie): bool {
    return isset($_COOKIE[$cookie]);
}

function deleteCookie(string $cookie): void {
    if (cookieSet($cookie)) {
        setcookie($cookie, null, -1, '/');
    }
}

function getUserID(): int|false {
    if (!isLoggedIn()) return false;
    return decryptCookie($_COOKIE[REMEMBER_ME]);
}

function isLoggedIn(): bool {
    return isset($_COOKIE[LOGGED_IN]) && decryptCookie($_COOKIE[LOGGED_IN]) == "true";
}

function isAdmin(): bool {
    return (isLoggedIn() && cookieSet(USER_GROUP) && decryptCookie($_COOKIE[USER_GROUP]) == ADMIN_GROUP_ID);
}

function successAlertNoRedir($value): void {
    $_SESSION['success'] = $value;
}

function successAlert($value, $redir = "index.php"): void {
    $_SESSION['success'] = $value;
    header("Location:" . $redir);
}

function failAlertNoRedir($value): void {
    $_SESSION['fail'] = $value;
}

function failAlert($value, $redir = "index.php"): void {
    $_SESSION['fail'] = $value;
    header("Location:" . $redir);
}

function hyphenate($str, array $noStrip = []): string {
    // non-alpha and non-numeric characters become spaces
    $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
    $str = trim($str);
    $str = str_replace(" ", "-", $str);
    $str = strtolower($str);

    return $str;
}

function rrmdir($dir): void {
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

function getNiceDateRepresentation($date): string {
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
