<?php
if(!defined("IN_ADMIN")) die;
$php_timezn = 'Europe/Kiev'; date_default_timezone_set($php_timezn);
$mysql_timezn = (new DateTime('now', new DateTimeZone($php_timezn)))->format('P'); // echo $mysql_timezn;

$host = "localhost";
$user = "admin";
$pass = "";
$dbname = "tsupp_studinfo";
$db_name_contr = "tsupp_controwl";
$conn = mysqli_connect($host, $user, $pass) or die("try again later.");
$d_s = mysqli_select_db($conn, $dbname) or die("try again later..");
mysqli_query($conn, "SET NAMES 'utf8';") or die("try again later...");
mysqli_query($conn, "SET time_zone = '$mysql_timezn';") or die("Помилка встановлення часового поясу: ".mysqli_error($conn));

?>
