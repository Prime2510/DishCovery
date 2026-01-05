<?php
// db.php
// Edit these values to match your Department of Computer Science MySQL account
define('DB_HOST', 'localhost');
define('DB_USER', 'dpb428');        
define('DB_PASS', 'Prime#2504');        
define('DB_NAME', 'dpb428');        

// Create mysqli connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    error_log("DB connect error: " . $mysqli->connect_error);
    die("Database connection failed.");
}
$mysqli->set_charset("utf8mb4");
