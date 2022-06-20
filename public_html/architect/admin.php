<?php
	if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль admin.php</p>"; require $path."footer.php"; exit(); }
	$votes_query = "
	SELECT
	a.project_desc, b.project_id, SUM(b.rating_id) AS votes
	FROM catalogArchitectProject a, testArchitect b
	WHERE b.project_id = a.project_id
	GROUP BY b.project_id ORDER BY votes DESC
	";
	$voters_query = "
	SELECT
	a.fullname, a.userDescription
	FROM userAuthArchitect a, testArchitect b
	WHERE b.voter_id = a.id AND b.rating_id > 0
	ORDER BY a.userDescription, a.fullname
	";
	$votes_result = mysqli_query($conn, $votes_query) 
								or die("Помилка сервера при запиті $votes_query : ".mysqli_error($conn));
	$voters_result = mysqli_query($conn, $voters_query) 
								or die("Помилка сервера при запиті $voters_query : ".mysqli_error($conn)); ?>
<h3 style="text-align: center; font-family: sans-serif; margin-top: 0.3em; margin-bottom: 0.1em;">
	Результати голосування станом на <?php $CurDate = date("d.m.Y H:i"); echo $CurDate;  ?></h3><?php 
if ($_SESSION['user_role'] == "ROLE_ADMIN") { ?>
<details style="text-align: center;"><summary style="font-family: sans-serif;">Показати</summary><?php
} ?>
<table style="width: 80%;">
	<tr>
		<th>Місце</th><th style="width: 1000px;">Назва та посилання<br>на проєкт</th>
		<th>Кількість<br>голосів "ЗА"</th>
	</tr><?php $place = 0; $votes = 0;
	while ($votes_row = mysqli_fetch_array($votes_result)) { $place++; ?>
	<tr>
		<th><?php echo $place; ?></th>
		<td style="width: 1000px; text-align: left;"><?php echo $votes_row['project_desc']; ?>
			<a href="<?php echo 'studarcprojects/'.$votes_row['project_id'].'_project.jpg'; ?>" target="_blank">
					(<?php echo $votes_row['project_id']; ?>)
			</a></td>
		<td style="text-align: сenter; vertical-align: middle;"><?php echo $votes_row['votes']; ?></td>
		</tr><?php $votes += $votes_row['votes'];
	} ?>
<tr><th style="text-align: right;" colspan=2>Загальна кількість голосів &nbsp;</th><th><?php echo $votes; ?></th></tr>
</table><?php
if ($_SESSION['user_role'] == "ROLE_ADMIN") { ?>
</details><?php
} ?>
<h3 style="text-align: center; font-family: sans-serif; margin-top: 0.6em; margin-bottom: 0.1em;">
	Особи, які проголосували станом на <?php echo $CurDate; ?></h3>
<table style="width: 45%;">
	<tr>
		<th>№</th><th style="width: 450px;">Прізвище, імʼя, по батькові</th><th>Шифр групи</th>
	</tr><?php $voters = 0;
	while ($voters_row = mysqli_fetch_array($voters_result)) { $voters++; ?>
	<tr>
			<td><?php echo $voters; ?></td><td style="text-align: left;"><?php echo $voters_row['fullname']; ?></td>
			<td><?php echo $voters_row['userDescription']; ?></td>
	 </tr><?php
	} ?>
</table>
