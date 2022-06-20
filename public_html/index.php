<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script>
    var interval;
    var minutes = 0;
    var seconds = 5;
    window.onload = function() {
        countdown('countdown');
    }

    function countdown(element) {
        interval = setInterval(function() {
            var el = document.getElementById(element);
            if(seconds == 0) {
                if(minutes == 0) {
                    window.location = "http://lmgtfy.com/?q=why+am+I+here+%3F"
                    clearInterval(interval);
                    return;
                } else {
                    minutes--;
                    seconds = 60;
                }
            }
            if(minutes > 0) {
                var minute_text = minutes + (minutes > 1 ? ' minutes' : ' minute');
            } else {
                var minute_text = '';
            }
            var second_text = seconds > 1 ? 'seconds' : 'second';
            el.innerHTML = '<h1>Something is about to happen in <br>' + minute_text + ' ' + seconds + ' ' + second_text + ' </h1>';
            seconds--;
        }, 1000);
    }
    </script>
	<title>Викладач очима студентів (2021/2022 н.р.) :: ІФНТУНГ</title>
<head>
<body>
<center><img id="wait" src="../images/please-wait-text.gif" 
		onload='document.getElementById("wait").style.display = "none"' /></center>
<div id="wrapper">
<?php

ini_set("error_reporting",E_ALL);ini_set("display_errors",1);ini_set("display_startup_errors",1);
ini_set("session.gc_maxlifetime", 86400); ini_set("session.save_path","./sessions");

// echo "<br><h1><center>Оновлюємо базу даних системи <br>до нового семестру</center></h1>";
// echo "<h2><center>Відновлення роботи - після того, як деканати сформують бланки відомостей успішності</center></h2>";
// echo "<br><h6><center>Результати анонімного опитування за весняний семестр 2015/2016н.р. - <br>у практичного психолога</center></h6>";
// exit();

define("IN_ADMIN", TRUE); $acadYear = "2021/2022"; $semester = "в осінньому семестрі";
$dateOfPollStart = "2021-11-23"; // Дата початку опитування
$date_of_results_enable = "2021-12-21"; // доступ до результатів опитування з цієї дати
$ukr_date_of_results_enable = date("d.m.Y", strtotime($date_of_results_enable));
$dateOfPollFinish = "2022-07-21"; // Кінцева дата опитування

require "logger.php";

//echo "auth... ";
$auth_time_start = microtime(true);
require "auth.php";
require "auth_google.php";
$auth_time = round(microtime(true) - $auth_time_start,3);
//echo "$auth_time с | ";

//echo "header... ";
$header_time_start = microtime(true);
require "header.php";
$header_time = round(microtime(true) - $header_time_start,3);
//echo "$header_time с | ";

$page .= "<div id=\"content-wrapper\">";
		//require "error.php"; //if need
//		$page.="<br><h1><center>in progress</center></h1>";
//		echo "<br><h2><center>Час включення - понеділок</center><h2>";

//echo "main... ";
$main_time_start = microtime(true);
require "main.php";
$main_time = round(microtime(true) - $main_time_start,3);
// echo "$main_time с | ";
//	require "timer.php";
$page .= "</div>";
require "footer.php";
echo $page;
?>
</div>
</body>
</html>
