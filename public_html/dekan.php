<?php
if(!defined("IN_ADMIN")) die;
if(empty($notice)) {
	require "dekan_func.php"; // функція "Рейтинг інституту серед студентів"
	require "departch_func.php"; // функція "Рейтинг кафедри серед студентів"
	require "teacher_func.php"; // функція "Рейтинг викладача серед студентів"
	$faculty_id = $_SESSION['user_description'];
	$faculty_ankets_count = 0;
	$faculty_chapter1_rating = 0;
	$faculty_chapter2_rating = 0;
	$faculty_total_rating = 0;
	faculty_rating($faculty_id, $page, $faculty_ankets_count,
			$faculty_chapter1_rating, $faculty_chapter2_rating, $faculty_total_rating);
} 
else {
	$page .= $notice;
}
?>
