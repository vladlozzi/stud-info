<?php
if(!defined("IN_ADMIN")) die;
	$id=$_SESSION['user_id'];
	$group=$_SESSION['user_description'];
////added 14_05_2012
$notice = "";
if (date("Y-m-d") >= $dateOfPollStart and date("Y-m-d") <= $dateOfPollFinish) {
	require "noticed2.php";
}
// if ($id == 25529) require "noticed2.php";
if (!isset($tableRow)) $tableRow = "";

$noticeThanks = '<p><br><h3>Дякуємо Вам за оцінки викладачам!<br>Оцінки, які викладачі поставили Вам, доступні на сайті '.
	'<a href="https://dekanat.nung.edu.ua" target="_blank" >за цим посиланням</a><br>в "Особистому кабінеті студента".</h3>'.
	'Посилання буде відкрито в новій вкладці.</p>';

if (empty($notice)) {
	if (empty($noticeThanks)) {
////added 14_05_2012
//-------------------------TABLE_HEADER--------------------------------------------------------
		$tableHeader = tableRowWrapper(tableHeaderWrapper("Дисципліна").
					tableHeaderWrapper("Викладач").
					tableHeaderWrapper(tableAbbr("Міжсесійний контроль", "П")).
					tableHeaderWrapper(tableAbbr("Модуль 0", "М0")).
					tableHeaderWrapper(tableAbbr("Модуль 1", "М1")).
					tableHeaderWrapper(tableAbbr("Модуль 2", "М2")).
					tableHeaderWrapper(tableAbbr("Модуль 3", "М3")).
					tableHeaderWrapper(tableAbbr("Модуль 4", "М4")).
					tableHeaderWrapper(tableAbbr("Модуль 5", "М5")).
					tableHeaderWrapper(tableAbbr("Модуль 6", "М6")).
					tableHeaderWrapper(tableAbbr("Модуль 7", "М7")).
					tableHeaderWrapper(tableAbbr("Модуль 8", "М8")).
					tableHeaderWrapper(tableAbbr("Проміжна оцінка за модулями", "ПОМ")).
					tableHeaderWrapper(tableAbbr("Екзаменаційна оцінка", "ЕО")).
					tableHeaderWrapper(tableAbbr("Семестрова оцінка", "СО"))
					);
//--------------------------TABLE_ROWS-------------------------------------------------------------
		$diff_subject_query="
		SELECT
		`stud_progress`.*
		FROM
		`stud_progress`
		WHERE
		`stud_progress`.`group_link` = '".$_SESSION['user_description']."'";
		$index = 0;
		if($diff_subject_result=mysqli_query($conn, $diff_subject_query)) {
			while($diff_subject_row = mysqli_fetch_array($diff_subject_result)) {
				if($diff_subject_row["student_name"] == $_SESSION['user_id'] || !($diff_subject_row["student_name"])) {
					$bufer[$index] = $diff_subject_row;
					$subjects[$index] = $diff_subject_row["subject_link"];
					$teacher[$index] = $diff_subject_row["teacher_link"];
					$stud[$index] = $diff_subject_row["student_name"];
					$good[$index] = TRUE;
					$index++;
					if($diff_subject_row["nazva_grupu"])
						$s_group = $diff_subject_row["nazva_grupu"];
				}
			}
		}
		$studGroup = "";
		$group_query = "
		SELECT `nazva_grupu` FROM `tsupp_controwl`.`catalogGroup` WHERE `id` = '".$_SESSION['user_description']."'";
		if($group_result = mysqli_query($conn, $group_query)) {
			while($group_row = mysqli_fetch_array($group_result)) {
				$studGroup = $group_row["nazva_grupu"];
			}
		}
		$page .= "Група: ".$studGroup /* ." ('.$_SESSION['user_description'].")" */ ;
//we need to check for duplicated rows
		for($i = 0; $i < $index; $i++) {
//	if (isset($teacher[$i]))
			$t = $teacher[$i];	//first teacher
//	else
//		$t = 0;
			$s = $subjects[$i];	//first subject
			for($j = $i + 1; $j < $index; $j++) {	//for next record
				if (($t == $teacher[$j]) && ($s == $subjects[$j]) && !($stud[$j]) ) { //the same teacher subject and no student name seted
					$good[$j] = FALSE;						//we need to delete this record
				}
			}
		}
//now we need ro display data
//======================================
//temporary add function form checkers.php
function markChecker($current, $maximum) {
	if(empty($current)) {
		return "-";
	}
	$mark=$current/$maximum;
	if($mark>=0.9) {
		return "5";
	}
	if($mark>=0.75) {
		return "4";
	}
	if($mark>=0.6) {
		return "3";
	}
	return "2";
}

function isGoodMark($mark) {
	if(empty($mark))
		return "-";
	else
		return $mark;
}

//======================================
function formater($mark, $maximum) {
	return addSpan(bold(isGoodMark($mark))."/".bold($maximum)."/".markChecker($mark, $maximum));
}
//-----
function absentFormater($cur, $max) {
	return addSpan(isGoodMark($cur)."/".isGoodMark($max));//."<font size = 1>".date("d.m.y")."</font>";
}

function weekFormater($cur) {
	return fontS($cur, 1);
}
//======================================
	for($i = 0; $i < $index; $i++) {
		if ($good[$i] == FALSE)
			continue;
		$stud_data_row = $bufer[$i];
		$NA = "<font size = 5>-</font>"; // student don't study this
		if($stud_data_row["show_p"]) {
			if (!isset($stud_data_row["pAbsent"]))
				$sdrpA = 0;
			else
				$sdrpA = $stud_data_row["pAbsent"];
		if (!isset($stud_data_row["pfAbsent"]))
                        $sdrpfA = 0;
                else
                        $sdrpfA = $stud_data_row["pfAbsent"];
                if (!isset($stud_data_row["p"]))
                        $sdrp = 0;
                else
                        $sdrp = $stud_data_row["p"];
		$p = formater($sdrp, $stud_data_row["p_max"]).absentFormater($sdrpA, $sdrpfA).weekFormater($stud_data_row["pW"]);
	} else $p = $NA;
	if($stud_data_row["show_m0"]) {
               if (!isset($stud_data_row["m0Absent"]))
                        $sdrm0A = 0;
                else
                        $sdrm0A = $stud_data_row["m0Absent"];
                if (!isset($stud_data_row["m0fAbsent"]))
                        $sdrm0fA = 0;
                else
                        $sdrm0fA = $stud_data_row["m0fAbsent"];
                if (!isset($stud_data_row["module0"]))
                        $sdrm0 = 0;
                else
                        $sdrm0 = $stud_data_row["module0"];
		$m0 = formater($sdrm0, $stud_data_row["m0_max"]).absentFormater($sdrm0A, $sdrm0fA).weekFormater($stud_data_row["m0W"]);
	} else $m0 = $NA;
        if($stud_data_row["show_m1"]) {
               if (!isset($stud_data_row["m1Absent"]))
                        $sdrm1A = 0;
                else
                        $sdrm1A = $stud_data_row["m1Absent"];
                if (!isset($stud_data_row["m1fAbsent"]))
                        $sdrm1fA = 0;
                else
                        $sdrm1fA = $stud_data_row["m1fAbsent"];
                if (!isset($stud_data_row["module1"]))
                        $sdrm1 = 0;
                else
                        $sdrm1 = $stud_data_row["module1"];
                $m1 = formater($sdrm1, $stud_data_row["m1_max"]).absentFormater($sdrm1A, $sdrm1fA).weekFormater($stud_data_row["m1W"]);
        } else $m1 = $NA;
        if($stud_data_row["show_m2"]) {
               if (!isset($stud_data_row["m2Absent"]))
                        $sdrm2A = 0;
                else
                        $sdrm2A = $stud_data_row["m2Absent"];
                if (!isset($stud_data_row["m2fAbsent"]))
                        $sdrm2fA = 0;
                else
                        $sdrm2fA = $stud_data_row["m2fAbsent"];
                if (!isset($stud_data_row["module2"]))
                        $sdrm2 = 0;
                else
                        $sdrm2 = $stud_data_row["module2"];
                $m2 = formater($sdrm2, $stud_data_row["m2_max"]).absentFormater($sdrm2A, $sdrm2fA).weekFormater($stud_data_row["m2W"]);
        } else $m2 = $NA;
        if($stud_data_row["show_m3"]) {
               if (!isset($stud_data_row["m3Absent"]))
                        $sdrm3A = 0;
                else
                        $sdrm3A = $stud_data_row["m3Absent"];
                if (!isset($stud_data_row["m3fAbsent"]))
                        $sdrm3fA = 0;
                else
                        $sdrm3fA = $stud_data_row["m3fAbsent"];
                if (!isset($stud_data_row["module3"]))
                        $sdrm3 = 0;
                else
                        $sdrm3 = $stud_data_row["module3"];
                $m3 = formater($sdrm3, $stud_data_row["m3_max"]).absentFormater($sdrm3A, $sdrm3fA).weekFormater($stud_data_row["m3W"]);
        } else $m3 = $NA;
        if($stud_data_row["show_m4"]) {
               if (!isset($stud_data_row["m4Absent"]))
                        $sdrm4A = 0;
                else
                        $sdrm4A = $stud_data_row["m4Absent"];
                if (!isset($stud_data_row["m4fAbsent"]))
                        $sdrm4fA = 0;
                else
                        $sdrm4fA = $stud_data_row["m4fAbsent"];
                if (!isset($stud_data_row["module4"]))
                        $sdrm4 = 0;
                else
                        $sdrm4 = $stud_data_row["module4"];
                $m4 = formater($sdrm4, $stud_data_row["m4_max"]).absentFormater($sdrm4A, $sdrm4fA).weekFormater($stud_data_row["m4W"]);
        } else $m4 = $NA;
        if($stud_data_row["show_m5"]) {
               if (!isset($stud_data_row["m5Absent"]))
                        $sdrm5A = 0;
                else
                        $sdrm5A = $stud_data_row["m5Absent"];
                if (!isset($stud_data_row["m5fAbsent"]))
                        $sdrm5fA = 0;
                else
                        $sdrm5fA = $stud_data_row["m5fAbsent"];
                if (!isset($stud_data_row["module5"]))
                        $sdrm5 = 0;
                else
                        $sdrm5 = $stud_data_row["module5"];
                $m5 = formater($sdrm5, $stud_data_row["m5_max"]).absentFormater($sdrm5A, $sdrm5fA).weekFormater($stud_data_row["m5W"]);
        } else $m5 = $NA;
        if($stud_data_row["show_m6"]) {
               if (!isset($stud_data_row["m6Absent"]))
                        $sdrm6A = 0;
                else
                        $sdrm6A = $stud_data_row["m6Absent"];
                if (!isset($stud_data_row["m6fAbsent"]))
                        $sdrm6fA = 0;
                else
                        $sdrm6fA = $stud_data_row["m6fAbsent"];
                if (!isset($stud_data_row["module6"]))
                        $sdrm6 = 0;
                else
                        $sdrm6 = $stud_data_row["module6"];
                $m6 = formater($sdrm6, $stud_data_row["m6_max"]).absentFormater($sdrm6A, $sdrm6fA).weekFormater($stud_data_row["m6W"]);
        } else $m6 = $NA;
        if($stud_data_row["show_m7"]) {
               if (!isset($stud_data_row["m7Absent"]))
                        $sdrm7A = 0;
                else
                        $sdrm7A = $stud_data_row["m7Absent"];
                if (!isset($stud_data_row["m7fAbsent"]))
                        $sdrm7fA = 0;
                else
                        $sdrm7fA = $stud_data_row["m7fAbsent"];
                if (!isset($stud_data_row["module7"]))
                        $sdrm7 = 0;
                else
                        $sdrm7 = $stud_data_row["module7"];
                $m7 = formater($sdrm7, $stud_data_row["m7_max"]).absentFormater($sdrm7A, $sdrm7fA).weekFormater($stud_data_row["m7W"]);
        } else $m7 = $NA;
        if($stud_data_row["show_m8"]) {
               if (!isset($stud_data_row["m8Absent"]))
                        $sdrm8A = 0;
                else
                        $sdrm8A = $stud_data_row["m8Absent"];
                if (!isset($stud_data_row["m8fAbsent"]))
                        $sdrm8fA = 0;
                else
                        $sdrm8fA = $stud_data_row["m8fAbsent"];
                if (!isset($stud_data_row["module8"]))
                        $sdrm8 = 0;
                else
                        $sdrm8 = $stud_data_row["module8"];
                $m8 = formater($sdrm8, $stud_data_row["m8_max"]).absentFormater($sdrm8A, $sdrm8fA).weekFormater($stud_data_row["m8W"]);
        } else $m8 = $NA;

//	if($stud_data_row["show_m1"]) {$m1 = formater($stud_data_row["module1"], $stud_data_row["m1_max"]).absentFormater($stud_data_row["m1Absent"], $stud_data_row["m1fAbsen"]).weekFormater($stud_data_row["m1W"]);} else $m1 = $NA;
//	if($stud_data_row["show_m2"]) {$m2 = formater($stud_data_row["module2"], $stud_data_row["m2_max"]).absentFormater($stud_data_row["m2Absent"], $stud_data_row["m2fAbsen"]).weekFormater($stud_data_row["m2W"]);} else $m2 = $NA;
//	if($stud_data_row["show_m3"]) {$m3 = formater($stud_data_row["module3"], $stud_data_row["m3_max"]).absentFormater($stud_data_row["m3Absent"], $stud_data_row["m3fAbsen"]).weekFormater($stud_data_row["m3W"]);} else $m3 = $NA;
//	if($stud_data_row["show_m4"]) {$m4 = formater($stud_data_row["module4"], $stud_data_row["m4_max"]).absentFormater($stud_data_row["m4Absent"], $stud_data_row["m4fAbsen"]).weekFormater($stud_data_row["m4W"]);} else $m4 = $NA;
//	if($stud_data_row["show_m5"]) {$m5 = formater($stud_data_row["module5"], $stud_data_row["m5_max"]).absentFormater($stud_data_row["m5Absent"], $stud_data_row["m5fAbsen"]).weekFormater($stud_data_row["m5W"]);} else $m5 = $NA;
//	if($stud_data_row["show_m6"]) {$m6 = formater($stud_data_row["module6"], $stud_data_row["m6_max"]).absentFormater($stud_data_row["m6Absent"], $stud_data_row["m6fAbsen"]).weekFormater($stud_data_row["m6W"]);} else $m6 = $NA;
//	if($stud_data_row["show_m7"]) {$m7 = formater($stud_data_row["module7"], $stud_data_row["m7_max"]).absentFormater($stud_data_row["m7Absent"], $stud_data_row["m7fAbsen"]).weekFormater($stud_data_row["m7W"]);} else $m7 = $NA;
//	if($stud_data_row["show_m8"]) {$m8 = formater($stud_data_row["module8"], $stud_data_row["m8_max"]).absentFormater($stud_data_row["m8Absent"], $stud_data_row["m8fAbsen"]).weekFormater($stud_data_row["m8W"]);} else $m8 = $NA;
	$pom = formater($stud_data_row["pom"], "100").weekFormater($stud_data_row["pomW"]);
	if($stud_data_row["show_eo"]) {$eo = formater($stud_data_row["eo"], "100").weekFormater($stud_data_row["eoW"]);} else $eo = $NA;
	$so = formater($stud_data_row["so"], "100").weekFormater($stud_data_row["soW"]);
	if(!isset($tableRow)) $tableRow = "";
	$tableRow .= tableRowWrapper(
					tableDigitWrapper($stud_data_row["s_name"]).
					tableDigitWrapper($stud_data_row["t_name"]).
					tableDigitWrapper($p, "class=\"rating\"").
					tableDigitWrapper($m0, "class=\"rating\"").
					tableDigitWrapper($m1, "class=\"rating\"").
					tableDigitWrapper($m2, "class=\"rating\"").
					tableDigitWrapper($m3, "class=\"rating\"").
					tableDigitWrapper($m4, "class=\"rating\"").
					tableDigitWrapper($m5, "class=\"rating\"").
					tableDigitWrapper($m6, "class=\"rating\"").
					tableDigitWrapper($m7, "class=\"rating\"").
					tableDigitWrapper($m8, "class=\"rating\"").
					tableDigitWrapper($pom, "class=\"rating\"").
					tableDigitWrapper($eo, "class=\"rating\"").
					tableDigitWrapper($so, "class=\"rating\"")
					);
}
$page .= tableWrapper($tableHeader.$tableRow);
$page .= "
<div id=\"legend\">
<p>
	Умовні скорочення:&nbsp;П &ndash; міжсесійний контроль;&nbsp;М &ndash; модуль (М1 - модуль №1;&nbsp;М2 - модуль&nbsp;№2&nbsp;і т. д.);&nbsp;ПОМ &ndash; підсумкова оцінка за модулями;&nbsp;ЕО &ndash; екзаменаційна оцінка;&nbsp;СО &ndash; семестрова оцінка.</p>
<table>
	<tr>
		<td>
			16/22/4<hr>
			10/8<hr>
			03.04.12
			</td>
		<td> 16 - набрана сума балів / 22 - максимум можливих балів / 4 - оцінка в 5 бальній системі<hr> 10 - пропущено занять(год.) / 8 - з них невідпрацьованих(год)<hr>дата контролю </td>
	</tr>
</table>
</div>";
// echo $page;
// added 14_05_2012
	} else $page .= $noticeThanks;
} else {
	$page .= $notice;
}
////added 14_05_2012
?>