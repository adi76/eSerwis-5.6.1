<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	
	$_dimie = $_POST[dimie];
	$_dnazwisko = $_POST[dnazwisko];
	
	$_dimie = trim($_dimie);
	$_dnazwisko = trim($_dnazwisko);
	
	// sprawdzenie czy użytkownik o takim imieniu i nazwisku już nie istnieje w bazie
	$r40 = mysql_query("SELECT count(user_id) FROM $dbname.serwis_uzytkownicy WHERE (UPPER(user_first_name)='".strtoupper($_dimie)."') and (UPPER(user_last_name)='".strtoupper($_dnazwisko)."') LIMIT 1", $conn) or die($k_b);	
	list($czy_jest_dubel)=mysql_fetch_array($r40);

	if ($czy_jest_dubel==0) {
		$sql_a = "INSERT INTO $dbname.serwis_uzytkownicy VALUES ('','$_POST[dlogin]',md5('$_POST[dpassword]'),'$_dimie','$_dnazwisko','$_POST[demail]','$_POST[dphone]','$_POST[dfilia]','$_POST[urank]','0','','','','$_POST[masteruser]','$_POST[ustyle]',$_POST[locked],$_POST[ikony],$_POST[sprzedaz],$_POST[demailallow],'$_POST[dulica]','$_POST[dkod]','$_POST[dmiejscowosc]',0,'$_POST[menu_type]',$_REQUEST[startpage],$_POST[mminhd],'')";
		//echo "$sql_a";
		
		if (mysql_query($sql_a, $conn)) 
			{ 
				?><script> 
					if (opener) opener.location.reload(true); 
					self.close();
					//newWindow_r(800,600,'hd_statystyka_uaktualnij.php?newuser=1&loginname=<?php echo $_POST[dlogin]; ?>');
				 </script><?php
		} else 
			{
			  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy');self.close(); </script><?php
			}
	} else {
		?><script>info('Wprowadzone przez Ciebie imie i nazwisko (<?php echo $_dimie.' '.$_dnazwisko; ?>) użytkownika nie są unikalne w bazie. \n\n Dodanie użytkownika do bazy zostało anulowane.'); history.go(-1); </script><?php
	}
	
} else { 
	pageheader("Dodawanie nowego pracownika");
	starttable();
	echo "<form name=add action=$PHP_SELF method=POST onSubmit=\"cookieForms('save', 'add'); return true; \">";
	
	tr_();echo "<td colspan=2><b>Dane osobowe</b><hr /></td>";_tr();	
	tr_();
		td("150;r;Imię");
		td_(";;");
			echo "<input class=wymagane size=20 maxlength=20 type=text id=dimie name=dimie onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("150;r;Nazwisko");
		td_(";;");
			echo "<input class=wymagane size=30 maxlength=30 type=text id=dnazwisko name=dnazwisko onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();

	tr_();
		td("150;r;Adres zamieszkania (ulica)");
		td_(";;");
			echo "<input class=wymagane size=50 maxlength=30 type=text id=dulica name=dulica onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("150;r;Kod pocztowy / Miejscowość");
		td_(";;");
			echo "<input class=wymagane size=10 maxlength=6 type=text id=dkod name=dkod onkeypress='return handleEnter(this, event);'>";
			echo "&nbsp;";
			echo "<input class=wymagane size=30 maxlength=40 type=text id=dmiejscowosc name=dmiejscowosc onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("150;r;Telefon");
		td_(";;");
			echo "<input class=wymagane size=20 maxlength=20 type=text id=dphone name=dphone onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();		
	tr_();
		td("150;r;email");
		td_(";;");
			echo "<input class=wymagane size=40 maxlength=150 type=text id=demail name=demail onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();

	tr_();echo "<td colspan=2><b>Dostęp do systemu</b><hr /></td>";_tr();	
	
	tr_();
		td("150;r;Login");
		td_(";;");
			echo "<input id=user class=wymagane size=25 maxlength=25 type=text name=dlogin onKeyUp='slownik_loginow()' onBlur='slownik_loginow()'>";
			echo "<img name=status src=img//none.gif>";
			echo "<select name=lista style='display:none' onkeypress='return handleEnter(this, event);'>";
			
			$sql="SELECT user_login FROM $dbname.serwis_uzytkownicy ORDER BY user_login";
			$result=mysql_query($sql,$conn) or die($k_b);
			
			while ($dane=mysql_fetch_array($result)) {
				$temp = $dane['user_login'];
				echo "<option value=$temp>$temp</option>\n";
			}
			echo "</select>";			
		_td();
	_tr();	
	tr_();
		td("150;r;Hasło");
		td_(";;");
			echo "<input class=wymagane size=34 maxlength=34 type=password id=dpassword name=dpassword onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	

	tr_();
		td("150;r;Uprawnienia");
		td_(";;");
			echo "<select class=wymagane name='urank' onkeypress='return handleEnter(this, event);'>\n"; 
			echo "<option value=2>Helpdesk</option>\n";
			echo "<option value=0 selected>Zwykły</option>\n"; 	
			echo "<option value=1>Zaawansowany</option>\n"; 	
			echo "<option value=9>Administrator</option>\n"; 	
			echo "</select>&nbsp;";
			if ($currentuser==$adminname) {
				echo "<select name='masteruser' onkeypress='return handleEnter(this, event);'>\n";
				echo "<option value=0 selected></option>\n";
				echo "<option value=1>master</option>\n";
				echo "</select>";
			} else { echo "<input type=hidden name=masteruser value=0>"; }
		_td();
	_tr();
	tr_();
		td("150;r;Filia / Oddział");
		td_(";;");		
			$query = "SELECT filia_nazwa,filia_id FROM $dbname.serwis_filie";
			if ($currentuser!=$adminname) {	$query = $query." WHERE filia_id=$es_filia"; }
			if ($result = mysql_query($query, $conn)) { 
			 	if($success = mysql_num_rows($result) > 0) { 
					echo "<select name='dfilia' onkeypress='return handleEnter(this, event);'>\n"; 
					while (list($fnazwa,$fid)=mysql_fetch_array($result)) { 
						echo "<option value='$fid'>$fnazwa</option>\n"; 
					} 
					echo "</select>\n"; 
					}
			}
		_td();
	_tr();
	
	echo "<tr style='display:none;'>";
		td("150;r;Otrzymywanie emaili");
		td_(";;");
			echo "<select class=wymagane name=demailallow onkeypress='return handleEnter(this, event);'>";
			echo "<option value=0>NIE</option>\n";
			echo "<option value=1>TAK</option>\n";
			echo "</select>";
		_td();
	_tr();		
	
	tr_();
		td("150;r;Sprzedaż towarów / usług");
		td_(";;");
			echo "<select name=sprzedaz onkeypress='return handleEnter(this, event);'>\n";
			echo "<option value=0 SELECTED>zabroniona</option>";
			echo "<option value=1>dozwolona</option>";
			echo "</select>";
		_td();
	_tr();	
	tr_();
		td("150;r;Konto");
		td_(";;");
			echo "<select name=locked onkeypress='return handleEnter(this, event);'>\n";
			echo "<option value=0 SELECTED>Włączone</option>";
			echo "<option value=1>Wyłączone</option>";
			echo "</select>";
		_td();
	_tr();

	tr_();
		td("150;r;Strona startowa");
		td_(";;");
			echo "<select name=startpage onkeypress='return handleEnter(this, event);' onChange=\"if (this.value==1) { document.getElementById('info1').style.display=''; } else { document.getElementById('info1').style.display='none'; }\">\n";
			echo "<option value=0 SELECTED>Strona główna</option>";
			echo "<option value=1>Przeglądanie zgłoszeń</option>";
			echo "<option value=2>Sprawdzenie wykonywania kopii</option>";
			echo "<option value=3>Sprawdzenie wykonywania kopii - tylko problemy</option>";
			echo "</select>";
			echo "<span id=info1 style='display:none;'><br /><font color=red>Ustwienie będzie aktywne dla widoku bazy po zalogowaniu: \"zaawansowany\"</font></span>";
		_td();
	_tr();
	
	tr_();
		td("150;r;Menu główne w Helpdesku");
		td_(";;");
			echo "<select name=mminhd onkeypress='return handleEnter(this, event);'>\n";
			echo "<option value=1>TAK</option>";
			echo "<option value=0 SELECTED>NIE</option>";
			echo "</select>";
		_td();
	_tr();
	
	tr_();echo "<td colspan=2><b>Wygląd</b><hr /></td>";_tr();	

	tr_();
		td("150;r;Widok bazy po zalogowaniu");
		td_(";;");
			echo "<select name=menu_type onkeypress='return handleEnter(this, event);'>\n";
			echo "<option value=0 SELECTED>prosty</option>";
			echo "<option value=1>zaawansowany</option>";
			echo "</select>";
		_td();
	_tr();
	
	tr_();
		td("150;r;Kolorystyka");
		td_(";;");
			$result = mysql_query("SELECT style_nazwa,style_opis FROM $dbname.serwis_style", $conn) or die($k_b);
			if($success = mysql_num_rows($result) > 0) {		
				echo "<select name='ustyle' onkeypress='return handleEnter(this, event);'>\n";
				while (list($snazwa,$sopis)=mysql_fetch_array($result)) { 
					echo "<option value='$snazwa'>$sopis</option>\n"; 
				}
			}
			echo "</select>";			
		_td();
	_tr();
	tr_();
		td("150;r;Wygląd menu");
		td_(";;");
			echo "<select name=ikony onkeypress='return handleEnter(this, event);'>\n";
			echo "<option value=0>Ukryj ikony w menu</option>";
			echo "<option value=1 SELECTED>Pokaż ikony w menu</option>";
			echo "</select>";			
		_td();
	_tr();


tbl_empty_row();	
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("dimie","req","Nie podano imienia");
  frmvalidator.addValidation("dnazwisko","req","Nie podano nazwiska");
  frmvalidator.addValidation("dlogin","req","Nie podano loginu");
  frmvalidator.addValidation("dpassword","req","Nie podano hasła");
  frmvalidator.addValidation("demail","req","Nie podano adresu email");
  frmvalidator.addValidation("demail","email","Błędnie podany adres email");
  frmvalidator.addValidation("dphone","req","Nie podano numeru telefonu");
  frmvalidator.addValidation("dulica","req","Nie podano ulicy");
  frmvalidator.addValidation("dkod","req","Nie podano kodu pocztowego");
  frmvalidator.addValidation("dmiejscowosc","req","Nie podano miejscowości");
  
</script>
<?php } ?>
</body>
</html>