<?php
if(!defined("IN_ADMIN")) die;
if(empty($notice)) {
	require "departch_func.php"; // функція "Рейтинг кафедри серед студентів"
	require "teacher_func.php"; // функція "Рейтинг викладача серед студентів"
	$depart_id = $_SESSION['user_description'];
	$depart_ankets_count = 0;
	$depart_chapter1_rating = 0;
	$depart_chapter2_rating = 0;
	$depart_total_rating = 0;
	depart_rating($depart_id, $page, $depart_ankets_count,
			$depart_chapter1_rating, $depart_chapter2_rating, $depart_total_rating);
} 
else {
	$page .= $notice;
}
?>
