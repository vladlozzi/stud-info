<?php
if(!defined("IN_ADMIN")) die;
if (empty($notice)) {
	require "teacher_func.php";
	$teacher_id = $_SESSION['user_id'];
	$ankets_count = 0;
	$chapter1_rating = '-';
	$chapter2_rating = '-';
	$total_rating = '-';
	teacher_rating($teacher_id, $page, $ankets_count, 
			$chapter1_rating, $chapter2_rating, $total_rating);
}
else {
	$page .= $notice;
}
?>
