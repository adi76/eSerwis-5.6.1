<?php
session_start();
session_unset();
session_destroy();
include('cfg_adres.php');
//if ($_SERVER['SERVER_PORT']!=443) {$d=$linkdostrony . 'login.php?key=eSerwis'; header("Location: $d"); }

if (($key!='5c18tpdr749nkh60w3y2sbxvqfjzmg0ng3zwxq95ypdmj67tc8vkb2hsf14r') && ($key!='eSerwis')) header("Location: ".$linkdostrony."");
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
<head>
	<title>Logowanie do bazy eSerwis</title>
<script>
function handleEnter(_1,_2){var _3=_2.keyCode?_2.keyCode:_2.which?_2.which:_2.charCode;if(_3==13){var i;for(i=0;i<_1.form.elements.length;i++){if(_1==_1.form.elements[i]){break;}}i=(i+1)%_1.form.elements.length;_1.form.elements[i].focus();return false;}else{return true;}}
</script>
<link rel="stylesheet" type="text/css" href="css/serwis.css" />
<style type="text/css">
.login{margin-left:auto;margin-right:auto;margin-top:7em;padding:10em;border:0px solid transparent;}
.login-form{text-align:left;width:365px;}
.form-block{margin-left:auto;margin-right:auto;margin-top:0em;border:1px solid #B4B8C2;background:#E5E6E9;padding-top:20px;padding-left:10px;padding-bottom:5px;padding-right:10px;}
.form-block1{margin-left:auto;margin-right:auto;border:1px solid #B4B8C2; background:#BBBDC5;padding-top:4px;padding-left:4px;padding-bottom:4px;padding-right:10px;font-weight:bold;color:#242424;height:14px;}
.inputlabel{float:left;width:100px;text-align:right;font-size:11px;margin:4px 6px 0 13;}
.inputbox{width:200px;margin:0 0 0.3em 0;border:1px solid #B4B8C2;padding:3 3 3 3;font-size:10px;height:22px;}
.button{border:solid 1px #A8ADB8;background:#C6C9D0;color:black;font-weight:normal;font-size:11px;padding:3px;width:70px;margin:1em 0 1em 0;}
.button:hover{border:solid 1px #A8ADB8;background:#FDFDCD;color:black;font-weight:normal;font-size:11px;padding:3px;width:70px;}
.footer{position:absolute;bottom:0;text-align:center;width:345px;margin-bottom:5px;margin-top:0px;}
.linia{color:#5F6676;border-style:dotted;height:1px;border-bottom:none;border-right:none;border-left:none;background-color:transparent;margin:10px 0 0 0;padding:0px;}
</style>
</head>
<body OnLoad="document.forms[0].elements[0].focus();">
<form action="login_check.php" name="logowanie" method="POST">
<br />
<table class="loginwindow_pasek" height="97%" align="center" border="0"><tr><td>
<table align="center" border="0" cellpadding="0" cellspacing="0" cols="2">
<?php include('cfg_wersja.php');?>
<tr>
	<td align="left" class="loginwindow_title"><b>&nbsp;&nbsp;<font size="2" color="yellow">::</font>&nbsp;&nbsp;<font color="white" size="2">Logowanie do bazy eSerwis</font></b></td>
</tr>
<tr>
	<td>
		<table width="320" height="79" border="0" cellpadding="0" cellspacing="0" cols="2">
			<tr class="loginwindow_tlo">
				<td>&nbsp;</td><td>&nbsp;</td>
			</tr>
			<tr class="loginwindow_tlo">
				<td align=right>
					<font face=Tahoma size=2>Użytkownik&nbsp;</font>
				</td>
				<td>
					<input class="loginwindow_user" type="text" name="login" size="23">
				</td>
			</tr>
			<tr class="loginwindow_tlo">
				<td align=right>
					<font face=Tahoma size=2>Hasło&nbsp;</font>
				</td>
				<td >
					<input class="loginwindow_pass" type="password" name="haslo" SIZE="23">
				</td>
			</tr>
			<tr class="loginwindow_tlo">
				<td>&nbsp;</td><td>&nbsp;</td>
			</tr>
			<tr>
				<td class="loginwindow_tlo" align="left">&nbsp;&nbsp;<input class="buttons"  type="button" name="zam" value="Zakończ" onClick="javascript:if(confirm('Czy napewno chcesz zakończyć pracę z bazą eSerwis ?')) self.close();"></td>
				<td class="loginwindow_tlo" align="right"><input class="buttons" type="submit" name="submitlogin" value="Zaloguj">&nbsp;&nbsp;</td>
			</tr>
			<tr class="loginwindow_tlo">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		</form>
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td class="loginwindow_pasek" align="center" valign="bottom">
<font size="-6" color="#666666">
<hr style="color:#5F6676; border-style:dotted; height:1px; border-bottom:none;border-right:none;border-left:none; background-color:transparent; margin:0px; padding:0px;">
<?php echo "eSerwis, wersja $wersja, "; ?>
Autor: Maciej Adrjanowicz
</font>
</td>
</tr>
</table>
</body>
</html>