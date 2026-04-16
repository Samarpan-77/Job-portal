<?php
require_once __DIR__ . '/../app/config/database.php';

$db = Database::connect();
echo "Database Connected Successfully!";
