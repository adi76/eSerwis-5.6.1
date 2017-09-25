<?php include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
	
	function toUpper($string) {
		$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
		return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
	};
	
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	
	$_POST=sanitize($_POST);
	
	$_dimie = $_POST[dimie];
	$_dnazwisko = $_POST[dnazwisko];
	
	$_dimie = trim($_dimie);
	$_dnazwisko = trim($_dnazwisko);
	
	// sprawdzenie czy użytkownik o takim imieniu i nazwisku już nie istnieje w bazie

	//echo "SELECT count(user_id) FROM $dbname.serwis_uzytkownicy WHERE (UPPER(user_first_name)='".toUpper($_dimie)."') and (UPPER(user_last_name)='".toUpper($_dnazwisko)."') LIMIT 1";
	
	$r40 = mysql_query("SELECT count(user_id) FROM $dbname.serwis_uzytkownicy WHERE (UPPER(user_first_name)='".toUpper($_dimie)."') and (UPPER(user_last_name)='".toUpper($_dnazwisko)."') LIMIT 1", $conn) or die($k_b);	
	list($czy_jest_dubel)=mysql_fetch_array($r40);
	
	if ($czy_jest_dubel>0) {
	
		$accessLevels = array("0","1","2"); if(array_search($es_prawa, $accessLevels)>-1) {
			$sql_e1="UPDATE $dbname.serwis_uzytkownicy SET user_login = '$_POST[dlogin]' , user_first_name = '$_dimie' , user_last_name = '$_dnazwisko' , user_email = '$_POST[demail]' , user_phone = '$_POST[dphone]', user_style='$_POST[ustyle]', user_showicons=$_POST[ikony], user_ulica = '$_POST[dulica]', user_kod_pocztowy = '$_POST[dkod]', user_miejscowosc = '$_POST[dmiejscowosc]', user_menu_type='$_POST[menu_type]', user_startpage = '$_POST[startpage]', user_mainmenu_in_helpdesk='$_POST[mminhd]' WHERE user_id = '$did' LIMIT 1";
		}
		
		$accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1) {
			$sql_e1="UPDATE $dbname.serwis_uzytkownicy SET user_login = '$_POST[dlogin]' , user_first_name = '$_dimie' , user_last_name = '$_dnazwisko' , user_email = '$_POST[demail]' , user_allow_emails = '$_POST[demailallow]', user_phone = '$_POST[dphone]' , belongs_to = '$_POST[dfilia]', user_ranking='$_POST[urank]', user_master='$_POST[masteruser]', user_style='$_POST[ustyle]', user_locked=$_POST[locked], user_showicons=$_POST[ikony], user_allow_sell='$_POST[sprzedaz]', user_ulica = '$_POST[dulica]', user_kod_pocztowy = '$_POST[dkod]', user_miejscowosc = '$_POST[dmiejscowosc]', user_menu_type='$_POST[menu_type]', user_startpage = '$_POST[startpage]', user_mainmenu_in_helpdesk='$_POST[mminhd]' WHERE user_id = '$did' LIMIT 1";
			//	echo $sql_e1;
		}

		if (($es_imie==$_dimie) && ($es_nazwisko==$_dnazwisko)) {
			$es_style	= $_POST[ustyle];
			$es_m		= $_POST[masteruser];
			$pokaz_ikony = $_POST[ikony];
		}
		if (mysql_query($sql_e1, $conn)) { 
		
			if ($_POST[login]!=$_POST[dlogin]) {
				// uaktualnij tabelę ze statystykami Helpdesk'a
				$sql_e1="UPDATE $dbname_hd.hd_statystyka SET statystyka_osoba = '$_POST[dlogin]' WHERE (statystyka_osoba = '$_POST[login]')";
				mysql_query($sql_e1, $conn);
			}
			
			?>
			<script>HideWaitingMessage('Saving1');</script>
			<script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	} else {
		?><script>info('Wprowadzone przez Ciebie imie i nazwisko (<?php echo $_dimie.' '.$_dnazwisko; ?>) użytkownika nie są unikalne w bazie. \n\n Wpisane zmiany nie zostały zapisane do bazy.'); history.go(-1); </script><?php
	}
} else {

	$sql_e = "SELECT * FROM $dbname.serwis_uzytkownicy WHERE (user_id=$select_id) LIMIT 1";
	$result = mysql_query($sql_e, $conn) or die($k_b);

	while ($newArray = mysql_fetch_array($result)) {

		$temp_id  			= $newArray['user_id'];
		$temp_login			= $newArray['user_login'];
		$temp_pass			= $newArray['user_pass'];
		$temp_first_name	= $newArray['user_first_name'];
		$temp_last_name		= $newArray['user_last_name'];
		$temp_email			= $newArray['user_email'];
		$temp_email_allow	= $newArray['user_allow_emails'];	
		$temp_phone			= $newArray['user_phone'];
		$temp_rank			= $newArray['user_ranking'];	
		$temp_belongs_to	= $newArray['belongs_to'];
		$temp_um			= $newArray['user_master'];
		$temp_style			= $newArray['user_style'];
		$temp_lock			= $newArray['user_locked'];
		$temp_ikony			= $newArray['user_showicons'];
		$temp_sprzedaz		= $newArray['user_allow_sell'];
		$temp_ulica			= $newArray['user_ulica'];
		$temp_kod			= $newArray['user_kod_pocztowy'];
		$temp_miejscowosc	= $newArray['user_miejscowosc'];	
		$temp_menu_type		= $newArray['user_menu_type'];	
		$temp_startpage		= $newArray['user_startpage'];	
		$temp_mminhd		= $newArray['user_mainmenu_in_helpdesk'];	
		
	}
	
	pageheader("Edycja danych pracownika");
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	starttable();
	echo "<form name=ed action=$PHP_SELF method=POST>";
	tbl_empty_row(2);
	tr_();echo "<td colspan=2><b>Dane osobowe</b><hr /></td>";_tr();	
		tr_();
			td("150;r;Imię");
			td_(";;");
				echo "<input class=wymagane size=20 maxlength=20 type=text name=dimie value='$temp_first_name' onkeypress='return handleEnter(this, event);'>";
			_td();
		_tr();
		tr_();
			td("150;r;Nazwisko");
			td_(";;");
				echo "<input class=wymagane size=30 maxlength=30 type=text name=dnazwisko value='$temp_last_name' onkeypress='return handleEnter(this, event);'>";
			_td();
		_tr();
		tr_();
			td("150;r;Adres zamieszkania (ulica)");
			td_(";;");
				echo "<input class=wymagane size=50 maxlength=30 type=text name=dulica value='$temp_ulica' onkeypress='return handleEnter(this, event);'>";
			_td();
		_tr();
		tr_();
			td("150;r;Kod pocztowy / Miejscowość");
			td_(";;");
				echo "<input class=wymagane size=10 maxlength=6 type=text name=dkod value='$temp_kod' onkeypress='return handleEnter(this, event);'>";
				echo "&nbsp;";
				echo "<input class=wymagane size=30 maxlength=40 type=text name=dmiejscowosc value='$temp_miejscowosc' onkeypress='return handleEnter(this, event);'>";
			_td();
		_tr();
		tr_();
			td("150;r;Telefon");
			td_(";;");
				echo "<input class=wymagane size=20 maxlength=20 type=text name=dphone value='$temp_phone' onkeypress='return handleEnter(this, event);'>";
			_td();
		_tr();
		tr_();
			td("150;r;email");
			td_(";;");
				echo "<input class=wymagane size=35 maxlength=100 type=text name=demail value='$temp_email' onkeypress='return handleEnter(this, event);'>";
			_td();
		_tr();	

		tr_();echo "<td colspan=2><b>Dostęp do systemu</b><hr /></td>";_tr();	
		
		tr_();
			td("150;r;Login");
			td_(";;");
				echo "<input id=user class=wymagane size=25 maxlength=25 type=text name=dlogin onKeyUp='slownik_loginowe()' onBlur='slownik_loginowe()' value='$temp_login'>";
				echo "<img name=status src=img//none.gif>";
				echo "<select name=lista style='display:none' onkeypress='return handleEnter(this, event);'>";
				$result=mysql_query("SELECT user_login FROM $dbname.serwis_uzytkownicy ORDER BY user_login",$conn) or die($k_b);
				while (list($temp)=mysql_fetch_array($result)) {
					if ($temp!=$temp_login) echo "<option value=$temp>$temp</option>\n";
				}
				echo "</select>";
			_td();
		_tr();

		$accessLevels = array("9");
		if(array_search($es_prawa, $accessLevels)>-1) {
		tr_();
			td("150;r;Uprawnienia");
			td_(";;");
				echo "<select class=wymagane name='urank' onkeypress='return handleEnter(this, event);'>\n"; 
					echo "<option value=2 "; if ($temp_rank==2) { echo "selected"; } echo " >Helpdesk</option>\n"; 
					echo "<option value=0 "; if ($temp_rank==0) { echo "selected"; } echo " >Zwykły</option>\n"; 	
					echo "<option value=1 "; if ($temp_rank==1) { echo "selected"; } echo " >Zaawansowany</option>\n";
					echo "<option value=9 "; if ($temp_rank==9) { echo "selected"; } echo " >Administrator</option>\n"; 	
				echo "</select>";
				if ($currentuser==$adminname) {
					echo "&nbsp;";
					echo "<select name='masteruser' onkeypress='return handleEnter(this, event);'>\n";
					echo "<option value=0 "; if ($temp_um==0) { echo "selected";} echo "></option>\n";
					echo "<option value=1 "; if ($temp_um==1) { echo "selected";} echo ">master</option>\n";
					echo "</select>";
				}
			_td();
		_tr();
		} else $urank=$temp_rank;
		
		if (($es_prawa==1) || ($es_prawa==9) || ($es_m==1)) {	
			tr_();
				td("150;r;Filia / Oddział");
				td_(";;");
					$query = "SELECT filia_nazwa,filia_id FROM $dbname.serwis_filie";	
					if ($currentuser!=$adminname) {
						$query = $query." WHERE filia_id=$es_filia";
					}
					if ($result = mysql_query($query,$conn)) { 
						if (($success = mysql_num_rows($result) > 0) and ($all!=0)) { 
							echo "<select name='dfilia' onkeypress='return handleEnter(this, event);'>\n"; 
							$result_e2 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to",$conn) or die($k_b);
							list($return_name)=mysql_fetch_array($result_e2);
							while (list($fnazwa,$fid)=mysql_fetch_array($result)) { 
								echo "<option value='$fid' ";
								if ($fnazwa==$return_name) echo "selected";	echo " >$fnazwa</option>\n"; 
							}
							echo "</select>\n"; 
							}
					}
				_td();
			_tr();	
		}
		if (($es_prawa==9) || ($es_m==1)) {
			echo "<tr style='display:none;'>";
				td("150;r;Otrzymywanie emaili");
				td_(";;");
					echo "<select class=wymagane name=demailallow onkeypress='return handleEnter(this, event);'>";
					echo "<option value=0"; if ($temp_email_allow==0) echo " SELECTED"; echo ">NIE</option>\n";
					echo "<option value=1"; if ($temp_email_allow==1) echo " SELECTED";echo ">TAK</option>\n";
					echo "</select>";
				_td();
			_tr();	
		}
		
		if (($es_prawa==9) || ($es_m==1)) {
			tr_();
				td("150;r;Sprzedaż towarów / usług");
				td_(";;");
					echo "<select name=sprzedaz onkeypress='return handleEnter(this, event);'>\n";
					echo "<option value=0"; if ($temp_sprzedaz==0) echo " SELECTED"; echo ">zabroniona</option>";
					echo "<option value=1"; if ($temp_sprzedaz==1) echo " SELECTED"; echo ">dozwolona</option>";
					echo "</select>";
				_td();
			_tr();
		
			tr_();
				td("150;r;Konto");
				td_(";;");
					echo "<select name=locked onkeypress='return handleEnter(this, event);'>\n";
					echo "<option value=0"; if ($temp_lock==0) echo " SELECTED"; echo ">Włączone</option>";
					echo "<option value=1"; if ($temp_lock==1) echo " SELECTED"; echo ">Wyłączone</option>";
					echo "</select>";
				_td();
			_tr();	

		} else echo "<input type=hidden name=locked value=$temp_lock>";
		
		tr_();
			td("150;r;Strona startowa");
			td_(";;");
				echo "<select name=startpage id=startpage onkeypress='return handleEnter(this, event);' onChange=\"if (this.value==1) { document.getElementById('info1').style.display=''; } else { document.getElementById('info1').style.display='none'; }\">\n";
				echo "<option value=0"; if ($temp_startpage==0) echo " SELECTED"; echo ">Strona główna</option>";
				echo "<option value=1"; if ($temp_startpage==1) echo " SELECTED"; echo ">Przeglądanie zgłoszeń</option>";
				echo "<option value=2"; if ($temp_startpage==2) echo " SELECTED"; echo ">Sprawdzenie wykonywania kopii</option>";
				echo "<option value=3"; if ($temp_startpage==3) echo " SELECTED"; echo ">Sprawdzenie wykonywania kopii - tylko problemy</option>";
				echo "</select>";
				echo "<span id=info1 style='display:none;'><br /><font color=red>Ustwienie będzie aktywne dla widoku bazy po zalogowaniu: \"zaawansowany\"</font></span>";
			_td();
		_tr();
	
		tr_();
			td("150;r;Menu główne w Helpdesku");
			td_(";;");
				echo "<select name=mminhd onkeypress='return handleEnter(this, event);'>\n";
				echo "<option value=1"; if ($temp_mminhd==1) echo " SELECTED"; echo ">TAK</option>";
				echo "<option value=0"; if ($temp_mminhd==0) echo " SELECTED"; echo ">NIE</option>";
				echo "</select>";
			_td();
		_tr();
	
		tr_();echo "<td colspan=2><b>Wygląd</b><hr /></td>";_tr();	

		tr_();
			td("150;r;Widok bazy po zalogowaniu");
			td_(";;");
				echo "<select name=menu_type onkeypress='return handleEnter(this, event);'>\n";
				echo "<option value=0"; if ($temp_menu_type==0) echo " SELECTED"; echo ">prosty</option>";
				echo "<option value=1"; if ($temp_menu_type==1) echo " SELECTED"; echo ">zaawansowany</option>";
				echo "</select>";
			_td();
		_tr();
		
		tr_();
			td("150;r;Kolorystyka");
			td_(";;");
			$result = mysql_query("SELECT style_nazwa,style_opis FROM $dbname.serwis_style ORDER BY style_opis ASC", $conn) or die($k_b);
			if($success = mysql_num_rows($result) > 0) {		
				echo "<select name='ustyle' onkeypress='return handleEnter(this, event);'>\n"; 
				while (list($snazwa,$sopis)=mysql_fetch_array($result)) { 
					echo "<option value='$snazwa' ";
					if ($temp_style==$snazwa) echo "SELECTED";echo ">$sopis</option>\n"; 
				} 
				echo "</select>";
			}
			_td();
		_tr();

		tr_();
			td("150;r;Wygląd menu");
			td_(";;");
				echo "<select name=ikony onkeypress='return handleEnter(this, event);'>\n";
				echo "<option value=0"; if ($temp_ikony==0) echo " SELECTED"; echo ">Ukryj ikony w menu</option>";
				echo "<option value=1"; if ($temp_ikony==1) echo " SELECTED"; echo ">Pokaż ikony w menu</option>";
				echo "</select>";
			_td();
		_tr();
		
	tbl_empty_row();
	endtable();
	echo "<input type=hidden name=login value='$_GET[login]'>";
	echo "<input size=30 type=hidden name=did value='$temp_id'>";
	startbuttonsarea("right");
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value='Pojazdy użytkownika' onclick=\"self.close(); newWindow_r(800,600,'hd_z_pojazdy.php?userid=$temp_id');\" />";
	echo "</span>";

	echo "&nbsp;&nbsp;";
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	_form();
?>
<script>
if (document.getElementById('startpage').value==1) { document.getElementById('info1').style.display=''; } else { document.getElementById('info1').style.display='none'; }
</script>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ed");
  frmvalidator.addValidation("dimie","req","Nie podano imienia");
  frmvalidator.addValidation("dnazwisko","req","Nie podano nazwiska");
  frmvalidator.addValidation("dlogin","req","Nie podano loginu");
  frmvalidator.addValidation("demail","req","Nie podano adresu email");
  frmvalidator.addValidation("demail","email","Błędnie podany adres email");
  frmvalidator.addValidation("dphone","req","Nie podano numeru telefonu");
  frmvalidator.addValidation("dulica","req","Nie podano ulicy");
  frmvalidator.addValidation("dkod","req","Nie podano kodu pocztowego");
  frmvalidator.addValidation("dmiejscowosc","req","Nie podano miejscowości");  
</script>
<?php } ?>
<script>HideWaitingMessage();</script>
</body>
</html>