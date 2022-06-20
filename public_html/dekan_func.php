<?php
if(!defined("IN_ADMIN")) die;
function faculty_rating($faculty_id, &$page, &$faculty_ankets_count,
			&$faculty_chapter1_rating, &$faculty_chapter2_rating, &$faculty_total_rating
		    ) { global $conn, $db_name_contr;
//-------------------------TABLE_HEADER--------------------------------------------------------
	$tableHeader = 
				tableRowWrapper(tableHeaderWrapper(" № ", "rowspan=2").
					tableHeaderWrapper("Кафедра", "rowspan=2").
					tableHeaderWrapper("Кількість<br>заповнених<br>анкет", "rowspan=2").
					tableHeaderWrapper("Частка&nbsp;балів,&nbsp;яку&nbsp;набрали&nbsp;викладачі,&nbsp;%<br>(максимум - 100%)", "colspan=3")
				).
				tableRowWrapper(tableHeaderWrapper("Критерії<br>№&nbsp;1-8").
					tableHeaderWrapper("Критерії<br>№&nbsp;9-13").
					tableHeaderWrapper("Загалом")
				);
	$page .= "<h3><center>Результати опитування \"Викладач очима студентів\"</center></h3>";
	$departs_query = "
	SELECT id, nazva_kaf 
	FROM ".$db_name_contr.".catalogDepartment
	WHERE fakultet_id = $faculty_id
	ORDER BY nazva_kaf
	";
	$departs_result = mysqli_query($conn, $departs_query) 
				or die("Помилка сервера при запиті departs_query : ".mysqli_error($conn));

	$departs_count = 0;
	$tableRows = "";
	$faculty_ankets_count = 0;
	$faculty_chapter1_rating = 0;
	$faculty_chapter2_rating = 0;
	$faculty_total_rating = 0;
	while ($departs_row = mysqli_fetch_array($departs_result))
	{
		$departs_count ++;
		$depart_name = $departs_row['nazva_kaf'];
		$depart_id =   $departs_row['id'];
		$depart_page = "";
		$depart_ankets_count = 0;
		$depart_chapter1_rating = 0;
		$depart_chapter2_rating = 0;
		$depart_total_rating = 0;
		depart_rating($depart_id, $depart_page, $depart_ankets_count, 
				$depart_chapter1_rating, $depart_chapter2_rating, $depart_total_rating);
		$tableRows .= 
						tableRowWrapper(
							tableDigitWrapper("<center>".$departs_count."</center>").
							tableDigitWrapper($depart_name).
							tableDigitWrapper("<center>".$depart_ankets_count."</center>").
							tableDigitWrapper("<center>".$depart_chapter1_rating."</center>").
							tableDigitWrapper("<center>".$depart_chapter2_rating."</center>").
							tableDigitWrapper("<center>".$depart_total_rating."</center>")
						).
						tableRowWrapper(
							tableDigitWrapper("").
							tableDigitWrapper("<details>$depart_page</details>", "colspan=5")

						);
		$faculty_ankets_count += $depart_ankets_count;
		$faculty_chapter1_rating += $depart_chapter1_rating * $depart_ankets_count;
		$faculty_chapter2_rating += $depart_chapter2_rating * $depart_ankets_count;
		$faculty_total_rating += $depart_total_rating * $depart_ankets_count;
	}
	if ($faculty_ankets_count > 0)
	{
	$faculty_chapter1_rating = round($faculty_chapter1_rating / $faculty_ankets_count, 1);
	$faculty_chapter2_rating = round($faculty_chapter2_rating / $faculty_ankets_count, 1);
	$faculty_total_rating = round($faculty_total_rating / $faculty_ankets_count, 1);
	} else 
	{ $faculty_chapter1_rating = 0; $faculty_chapter2_rating = 0; $faculty_total_rating = 0; }

	$tableFooter = tableRowWrapper(tableFooterWrapper("").
					tableFooterWrapper("По інституту").
					tableFooterWrapper($faculty_ankets_count).
					tableFooterWrapper($faculty_chapter1_rating).
					tableFooterWrapper($faculty_chapter2_rating).
					tableFooterWrapper($faculty_total_rating)
				);
	$page .= tableWrapper($tableHeader.$tableRows.$tableFooter);
} 
?>
