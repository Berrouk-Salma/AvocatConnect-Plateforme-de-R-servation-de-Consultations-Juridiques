<?php
session_start();
$host = 'localhost';
$user = 'root';  // MySQL username
$password = '';   // MySQL password
$database = 'lawfirm_db'; // Database name from your SQL file

// Create connection
$mysqli = new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to utf8
$mysqli->set_charset("utf8");
?>