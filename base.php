<?php
session_start();
ob_start();

$dbhost = "localhost";
$dbname = "kpi_system";
$dbuser = "root";
$dbpass = "";

$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$link) {
    die("MySQL Error: " . mysqli_error($link));
}

// show all errors except notice and warning
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);

// for arabic content
mysqli_query($link, "SET NAMES utf8");
?>