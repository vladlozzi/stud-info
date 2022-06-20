<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль student.php</p>"; require $path."footer.php"; exit(); }
$poll_query = "SELECT * FROM testArchitect WHERE voter_id = ".$_SESSION['user_id'];
$poll_result = mysqli_query($conn, $poll_query) 
								or die("Помилка сервера при запиті $poll_query : ".mysqli_error($conn));
if (mysqli_num_rows($poll_result) == 0) { ?>
<h2 style="text-align: center; font-family: sans-serif; margin-top: 1em; margin-bottom: 1em;">
	Голосування за найкращий архітектурний проект студентів ІФНТУНГ наразі недоступне!</h2>
<?php return;
} 
while ($poll_row = mysqli_fetch_array($poll_result)) {
	$_POST['chk'.$poll_row['id']] = isset($_POST['chk'.$poll_row['id']]) ? $_POST['chk'.$poll_row['id']] : "";
	if (!empty($_POST['chk'.$poll_row['id']])) {
		$poll_update1_query = "UPDATE testArchitect SET rating_id = 1 WHERE id = ".$poll_row['id'];
		$poll_update2_query = "UPDATE testArchitect SET rating_id = 0 WHERE voter_id = ".$_SESSION['user_id']." 
																	AND id != ".$poll_row['id'];
		$poll_update1_result = mysqli_query($conn, $poll_update1_query) 
								or die("Помилка сервера при запиті $poll_update1_query : ".mysqli_error($conn));
		$poll_update2_result = mysqli_query($conn, $poll_update2_query) 
								or die("Помилка сервера при запиті $poll_update2_query : ".mysqli_error($conn));
	}
} ?>
<h3 style="text-align: center; font-family: sans-serif; margin-top: 0.3em; margin-bottom: 0.1em;">
	Підтримайте архітектурний проєкт, який Вам до вподоби</h3>
<table>
	<tr><th>Посилання на проєкт</th><th style="width: 130px;"></th></tr><?php
$poll_result = mysqli_query($conn, $poll_query) 
								or die("Помилка сервера при запиті $poll_query : ".mysqli_error($conn));
while ($poll_row = mysqli_fetch_array($poll_result)) { ?>
	<tr>
		<td>
		<!-- <img width="50" height="70" src="<?php echo 'studarcprojects/'.$poll_row['project_id'].'_project.jpg'; ?>"> -->
			<br> 
			<a href="<?php echo 'studarcprojects/'.$poll_row['project_id'].'_project.jpg'; ?>" target="_blank">
					<?php echo $poll_row['project_id']; ?>
			</a><br><br></td>
		<td style="text-align: left; vertical-align: middle;">
			<input type="checkbox" name="chk<?php echo $poll_row['id']; ?>" onclick="submit()" 
				<?php if ($poll_row['rating_id'] > 0) echo "checked"; ?> >Підтримую
<!--
			<input type="radio" name="R<?php echo $poll_row['id']; ?>" value="5" 
				<?php if ($poll_row['rating_id'] == 5) echo "checked"; ?> >5-дуже добре<br>
			<input type="radio" name="R<?php echo $poll_row['id']; ?>" value="4" 
				<?php if ($poll_row['rating_id'] == 4) echo "checked"; ?>>4-добре<br>
			<input type="radio" name="R<?php echo $poll_row['id']; ?>" value="3" 
				<?php if ($poll_row['rating_id'] == 3) echo "checked"; ?>>3-посередньо<br>
			<input type="radio" name="R<?php echo $poll_row['id']; ?>" value="2" 
				<?php if ($poll_row['rating_id'] == 2) echo "checked"; ?>>2-погано<br>
			<input type="radio" name="R<?php echo $poll_row['id']; ?>" value="1" 
				<?php if ($poll_row['rating_id'] == 1) echo "checked"; ?>>1-дуже погано
-->
		</td>
		</tr><?php
} ?>
<!-- <tr><td colspan=2><input type="submit" name="sbtSave" value="Зберегти"></td></tr> -->
</table>
