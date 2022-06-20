<?php
	if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль teacher.php</p>"; require $path."footer.php"; exit(); }
$projects_query = "
SELECT *
FROM catalogArchitectProject
ORDER BY project_desc
";
$projects_result = mysqli_query($conn, $projects_query) 
								or die("Помилка сервера при запиті $projects_query : ".mysqli_error($conn)); ?>
<h3 style="text-align: center; font-family: sans-serif; margin-top: 0.3em; margin-bottom: 0.1em;">
	Проєкти студентів, які беруть участь у конкурсі</h3>
<table style="width: 80%;">
	<tr>
		<th>№</th><th>Назва та посилання на проєкт</th>
	</tr><?php $place = 0;
while ($projects_row = mysqli_fetch_array($projects_result)) { $place++; ?>
	<tr>
		<th><?php echo $place; ?></th>
		<td style="text-align: left;"><?php echo $projects_row['project_desc']; ?>
			<a href="<?php echo 'studarcprojects/'.$projects_row['project_id'].'_project.jpg'; ?>" target="_blank">
					(<?php echo $projects_row['project_id']; ?>)
			</a></td>
	</tr><?php
} ?>
</table>
