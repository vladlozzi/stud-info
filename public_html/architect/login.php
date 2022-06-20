<?php
// echo "login.php - OK";
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                               "Помилка входу в модуль login.php</p>"; require "footer.php"; exit(); }

$login = <<< EOT

<center>
<form id="login" action="" method="post">
	<fieldset id="inputs">
		Логін: <input id="username" type="text" name="login" placeholder="Логін" autofocus required />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Пароль: <input id="password" type="password" name="psswd" placeholder="Пароль" required />
		<br><br>
		<input type="submit" id="submit" value="Вхід" name="enter"/>
	</fieldset>
</form>
</center>
EOT;

echo $login;

?>
