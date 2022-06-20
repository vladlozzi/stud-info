<?php
if(!defined("IN_ADMIN")) die;
function univer_rating(&$page, &$ankets_count, &$chapter1_rating, &$chapter2_rating, &$total_rating) {
	global $conn, $db_name_contr;
$_POST['radMode'] = (isset($_POST['radMode'])) ? $_POST['radMode'] : "Pivot";
//-------------------------TABLE_HEADER-------------------------------------------------------
	$tableHeader1 = 
				tableRowWrapper(tableHeaderWrapper(" № ", "rowspan=2").
					tableHeaderWrapper("Інститут", "rowspan=2").
					tableHeaderWrapper("Опитано&nbsp;студентів:<br>кількість / %<br>від контингенту", "rowspan=2").
					tableHeaderWrapper("Кількість<br>заповнених<br>анкет", "rowspan=2").
					tableHeaderWrapper("Частка&nbsp;балів,&nbsp;яку&nbsp;набрали&nbsp;викладачі,&nbsp;%<br>(максимум - 100%)", "colspan=3")
				).
				tableRowWrapper(tableHeaderWrapper("Критерії<br>№&nbsp;1-8").
					tableHeaderWrapper("Критерії<br>№&nbsp;9-13").
					tableHeaderWrapper("Загалом")
				);
	$tableHeader2 = 
				tableRowWrapper(
					tableHeaderWrapper("Інститут", "rowspan=2").
					tableHeaderWrapper("Частка&nbsp;балів,&nbsp;які&nbsp;набрали&nbsp;викладачі&nbsp;за&nbsp;критеріями,&nbsp;%<br>(максимум - 100%)", "colspan=12")
				).
				tableRowWrapper(
					tableHeaderWrapper("№&nbsp;1").tableHeaderWrapper("№&nbsp;2").tableHeaderWrapper("№&nbsp;3").tableHeaderWrapper("№&nbsp;4").
					tableHeaderWrapper("№&nbsp;5").tableHeaderWrapper("№&nbsp;6").tableHeaderWrapper("№&nbsp;7").tableHeaderWrapper("№&nbsp;8").
					tableHeaderWrapper("№&nbsp;9").tableHeaderWrapper("№&nbsp;10").tableHeaderWrapper("№&nbsp;11").tableHeaderWrapper("№&nbsp;12")
				);
	$page .= "<h3><center>Результати опитування \"Викладач очима студентів\"</center></h3>";
	$page .= "<center><input type='radio' name='radMode' value='Pivot' onclick='submit()' "
						.(($_POST['radMode'] == "Pivot") ? "checked" : "")
						.">&nbsp;За інститутами (зведена) &nbsp; &nbsp; &nbsp; 
										<input type='radio' name='radMode' value='Set' onclick='submit()' "
						.(($_POST['radMode'] == "Set") ? "checked" : "")
						.">&nbsp;За інститутами та окремими критеріями
						</center>";
	$faculties_query = "
	SELECT id, fakultet_name 
	FROM ".$db_name_contr.".catalogFakultet 
	WHERE id < 14 OR id = 16
	ORDER BY fakultet_name
	";
	$faculties_result = mysqli_query($conn, $faculties_query) 
				or die("Помилка сервера при запиті faculties_query : ".mysqli_error($conn));
	switch ($_POST['radMode']) {
		case 'Pivot' :
			mysqli_query($conn, "DROP TABLE IF EXISTS studTestHabar") 
				or die("Помилка сервера при запиті DROP TABLE IF EXISTS studTestHabar : ".mysqli_error($conn));

			mysqli_query($conn, "
				CREATE TABLE studTestHabar
				SELECT DISTINCT userId
				FROM ".$db_name_contr.".testHabar
				WHERE answerId>0
				") or die("Помилка сервера при запиті CREATE TABLE studTestHabar : ".mysqli_error($conn));
			$faculties_count = 0;
			$tableRows = "";
			$qstuds_count = 0;
			$gstuds_count = 0;
	 	$ankets_count = 0;
	$chapter1_rating = 0;
	$chapter2_rating = 0;
	$total_rating = 0;
	while ($faculties_row = mysqli_fetch_array($faculties_result)) {
		$faculties_count ++;
		$faculty_name = $faculties_row['fakultet_name'];
		$faculty_id =   $faculties_row['id'];
		$faculty_time_start = microtime(true);
//		echo $faculty_name."... ";
//		Кількість опитаних студентів інституту
		$qstuds_query = "
		SELECT COUNT(*) AS qs_count 
		FROM ".$db_name_contr.".catalogGroup a, ".$db_name_contr.".catalogStudent b, studTestHabar c 
		WHERE b.group_link = a.id AND b.id=c.userId AND a.shufr_fak = $faculty_id 
		";
		$qstuds_result = mysqli_query($conn, $qstuds_query) 
				or die("Помилка сервера при запиті qstuds_query : ".mysqli_error($conn));
		$qstuds_row = mysqli_fetch_array($qstuds_result);
		
//		Загальна кількість студентів інституту, яких слід опитати (бакалаврат денної форми навчання)
		$gstuds_query = "
			SELECT COUNT(*) AS gs_count 
			FROM ".$db_name_contr.".catalogGroup, ".$db_name_contr.".catalogWorkEduPlan, 
				".$db_name_contr.".catalogEduForm, 
				".$db_name_contr.".catalogEduDegree, ".$db_name_contr.".catalogStudent
			WHERE 
				".$db_name_contr.".catalogGroup.plan_id = ".$db_name_contr.".catalogWorkEduPlan.id AND 
				".$db_name_contr.".catalogWorkEduPlan.edu_form_id = ".$db_name_contr.".catalogEduForm.id AND 
				".$db_name_contr.".catalogEduForm.edu_form = 'денна' AND 
				".$db_name_contr.".catalogWorkEduPlan.edu_degree_id = ".$db_name_contr.".catalogEduDegree.id AND /* 
				".$db_name_contr.".catalogEduDegree.degree_name = 'бакалавр' AND */ 
				".$db_name_contr.".catalogStudent.group_link = ".$db_name_contr.".catalogGroup.id AND 
				".$db_name_contr.".catalogGroup.shufr_fak = ".$faculty_id
		;
		$gstuds_result = mysqli_query($conn, $gstuds_query) 
				or die("Помилка сервера при запиті gstuds_query : ".mysqli_error($conn));
		$gstuds_row = mysqli_fetch_array($gstuds_result);
	
		$faculty_page = "";
		$faculty_qstuds_count = empty($qstuds_row['qs_count']) ? 0 : $qstuds_row['qs_count'];
		$faculty_gstuds_count = empty($gstuds_row['gs_count']) ? 0 : $gstuds_row['gs_count'];
		$faculty_qstuds_percent = ($faculty_gstuds_count > 0) ? 
					round($faculty_qstuds_count / $faculty_gstuds_count * 100,  1) : 0;
		$faculty_ankets_count = 0;
		$faculty_chapter1_rating = 0;
		$faculty_chapter2_rating = 0;
		$faculty_total_rating = 0;
		faculty_rating($faculty_id, $faculty_page, $faculty_ankets_count, 
				$faculty_chapter1_rating, $faculty_chapter2_rating, $faculty_total_rating);

		$tableRows .= tableRowWrapper
					(tableDigitWrapper("<center>".$faculties_count."</center>").
						tableDigitWrapper($faculty_name).
						tableDigitWrapper("<center>".$faculty_qstuds_count." / ".
												$faculty_qstuds_percent."% від&nbsp;".$faculty_gstuds_count."</center>").
						tableDigitWrapper("<center>".$faculty_ankets_count."</center>").
						tableDigitWrapper("<center>".$faculty_chapter1_rating."</center>").
						tableDigitWrapper("<center>".$faculty_chapter2_rating."</center>").
						tableDigitWrapper("<center>".$faculty_total_rating."</center>")
					).tableRowWrapper(
						tableDigitWrapper("").
						tableDigitWrapper("<details>$faculty_page</details>","colspan=6")
					);
		$qstuds_count += $faculty_qstuds_count;
		$gstuds_count += $faculty_gstuds_count;
		$ankets_count += $faculty_ankets_count;
		$chapter1_rating += $faculty_chapter1_rating * $faculty_ankets_count;
		$chapter2_rating += $faculty_chapter2_rating * $faculty_ankets_count;
		$total_rating += $faculty_total_rating * $faculty_ankets_count;
		$faculty_time_end = microtime(true);
		$faculty_time = round($faculty_time_end - $faculty_time_start, 3);
//		echo "$faculty_time с | ";
	}

	if ($ankets_count>0) {
		$chapter1_rating = round($chapter1_rating / $ankets_count, 1);
		$chapter2_rating = round($chapter2_rating / $ankets_count, 1);
		$total_rating = round($total_rating / $ankets_count, 1);
	} else { $chapter1_rating = 0; $chapter2_rating = 0; $total_rating = 0; }
	$qstuds_percent = ($gstuds_count > 0) ? round($qstuds_count / $gstuds_count * 100,  1) : 0;

	$tableFooter1 = tableRowWrapper(tableFooterWrapper("").
					tableFooterWrapper("По університету").
					tableFooterWrapper($qstuds_count."&nbsp;/&nbsp;".$qstuds_percent."%&nbsp;від&nbsp;".$gstuds_count).
					tableFooterWrapper($ankets_count).
					tableFooterWrapper($chapter1_rating).
					tableFooterWrapper($chapter2_rating).
					tableFooterWrapper($total_rating)
				);
			$page .= tableWrapper($tableHeader1.$tableRows.$tableFooter1);
			mysqli_query($conn, "DROP TABLE IF EXISTS studTestHabar") 
				or die("Помилка сервера при запиті DROP TABLE studTestHabar : ".mysqli_error($conn));
			break;
		case 'Set' :
			$tableRows = "";
			while ($faculties_row = mysqli_fetch_array($faculties_result)) {
				$tableRow = tableDigitWrapper($faculties_row['fakultet_name']);
				// Вибираємо усіх викладачів інституту
				$facultyTeachers_query = "
					SELECT a.id 
					FROM ".$db_name_contr.".catalogTeacher a, ".$db_name_contr.".catalogDepartment b 
					WHERE a.kaf_link = b.id AND b.fakultet_id = ".$faculties_row['id'];
				for ($iCr = 0; $iCr < 8; $iCr++) {
					$iQu = $iCr + 1; $maxFacultyPoints = 0; $facultyPoints = 0;
					$facultyTeachers_result = mysqli_query($conn, $facultyTeachers_query) 
						or die("Помилка сервера при запиті $facultyTeachers_query : ".mysqli_error($conn));
					while ($facultyTeachers_row = mysqli_fetch_array($facultyTeachers_result)) {
						// Кількість опитаних студентів і максимальна сума балів викладача
						$teacherStuds_query = "
							SELECT DISTINCT userId
							FROM ".$db_name_contr.".testHabar a, ".$db_name_contr.".questionAnswers b 
							WHERE a.comment = ".$facultyTeachers_row['id']." AND a.testsId = ".$iQu." AND a.answerId > 0 
										AND b.comment > -2 AND a.answerId = b.id
						";
						$teacherStuds_result = mysqli_query($conn, $teacherStuds_query) 
							or die("Помилка сервера при запиті $teacherStuds_query : ".mysqli_error($conn));
						$maxTeacherPoints = 5 * mysqli_num_rows($teacherStuds_result);
						// if ($facultyTeachers_row['id'] == 127) echo $iQu." ".$facultyTeachers_row['id']." ".mysqli_num_rows($teacherStuds_result);
						// Набрана сума балів викладача
						$teacherPoints_query = "
							SELECT SUM(b.comment) AS teacherPoints
							FROM ".$db_name_contr.".testHabar a, ".$db_name_contr.".questionAnswers b 
							WHERE a.comment = ".$facultyTeachers_row['id']." AND a.testsId = ".$iQu." 
										AND a.answerId > 0 AND b.comment > -2 AND a.answerId = b.id
						";
						$teacherPoints_result = mysqli_query($conn, $teacherPoints_query) 
							or die("Помилка сервера при запиті $teacherStuds_query : ".mysqli_error($conn));
						$teacherPoints_row = mysqli_fetch_array($teacherPoints_result);
						$facultyPoints += $teacherPoints_row['teacherPoints'];
						$maxFacultyPoints += $maxTeacherPoints;
					}
					$partPoints = ($maxFacultyPoints > 0) ? round($facultyPoints / $maxFacultyPoints * 100, 0) : "";
					$tableRow .= tableDigitWrapper(centerWrap($partPoints));
				}
				for ($iCr = 8; $iCr < 12; $iCr++) {
					$iQu = $iCr + 1; $maxFacultyPoints = 0; $facultyPoints = 0;
					$facultyTeachers_result = mysqli_query($conn, $facultyTeachers_query) 
						or die("Помилка сервера при запиті $facultyTeachers_query : ".mysqli_error($conn));
					while ($facultyTeachers_row = mysqli_fetch_array($facultyTeachers_result)) {
						// Кількість опитаних студентів і максимальна сума балів викладача
						$teacherStuds_query = "
							SELECT DISTINCT userId
							FROM ".$db_name_contr.".testHabar a, ".$db_name_contr.".questionAnswers b 
							WHERE a.comment = ".$facultyTeachers_row['id']." AND a.testsId = ".$iQu." AND a.answerId > 0 
										AND b.comment > -2 AND a.answerId = b.id
						";
						$teacherStuds_result = mysqli_query($conn, $teacherStuds_query) 
							or die("Помилка сервера при запиті $teacherStuds_query : ".mysqli_error($conn));
						$maxTeacherPoints = mysqli_num_rows($teacherStuds_result);
						// if ($facultyTeachers_row['id'] == 127) echo $iQu." ".$facultyTeachers_row['id']." ".mysqli_num_rows($teacherStuds_result);
						// Набрана сума балів викладача
						$teacherPoints_query = "
							SELECT SUM(b.comment) AS teacherPoints
							FROM ".$db_name_contr.".testHabar a, ".$db_name_contr.".questionAnswers b 
							WHERE a.comment = ".$facultyTeachers_row['id']." AND a.testsId = ".$iQu." 
										AND a.answerId > 0 AND b.comment > -2 AND a.answerId = b.id
						";
						$teacherPoints_result = mysqli_query($conn, $teacherPoints_query) 
							or die("Помилка сервера при запиті $teacherStuds_query : ".mysqli_error($conn));
						$teacherPoints_row = mysqli_fetch_array($teacherPoints_result);
						$facultyPoints += $teacherPoints_row['teacherPoints'];
						$maxFacultyPoints += $maxTeacherPoints;
					}
					$partPoints = ($maxFacultyPoints > 0) ? round($facultyPoints / $maxFacultyPoints * 100, 0) : "";
					$partPoints = ($iQu == 12 and $maxFacultyPoints > 0) ? 100 + $partPoints : $partPoints;
					$tableRow .= tableDigitWrapper(centerWrap($partPoints));
				}
				$tableRows .= tableRowWrapper($tableRow);
			}
			$page .= tableWrapper($tableHeader2.$tableRows);
			break;
	}
}
?>