<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\Database\Database;

$db = Database::connect();
$sql = file_get_contents(__DIR__ . "/schema.sql");

$db->query($sql);

?>