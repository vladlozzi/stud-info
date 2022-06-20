<?php
if(!defined("IN_ADMIN")) die;
if(empty($notice)) {
//	echo "Зачекайте... &nbsp; &nbsp; &nbsp; &nbsp;";
	require "univer_func.php"; // функція "Рейтинг викладачів університету серед студентів"
	require "dekan_func.php"; // функція "Рейтинг викладачів інституту серед студентів"
	require "departch_func.php"; // функція "Рейтинг викладачів кафедри серед студентів"
	require "teacher_func.php"; // функція "Рейтинг одного викладача серед студентів"
	$ankets_count = 0;
	$chapter1_rating = 0;
	$chapter2_rating = 0;
	$total_rating = 0;
	univer_rating($page, $ankets_count, $chapter1_rating, $chapter2_rating, $total_rating);
//	echo "Готово!<br>";
} 
else {
	$page .= $notice;
}
?>
