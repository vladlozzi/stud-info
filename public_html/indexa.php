<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="architect/styles.css" />
	<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<div id="wrapper"><?php
// echo "<br><h1><center>Технічні роботи, вибачте за незручності!</center></h1>";
// echo "<br><h2><center>Відновлення роботи - 09.03.2016 о 08:00</center></h2>";
ini_set("error_reporting",E_ALL); ini_set("display_errors",1); ini_set("display_startup_errors",1);
define("IN_ADMIN", TRUE); $path = "architect/";
// echo mb_internal_encoding();
mb_internal_encoding("UTF-8"); require $path."logger.php"; 
$MinistryName = "Міністерство освіти і науки України";
$UniversityName = "Івано-Франківський національний технічний університет нафти і газу";
require $path."auth.php"; require $path."header.php"; require $path."main.php"; require $path."footer.php"; ?>
</div>
</body>
</html>