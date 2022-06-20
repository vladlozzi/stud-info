<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль auth.php</p>"; require $path."footer.php"; exit(); }
require $path."dbu.php";
session_start();
$_GET['logout'] = isset($_GET['logout']) ? $_GET['logout'] : 0;
if(mysqli_real_escape_string($conn, $_GET['logout'])==1) {
//	session_unset();
	session_destroy(); header ("Location: indexa.php");
}
if(!empty($_POST['enter'])) {
        $_SESSION['login'] = trim(mysqli_real_escape_string($conn, $_POST['login']));
        $_SESSION['psswd'] = trim(mysqli_real_escape_string($conn, $_POST['psswd']));  
}
if(isset($_SESSION['login']) && isset($_SESSION['psswd'])) {
	$login = $_SESSION['login']; $psswd = $_SESSION['psswd'];

	$query = "SELECT `id`, `role`, `fullname`, `userDescription` ".
                 "      FROM `tsupp_controwl`.`userAuthArchitect` ".
                 "      WHERE `login`='{$login}' AND `psswd`='{$psswd}' ".
                 "      LIMIT 1";
//        echo $query; die;
	$sql = mysqli_query($conn, $query)
               or die("Помилка запиту на авторизацію: ".mysqli_error($conn));
	if (mysqli_num_rows($sql) == 1) {
        	$row = mysqli_fetch_assoc($sql);
	        $_SESSION['user_id'] = $row['id'];
		$_SESSION['user_role'] = $row['role'];
        	$_SESSION['user_fullname'] = $row['fullname'];
		$_SESSION['user_description'] = $row['userDescription'];
		logData($row['id'], $row['role'], '0', 'logged');
	}
} else {
	$login = "";
	$psswd = "";
}
?>
