<?php
if(!defined("IN_ADMIN")) die; // if manual used - drop script
//-------------------------------------$dekan_name=$_SESSION['user_fullname'];-------------------------------
$role_name = "";
switch ($_SESSION['user_role'])
{
	case 'ROLE_STUDENT': $role_name = "студент"; break;
	case 'ROLE_TEACHER': $role_name = "викладач"; break;
	case 'ROLE_ZAVKAF' : $role_name = "завідувач кафедри"; break;
	case 'ROLE_DEKAN'  : $role_name = "директор інституту"; break;
	case 'ROLE_VICERECTOR' : $role_name = "проректор"; break;
	case 'ROLE_RECTOR' : $role_name = "ректор"; break;
	case 'ROLE_PSYCHO' : $role_name = "практичний психолог"; break;
	case 'ROLE_ADMIN' : $role_name = "адміністратор"; break;
}	
$user_fullname = $_SESSION['user_fullname'];
//-------------------information message--------------------------
$page .= str_replace("Kiev","Kyiv",date("Y-m-d H:i:s (e P)"))." &nbsp; Ви увійшли в систему як ".newLineAfter(bold($role_name." ".$user_fullname));//add name
?>
