<?php
if(!defined("IN_ADMIN")) die;
$page .= "<div id=\"footer\">
		<address>&copy; Лабораторія МЗНП, 2012-2015 &nbsp; &copy; Лабораторія СНП(ex-АСУ), 2016-".date("Y")."</address>";

if (date("Y-m-d") < date("Y-m-d", strtotime($dateOfPollStart))) 
	$page .= "<br><p style=\"text-align: center; font-size: 120%; font-weight: bold; \">
		Опитування &laquo;Викладач очима студентів&raquo; почнеться ".date("d.m.Y", strtotime($dateOfPollStart))."р. Адміністратор</p>";
if (date("Y-m-d") >= date("Y-m-d", strtotime($dateOfPollStart)) and date("Y-m-d") <= date("Y-m-d", strtotime($dateOfPollFinish)))
	$page .= "<br><p style=\"text-align: center; font-size: 120%; font-weight: bold; \">
		Опитування &laquo;Викладач очима студентів&raquo; почалося ".date("d.m.Y", strtotime($dateOfPollStart))."р. 
		і триватиме до ".date("d.m.Y", strtotime($dateOfPollFinish))."р. Адміністратор</p>";
if (date("Y-m-d") > date("Y-m-d", strtotime($dateOfPollFinish))) 
	$page .= "<br><p style=\"text-align: center; font-size: 120%; font-weight: bold; \">
		Опитування &raquo;Викладач очима студентів&raquo; завершилося ".date("d.m.Y", strtotime($dateOfPollFinish))."р. Дякую Вам за участь! Адміністратор</p>";
$page .= "</div>";

