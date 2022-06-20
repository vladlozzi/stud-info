<?php
if(!defined("IN_ADMIN")) die;

$params = array(
	'client_id'     => '321271702998-edk6g1f4411q3lenmt9t1t6bni2n2l56.apps.googleusercontent.com',
	'redirect_uri'  => 'https://stud-info.nung.edu.ua/index.php',
	'response_type' => 'code',
	'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
	'state'         => '123'
);
$url = 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($params));


$signin = <<< EOT
<h1 style="text-align: center; margin-top: 50px; font-weight: bold;">
  <a href=
EOT;
$signin .= '"' . $url . '"';
$signin .= <<< EOT
>Увійдіть через пошту ІФНТУНГ</a>
</h1>
<div class="login-caption" style="margin-top: 50px;">
  <h3 style="text-align: center; font-weight: bold;">або авторизуйтеся за логіном і паролем</h3>
</div>
<form id="login" action="" method="post">
	<p style="text-align: center; font-size: 80%">
	 	Студентам для входу необхідно використовувати логін у форматі stud_NNNNNN і пароль, надані в дирекції інституту
	</p>
	<fieldset id="inputs">
		<input id="username" type="text" name="login" placeholder="Логін" autofocus required 
					pattern="^[A-Za-z0-9_]{1,64}$" title="Введіть свій логін, а не адресу e-mail" />
		<input id="password" type="password" name="psswd" placeholder="Пароль" required />
	</fieldset>
	<fieldset id="actions">
		<input type="submit" id="submit" name="enter" value="Вхід" 
			onclick='document.getElementById("wait").style.display = "inline"' />
	</fieldset>
</form>
EOT;
$page .= $signin;
?>
