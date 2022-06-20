<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль main.php</p>"; require $path."footer.php"; exit(); }
require $path."tegs.php"; // require "chekers.php"; require "functions.php"; // echo dirname(__FILE__);
if (isset($_SESSION['user_role'])) { // echo $_SESSION['user_role'];
	switch ($_SESSION['user_role']) {
		case 'ROLE_ADMIN' : $current_role = "адміністратор(-ка)"; break;
		case 'ROLE_DEP_OPER' : $current_role = "уповноважена особа організатора конкурсу"; break;
		case 'ROLE_TEACHER' : $current_role = "викладач(-ка)"; break;
		case 'ROLE_STUDENT' : $current_role = "студент(-ка)"; break;
		default : $current_role = "користувач"; break;
	}
	echo str_replace("Kiev","Kyiv",date("Y-m-d H:i:s (e P)")).
		". Ви увійшли як ".
		bold(mb_ereg_replace("проректор директор", "директор", $current_role." ".$_SESSION['user_fullname']));
?>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<a href="indexa.php?logout=1" 
	style="color: Tomato; font-weight: bold; font-family: sans-serif;">Вийти</a><br>
<p style="color: Blue; font-weight: bold; font-family: sans-serif; font-size: 80%;
	text-align: center; margin-top: 0.2em; margin-bottom: 0.2em;">
Для пошуку на сторінці натисніть Ctrl+F або скористайтеся меню браузера</p>
<form id="formDatas" method="post" target="_self">
<?php
	switch ($_SESSION['user_role'])	{
		case 'ROLE_ADMIN' : require $path."admin.php"; break;
		case 'ROLE_DEP_OPER' : require $path."admin.php"; break;
		case 'ROLE_TEACHER' : require $path."teacher.php"; break;
		case 'ROLE_STUDENT' : require $path."student.php"; break;
		default : echo "<br><h2>Для цього користувача жодних дій не передбачено</h2><br>"; break;
	}
?>
</form>
<?php
} else {
	require $path."login.php";
}
?>
