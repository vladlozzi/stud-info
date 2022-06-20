<?php
if (!defined("IN_ADMIN")) die;
// echo "dbu... ";
$dbu_time_start = microtime(true);
require "dbu.php";
$dbu_time = round(microtime(true) - $dbu_time_start,3);
// echo "$dbu_time с | ";
// echo "starting session... ";
$s_start_time_start = microtime(true);
session_start();
$s_start_time = round(microtime(true) - $s_start_time_start,3);
// echo "$s_start_time с | ";
if (!empty($_GET['logout'])) {
//  echo "<script>document.getElementById(\"wait\").style.display = \"inline\";</script>";
	session_destroy();
	header ("Location: /");
}
if (!empty($_POST['enter'])) {
	$s_login_time_start = microtime(true);
	
	if (strpos($_POST['login'], "@")) {
		echo '<script>window.alert("Логін, який Ви ввели, ймовірно, є адресою email. Спробуйте увійти через пошту ІФНТУНГ за посиланням під кнопкою Вхід");</script>';
		echo "<p style=\"font-size: 120%; font-weight: bold; color: red; text-align: center;\">
			Логін, який Ви ввели, ймовірно, є адресою email. Спробуйте увійти через пошту ІФНТУНГ за посиланням під кнопкою \"Вхід\"</p>";
	}
	$_SESSION['login'] = mysqli_real_escape_string($conn, $_POST['login']);
	$_SESSION['psswd'] = mysqli_real_escape_string($conn, $_POST['psswd']);
	$s_login_time = round(microtime(true) - $s_login_time_start,3);
//	echo "saving login to session... ";
//	echo "[".$_SESSION['login']."] [".$_SESSION['psswd']."]";
	// echo "$s_login_time с | ";

}
//===============================================================================
function escape_inj ($text) {
  $text = strtolower($text); // Приравниваем текст параметра к нижнему регистру
  if (
    !strpos($text, "select") && // 
    !strpos($text, "union") && //
    !strpos($text, "select") && //
    !strpos($text, "order") && // Ищем вхождение слов в параметре
    !strpos($text, "where") && // 
    !strpos($text, "char") && //
    !strpos($text, "from") //
  ) {
    return true; // Вхождений нету - возвращаем true
  } else {
    return false; // Вхождения есть - возвращаем false
  }
}
//================================================================================
if(isset($_SESSION['login']) && isset($_SESSION['psswd'])) {
//	echo "checking login... ";
	$checking_login_time_start = microtime(true);
	
	$login = $_SESSION['login'];
	$psswd = $_SESSION['psswd'];
	if(escape_inj($login) && escape_inj($psswd)) {
		$auth_query =  "SELECT `id`, `role`, `fullname`, `userDescription` 
				FROM `studAuth` 
				WHERE `login`='".md5($login)."' AND `psswd`='".md5($psswd)."' LIMIT 1";
//		echo $auth_query;
		$auth_result = mysqli_query($conn, $auth_query) or 
		    die("Помилка сервера при запиті auth_query: ".mysqli_error($conn));
		if (mysqli_num_rows($auth_result) == 1) {
			$auth_row = mysqli_fetch_assoc($auth_result);
			$_SESSION['user_id'] = $auth_row['id'];
			$_SESSION['user_role'] = $auth_row['role'];
			$_SESSION['user_fullname'] = $auth_row['fullname'];
			$_SESSION['user_description'] = $auth_row['userDescription'];
			logData($auth_row['id'], $auth_row['role'], '0', 'logged[===]'.$login.'[===]' /* .$psswd */);
		} else {
				if (!strpos($_POST['login'], "@")) {
					logData(0, 'brut', '0', $login.'[===]' /* .$psswd */);
				}
				echo '<script>document.getElementById("wait").style.display = "none"</script>';
		}
	} else {
		logData(0, 'AXTUNG!', '0', $login.'[===]' /* .$psswd */);
		echo '<script>document.getElementById("wait").style.display = "none"</script>';
	}
	$checking_login_time = round(microtime(true) - $checking_login_time_start,3);
//	echo "$checking_login_time с | ";
} else { $login = ""; $psswd = ""; }
?>