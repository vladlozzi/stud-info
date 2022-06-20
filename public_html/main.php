<?php
if(!defined("IN_ADMIN")) die;
// echo "attempt... ";
$attempt_time_start = microtime(true);
$attempt_query = "select count(id) AS cid from brutLog where ip = '".$_SERVER['REMOTE_ADDR']."'";
if ($maxAttempt_row = mysqli_fetch_array(mysqli_query($conn, $attempt_query))) {
	$maxAttempt=$maxAttempt_row['cid'];
}
$attempt_time = round(microtime(true) - $attempt_time_start, 3);
// echo "$attempt_time с | ";

if (isset($_SESSION['user_role']) && ($maxAttempt<100)) {
	require "tegs.php";
	require "logout.php";
	$page .= "<form method=\"post\" target=\"_self\">";
//	echo "<form method=\"post\" target=\"_self\">";
	require "common/greeting.php";
	$results_disable = "<h2 style=\"text-align: center;\">
			Результати опитування \"Викладач очима студентів\"<br>
			будуть доступні з $ukr_date_of_results_enable</h2>";
	switch ($_SESSION['user_role']) {
		case 'ROLE_STUDENT' : 
//			$page .= "<h4 style=\"text-align: center;\">Будь ласка, візьміть участь в опитуванні про культурно-просвітницьку роботу 
//									<a href=\"https://docs.google.com/forms/d/e/1FAIpQLSflAYmrngJXzZbeh3TRQ9Zf3EOJA-n3svv71BQrshB7blx1Dw/viewform?vc=0&c=0&w=1\" 
//									target=_blank style=\"\">за цим посиланням</a></h4><br>";
			require "stud.php"; break;
		case 'ROLE_TEACHER' : 
			if (date("Y-m-d") <= $date_of_results_enable) {
				$page .= $results_disable;
			} else require "teacher.php"; break;
		case 'ROLE_ZAVKAF' : 
			if (date("Y-m-d") <= $date_of_results_enable) {
				$page .= $results_disable;
			} else require "departch.php"; break;
		case 'ROLE_DEKAN' : 
			if (date("Y-m-d") <= $date_of_results_enable) {
				$page .= $results_disable;
			} else require "dekan.php"; break;
		case 'ROLE_VICERECTOR' : 
			if (date("Y-m-d") <= $date_of_results_enable) {
				$page .= $results_disable;
			} else require "univer.php"; break;
		case 'ROLE_RECTOR' : 
			if (date("Y-m-d") <= $date_of_results_enable) {
				$page .= $results_disable;
			} else require "univer.php"; break;
		case 'ROLE_PSYCHO' : require "univer.php"; break;
		case 'ROLE_ADMIN': if ($_SESSION['user_id'] == "48") require "univer.php"; break;
	}
		//	echo "</form>";
	$page.= "</form>";

	$page_arch = "<a style=\"font-weight: bold; background-color: #8FBC8F;\" 
					href = \"./archives/20152016 весна Навчальний процес очима студентів.html\" target=_blank 
					>Результати опитування за 2015/2016 н.р. </a>".
					"<br><a style=\"font-weight: bold; background-color: #8FBC8F;\" 
					href = \"./archives/20162017 осінь Навчальний процес очима студентів.html\" target=_blank 
					>Результати опитування за 2016/2017 н.р. </a>";
	$page_arch1 = "<br><a style=\"font-weight: bold; background-color: #8FBC8F;\" 
					href = \"http://si20182019spring.nung.edu.ua\" target=_blank 
					>Результати опитування за 2018/2019 н.р. (буде відкрито у новій вкладці)</a>";
	$page_arch2 = "<br><a style=\"font-weight: bold; background-color: #8FBC8F;\" 
					href = \"http://si20192020spring.nung.edu.ua\" target=_blank 
					>Результати опитування за 2019/2020 н.р. (буде відкрито у новій вкладці)</a>";
	$page_arch3 = "<br><a style=\"font-weight: bold; background-color: #8FBC8F;\" 
					href = \"http://si20202021.nung.edu.ua\" target=_blank 
					>Результати опитування за 2020/2021 н.р. (буде відкрито у новій вкладці)</a>";
	$pages_arch = $page_arch1.$page_arch2.$page_arch3;

	switch ($_SESSION['user_role']) {
		
		case 'ROLE_RECTOR' : $page.=$page_arch.$pages_arch; break;
		case 'ROLE_VICERECTOR' : $page.=$page_arch.$pages_arch; break;
		case 'ROLE_PSYCHO' : $page.=$page_arch.$pages_arch; break;
		case 'ROLE_ADMIN'  : if ($_SESSION['user_id'] == "48") $page.=$page_arch.$pages_arch; break;
		case 'ROLE_TEACHER'  : $page.=$pages_arch; break;
		case 'ROLE_DEKAN'  : $page.=$pages_arch; break;
	}
} else {
	require "login.php";
}
?>
