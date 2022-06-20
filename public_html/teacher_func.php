<?php
if(!defined("IN_ADMIN")) die;
function teacher_rating($teacher_id, &$page, &$ankets_count, 
			&$chapter1_rating, &$chapter2_rating, &$total_rating) { global $conn, $db_name_contr, $semester, $acadYear;
// функція "Рейтинг викладача серед студентів"
//echo $teacher_id;
//-------------------------TABLE_HEADER--------------------------------------------------------
	$table1Header = tableRowWrapper(tableHeaderWrapper(" № ").
					tableHeaderWrapper("Критерій").
					tableHeaderWrapper("Сума балів").
					tableHeaderWrapper("Середній бал").
					tableHeaderWrapper("Частка балів, %")
				);
//--------------------------TABLE_ROWS-------------------------------------------------------------
	$filled_ankets_query = "
	SELECT DISTINCT 
	userId 
	FROM 
	".$db_name_contr.".testHabar 
	WHERE 
	comment = $teacher_id AND answerId > 0";
	$ankets_count = 0;
	if ($filled_ankets_result = mysqli_query($conn, $filled_ankets_query)) {
	    while($filled_ankets_row = mysqli_fetch_array($filled_ankets_result)) $ankets_count++;
	}
	$page .= "Кількість анкет: <b>".$ankets_count."</b>";
	if ($ankets_count == 0) {
		$page .= "<br><center><b>".mb_strtoupper(mb_substr($semester, 0, 1)).mb_substr($semester, 1).
						 " $acadYear н.р. оцінки не поставив ще жоден студент.</b></center>";
//		exit;
	} else {
		$page .= "<br><center><b>Оцінки викладачеві за результатами опитування студентів<br>Розділ перший
				</b></center>
			";

		$chapter1_bals_query = "
			SELECT a.userId AS ank, b.comment AS bals, c.id as qId, c.question, c.titleId
			FROM ".$db_name_contr.".testHabar a, ".$db_name_contr.".questionAnswers b, 
			".$db_name_contr.".questionTests c
			WHERE a.comment = $teacher_id AND b.Id = a.answerId 
					AND a.testsId = c.id AND c.titleId = 1 /* AND CONVERT(b.comment, SIGNED) > -2 */ 
			ORDER BY c.id
		";
		$chapter1_bals_result = mysqli_query($conn, $chapter1_bals_query)
				or die("Помилка сервера в модулі teacher_func.php при запиті chapter1_bals_query: ".
					mysqli_error($conn) );
		/* Формуємо двовимірний масив з результатами запиту*/
		$ch1b_array = array(); 
		$ch1b_rows=0;
		while ($ch1b_row = mysqli_fetch_array($chapter1_bals_result)) {
			$ch1b_array[$ch1b_rows] = $ch1b_row; $ch1b_rows++;
		}

		$chapter2_bals_query = "
			SELECT a.userId AS ank, b.comment AS bals, c.id as qId, c.question, c.titleId
			FROM ".$db_name_contr.".testHabar a, ".$db_name_contr.".questionAnswers b, ".$db_name_contr.".questionTests c
			WHERE a.comment = $teacher_id AND b.Id = a.answerId 
					AND a.testsId = c.id AND c.titleId = 2 /* AND CONVERT(b.comment, SIGNED) > -2 */ 
			ORDER BY c.id
		";
		$chapter2_bals_result = mysqli_query($conn, $chapter2_bals_query)
				or die("Помилка сервера в модулі teacher_func.php при запиті chapter2_bals_query: ".
					mysqli_error($conn) );
		/* Формуємо двовимірний масив з результатами запиту */
		$ch2b_array = array(); 
		$ch2b_rows=0;
		while ($ch2b_row = mysqli_fetch_array($chapter2_bals_result)) {
			$ch2b_array[$ch2b_rows] = $ch2b_row; $ch2b_rows++;
		}

//		echo " ** chapter2_bals_query: OK - ".$ch2b_rows." **" ;

		$table1Rows = "";
		$chapter1_balsum = 0;
		$chapter1_questions = 0;
		$chapter1_answers = 0;
		$qT1_query = "SELECT * FROM ".$db_name_contr.".questionTests WHERE titleId = 1 ORDER BY id";
// echo $qTF_query." ; ";
		$qT1_result = mysqli_query($conn, $qT1_query)
				or die("Помилка сервера при запиті qT1_query: ".mysqli_error($conn) );
// echo $qTF_result;
		while ($qT1_row = mysqli_fetch_array($qT1_result)) {
			$questionNumber = $qT1_row['id'];
			$questionText = $qT1_row['question'];
//	Сума балів за критерієм $questionNumber для викладача
			$q_balsum = 0; $q_answers = 0; // echo "<br>";
			for ($bals_row = 0; $bals_row < $ch1b_rows; $bals_row++)
				if ($ch1b_array[$bals_row]['qId'] == $questionNumber) {
//					echo "*".$ch1b_array[$bals_row]['qId']." ".$bals_row."*"; 
					$q_balsum += ($ch1b_array[$bals_row]['bals'] > -2) ? $ch1b_array[$bals_row]['bals'] : 0;
					$q_answers += ($ch1b_array[$bals_row]['bals'] > -2) ? 1 : 0;
				}
			$table1Rows .= tableRowWrapper(
				tableDigitWrapper("<center>".$questionNumber."</center>").
				tableDigitWrapper($questionText).
				tableDigitWrapper("<center>".(($q_answers > 0) ? $q_balsum : "-")."</center>").
				tableDigitWrapper("<center>".(($q_answers > 0) ? round(100*$q_balsum/$q_answers,0)/100 : "-")."</center>").
				tableDigitWrapper("<center>".(($q_answers > 0) ? round($q_balsum*100/(5*$q_answers),1) : "-")."</center>")
			);
			$chapter1_balsum += $q_balsum;
			$chapter1_answers += $q_answers;
			$chapter1_questions++;
		}
		if ($chapter1_answers > 0)
			$chapter1_rating = round($chapter1_balsum*100/(5*$chapter1_answers),1);
		else $chapter1_rating = "-";
		$table1Rows .= tableRowWrapper(
				tableDigitWrapper("").
				tableDigitWrapper("<b><center>Разом</center></b>").
				tableDigitWrapper("<b><center>".$chapter1_balsum."</center></b>").
				tableDigitWrapper("<b><center>".
					(($chapter1_answers > 0) ? round(100*$chapter1_balsum/($chapter1_answers),0)/100 : "-").
					"</center></b>").
				tableDigitWrapper("<b><center>".$chapter1_rating."</center></b>")
			);
		$page .= tableWrapper($table1Header.$table1Rows)."<br>";
		$page .= "<center><b>Розділ другий</b></center>";

		$table2Header = tableRowWrapper(
				tableHeaderWrapper(" № ").
				tableHeaderWrapper("Критерій").
				tableHeaderWrapper("Кількість відповідей \"Так\"").
				tableHeaderWrapper("Частка відповідей \"Так\"").
				tableHeaderWrapper("Частка балів, %")
				);
		$table2Rows = "";
		$chapter2_balsum = 0;
		$chapter2_rbalsum = 0;
		$chapter2_questions = 0;
		$chapter2_answers = 0;
		$chapter2_rating = 0;
		$qT2_query = "SELECT * FROM ".$db_name_contr.".questionTests WHERE titleId = 2 ORDER BY id";
// echo $qTF_query." ; ";
		$qT2_result = mysqli_query($conn, $qT2_query)
				or die("Помилка сервера при запиті qT2_query: ".mysqli_error($conn) );
// echo $qTF_result;
		while ($qT2_row = mysqli_fetch_array($qT2_result)) {
			$questionNumber = $qT2_row['id'];
			$questionText = str_replace("<br>"," ",$qT2_row['question']);
//	Сума балів за критерієм $questionNumber для викладача
			$q_balsum = 0; $r_balsum = 0; $q_answers = 0;
			for ($bals_row = 0; $bals_row < $ch2b_rows; $bals_row++)
				if ($ch2b_array[$bals_row]['qId'] == $questionNumber) {
//					echo "*".$ch1b_array[$bals_row]['qId']." ".$bals_row."*";
					$q_balsum += ($ch2b_array[$bals_row]['bals'] > -2) ? (($questionNumber < 12) ? $ch2b_array[$bals_row]['bals'] : -$ch2b_array[$bals_row]['bals']) : 0;
					$r_balsum += ($ch2b_array[$bals_row]['bals'] > -2) ? $ch2b_array[$bals_row]['bals'] : 0;
					$q_answers += ($ch2b_array[$bals_row]['bals'] > -2) ? 1 : 0;
				}
			if ($q_answers > 0)
				$q_rating = ($r_balsum >= 0 and $questionNumber < 12) ?
					round($q_balsum*100/$q_answers,1) :
					100+round($r_balsum*100/$q_answers,1);
			else $q_rating = "-";
			$table2Rows .= tableRowWrapper(
					tableDigitWrapper("<center>".$questionNumber."</center>").
					tableDigitWrapper($questionText).
					tableDigitWrapper("<center>".(($q_answers > 0) ? $q_balsum : "-")."</center>").
					tableDigitWrapper("<center>".(($q_answers > 0) ? round($q_balsum/$q_answers,3) : "-")."</center>").
					tableDigitWrapper("<center>".$q_rating."</center>")
				);
			$chapter2_balsum += $q_balsum; $chapter2_rbalsum += (is_numeric($q_rating) ? $q_rating : 0) * $q_answers / 100;
//			$chapter2_rating += (is_numeric($q_rating) ? $q_rating : 0);
			$chapter2_questions++;
			$chapter2_answers += $q_answers;
		}
//		$chapter2_rating = round($chapter2_balsum / $chapter2_answers, 1);
		if ($chapter2_answers > 0)
			$chapter2_rating = round($chapter2_rbalsum*100/($chapter2_answers),1);
		else $chapter2_rating = "-";
		$table2Rows .= tableRowWrapper(
				tableDigitWrapper("").
				tableDigitWrapper("<b><center>Разом</center></b>").
				tableDigitWrapper("<b><center>".$chapter2_balsum."</center></b>").
				tableDigitWrapper("<b><center>".
					(($chapter2_answers > 0) ? round(100*$chapter2_balsum/($chapter2_answers),1)/100 : "-").
					"</center></b>").
				tableDigitWrapper("<b><center>".$chapter2_rating."</center></b>")
			);

		$page .= tableWrapper($table2Header.$table2Rows)."<br>";
		if ($chapter1_answers == 0 and $chapter2_answers == 0) $total_rating = "не визначено";
		else $total_rating = round((
				(($chapter1_answers > 0) ? $chapter1_rating : 0) * $chapter1_answers + 
				(($chapter2_answers > 0) ? $chapter2_rating : 0) * $chapter2_answers
			) / ($chapter1_answers + $chapter2_answers), 1);

		$page .= "<center><b>Сумарний показник - ".$total_rating."</b></center>";
	}
//	echo " ".$chapter1_rating." ".$chapter2_rating." ".$total_rating;
} // end of function teacher_rating
