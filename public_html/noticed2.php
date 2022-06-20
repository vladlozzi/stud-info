<?php
if(!defined("IN_ADMIN")) die;
$_POST['but'] = (isset($_POST['but'])) ? $_POST['but'] : "";
$post_but = mysqli_real_escape_string($conn, trim($_POST['but']));
if(!empty($post_but)) {
	$notice_query1 = "SELECT
		`r_id`
		FROM
		`studquest_habar`
		WHERE
		`studquest_habar`.`u_id` = ".$id."
		ORDER BY
		`studquest_habar`.`r_id`, `studquest_habar`.`studAnswer`
		";
	if($notice_result1=mysqli_query($conn, $notice_query1)) {
		while($notice_row1 = mysqli_fetch_array($notice_result1)) {
			$RTMP = "R".$notice_row1['r_id'];
			$RBUF = mysqli_real_escape_string($conn, trim($_POST[$RTMP]));
			if(!empty($RBUF)) {
				$quer = "UPDATE 
					`".$db_name_contr."`.`testHabar`
					SET 
					answerId = ".$RBUF.",
					answerDate = '".date("Y-n-d G:i:s")."',
					answerIpAddress = '".$_SERVER['REMOTE_ADDR']."'
					WHERE
					id = ".$notice_row1['r_id']."
					";//echo $quer;
				mysqli_query($conn, $quer);// or die(mysqli_error());
			}
		}
	}
}
$notice_query = "SELECT
		distinct *
		FROM
		`studquest_habar`
		WHERE
		`studquest_habar`.`u_id` = ".$id."
		ORDER BY
		`studquest_habar`.`r_id`, `studquest_habar`.`comment` DESC
		";
$cur_id = 0; $noticeTMPQuest = ""; $noticeBodyRow = "";
//echo "<br>$notice_query<br>";
if($notice_result=mysqli_query($conn, $notice_query)) {
	if(mysqli_num_rows($notice_result)>1) {
		while($notice_row = mysqli_fetch_array($notice_result)) {
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
		$noticeBodyPart .= tableDigitWrapper($noticeTMPQuest, 'style="width: 150px;"');
		$noticeBodyRow .= tableRowWrapper($noticeBodyPart);
		$noticeBody = "<form method=\"post\" target=\"_self\">".tableWrapper($noticeBodyRow)."<input type=\"submit\" name=\"but\" value=\"Відправити дані\"></form>";
		$notice = $noticeHead.$noticeBody;
	}
}
?>
