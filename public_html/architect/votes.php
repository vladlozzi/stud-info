<?php
//if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
//                               "Помилка входу в модуль dbu.php</p>"; require "footer.php"; exit(); }
$host = "localhost";
$user = "tsupp_maindb";
$pass = "ybyja9a8e";
$dbname = "tsupp_controwl";
$conn = mysqli_connect($host, $user, $pass)
	or die("Помилка входу на сервер БД ".$host.": ".mysqli_connect_error());
$d_s = mysqli_select_db($conn, $dbname)
	or die("Помилка вибору бази даних: ".mysqli_error($conn));
mysqli_query($conn, "SET NAMES 'utf8';")
	or die("Помилка встановлення кодування UTF-8: ".mysqli_error($conn));
$_GET['id'] = (isset($_GET['id'])) ? $_GET['id'] : "";
if (!empty($_GET['id'])) {
	$votes_query = "SELECT SUM(rating_id) AS votes FROM testArchitect WHERE project_id = ".$_GET['id'];
	$votes_result = mysqli_query($conn, $votes_query) 
								or die("Помилка сервера при запиті $votes_query : ".mysqli_error($conn));
	$votes_row = mysqli_fetch_array($votes_result);
	echo $votes_row['votes'];
} else echo "id проекту не задано";
?>
