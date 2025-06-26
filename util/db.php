<?php
namespace puzzlethings;

use Dotenv\Dotenv;
use Exception;
use PDO;
use PDOException;

$DEV_ENV = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false;

require 'vendor/autoload.php';
try {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
} catch (Exception $e) {
    die("Unable to load the dotenv file: " . $e->getMessage());
}

$DB_HOST = $_ENV['DB_HOST'];
$DB_NAME = $_ENV['DB_NAME'];
$DB_USER = $_ENV['DB_USER'];
$DB_PASS = $_ENV['DB_PASS'];

global $db;
try {
    $conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db = $conn;
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

