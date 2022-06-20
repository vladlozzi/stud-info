<?php
if(!defined("IN_ADMIN")) die;
$post_but = mysql_real_escape_string(trim($_POST['but']));
if(!empty($post_but)) {
	$notice_query1 = "SELECT
		`r_id`
		FROM
		`studQuest`
		WHERE
		`studQuest`.`u_id` = ".$id."
		ORDER BY
		`studQuest`.`r_id`, `studQuest`.`studAnswer`
		";
	if($notice_result1=mysql_query($notice_query1)) {
		while($notice_row1 = mysql_fetch_array($notice_result1)) {
			$RTMP = "R".$notice_row1['r_id'];
			$RBUF = mysql_real_escape_string(trim($_POST[$RTMP]));
			if(!empty($RBUF)) {
				$quer = "UPDATE 
					`tsupp_controwl`.`questionResults` 
					SET 
					answerId = ".$RBUF.",
					answerDate = '".date("Y-n-d G:i:s")."',
					answerIpAddress = '".$_SERVER['REMOTE_ADDR']."'
					WHERE
					id = ".$notice_row1['r_id']."
					";
				mysql_query($quer);/// or die(mysql_error());
			}
		}
	}
}
$notice_query = "SELECT
		distinct *
		FROM
		`studQuest`
		WHERE
		`studQuest`.`u_id` = ".$id."
		ORDER BY
		`studQuest`.`r_id`, `studQuest`.`studAnswer`
		";
if($notice_result=mysql_query($notice_query)) {
	if(mysql_num_rows($notice_result)>1) {
		while($notice_row = mysql_fetch_array($notice_result)) {
			$noticeHead = centerWrap(bold($notice_row['title']));

			$noticeQuestion = $notice_row['question'];
			$noticeAId = $notice_row['a_id'];
			$noticeAnswer = $notice_row['studAnswer'];
			$noticeRId = $notice_row['r_id'];
			//if cecord id was changed
			if($cur_id != $noticeRId) {
				$cur_id = $noticeRId;
				$noticeTeacher = $notice_row['t_name'];
				//first i must end the previous record
				if(!empty($noticeBodyPart)) {
					$noticeBodyPart .= tableDigitWrapper($noticeTMPQuest);
					$noticeBodyRow .= tableRowWrapper($noticeBodyPart);
					$noticeTMPQuest = null;
				}
				//then i must to write next record
				$noticeBodyPart = tableDigitWrapper($noticeTeacher).tableDigitWrapper($noticeQuestion);
				//need to write first answer;
				$noticeTMPQuest .= radioWrap("R".$cur_id, $noticeAId, $noticeAnswer);
					
			} else {
				$noticeTMPQuest .= radioWrap("R".$cur_id, $noticeAId, $noticeAnswer);
			}
		}
		//need to write ending of questions
		$noticeBodyPart .= tableDigitWrapper($noticeTMPQuest);
		$noticeBodyRow .= tableRowWrapper($noticeBodyPart);
		$noticeBody = "<form method=\"post\" target=\"_self\">".tableWrapper($noticeBodyRow)."<input type=\"submit\" name=\"but\" value=\"Відправити дані\"></form>";
		$notice = $noticeHead.$noticeBody;
	}
}
?>
