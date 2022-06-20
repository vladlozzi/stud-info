<?php
if(!defined("IN_ADMIN")) die;
function depart_rating($depart_id, &$page, &$depart_ankets_count,
			&$depart_chapter1_rating, &$depart_chapter2_rating, &$depart_total_rating
		) { global $conn, $db_name_contr;
//-------------------------TABLE_HEADER--------------------------------------------------------
	$tableHeader = 
				tableRowWrapper(tableHeaderWrapper(" № ", "rowspan=2").
					tableHeaderWrapper("Викладач", "rowspan=2").
					tableHeaderWrapper("Кількість<br>заповнених<br>анкет", "rowspan=2").
					tableHeaderWrapper("Частка&nbsp;балів,&nbsp;яку&nbsp;набрав&nbsp;викладач,&nbsp;%<br>(максимум - 100%)", "colspan=3")
				).
				tableRowWrapper(tableHeaderWrapper("Критерії<br>№&nbsp;1-8").
					tableHeaderWrapper("Критерії<br>№&nbsp;9-13").
					tableHeaderWrapper("Загалом")
				);
	$page .= "<h3><center>Результати опитування \"Викладач очима студентів\"</center></h3>";
//	$page .= tableWrapper($tableHeader);
	$teachers_query = "
	SELECT id, fullname FROM studAuth 
	WHERE userDescription = $depart_id AND role = 'ROLE_TEACHER' 
	ORDER BY fullname
	";
	$teachers_result = mysqli_query($conn, $teachers_query) 
				or die("Помилка сервера при запиті teachers_query : ".mysqli_error($conn));
	$teachers_count = 0;
	$tableRows = "";
	$depart_ankets_count = 0;
	$depart_chapter1_rating = 0;
	$depart_chapter2_rating = 0;
	$depart_total_rating = 0;
	while ($teachers_row = mysqli_fetch_array($teachers_result)) {
		$teachers_count ++;
		$teacher_name = $teachers_row['fullname'];
		$teacher_id =   $teachers_row['id'];
		$teacher_page = "";
		$teacher_ankets_count = 0;
		$teacher_chapter1_rating = '-';
		$teacher_chapter2_rating = '-';
		$teacher_total_rating = '-';
		teacher_rating($teacher_id, $teacher_page, $teacher_ankets_count, 
				$teacher_chapter1_rating, $teacher_chapter2_rating, $teacher_total_rating);
		$tp = $_SESSION['user_role']=='ROLE_ZAVKAF' ? "" : "<details>$teacher_page</details>";
		$tableRows .= 
					tableRowWrapper(
						tableDigitWrapper("<center>".$teachers_count."</center>").
						tableDigitWrapper($teacher_name).
						tableDigitWrapper("<center>".$teacher_ankets_count."</center>").
						tableDigitWrapper("<center>".$teacher_chapter1_rating."</center>").
						tableDigitWrapper("<center>".$teacher_chapter2_rating."</center>").
						tableDigitWrapper("<center>".$teacher_total_rating."</center>")
					).
					tableRowWrapper(
						tableDigitWrapper("").tableDigitWrapper($tp,"colspan=5")
					);
		
		$depart_ankets_count += $teacher_ankets_count;
		$depart_chapter1_rating += (is_numeric($teacher_chapter1_rating) ? $teacher_chapter1_rating : 0) * $teacher_ankets_count;
		$depart_chapter2_rating += (is_numeric($teacher_chapter2_rating) ? $teacher_chapter2_rating : 0) * $teacher_ankets_count;
		$depart_total_rating += (is_numeric($teacher_total_rating) ? $teacher_total_rating : 0) * $teacher_ankets_count;;
	}
	if ($depart_ankets_count>0)	{
		$depart_chapter1_rating = round($depart_chapter1_rating / $depart_ankets_count, 1);
		$depart_chapter2_rating = round($depart_chapter2_rating / $depart_ankets_count, 1);
		$depart_total_rating = round($depart_total_rating / $depart_ankets_count, 1);
	} else { $depart_chapter1_rating = 0; $depart_chapter2_rating = 0; $depart_total_rating = 0; }

	$tableFooter = tableRowWrapper(tableFooterWrapper("").
					tableFooterWrapper("По кафедрі").
					tableFooterWrapper($depart_ankets_count).
					tableFooterWrapper($depart_chapter1_rating).
					tableFooterWrapper($depart_chapter2_rating).
					tableFooterWrapper($depart_total_rating)
				);
	$page .= tableWrapper($tableHeader.$tableRows.$tableFooter);
} 
?>
