<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body OnLoad=\"document.forms[0].elements[0].focus();\" />";

$CountNowe = 0;
$CountPrzypisane = 0;
$CountRozpoczete = 0;
$CountPrzypisaneDoMnie = 0;

starttable();
th(";c;|50;c;Nowe|50;c;Przypisane|50;c;Przypisane do mnie|50;c;Rozpoczęte",$es_prawa);

	tbl_tr_highlight(0);
		td("150;rt;<br /><b>Awarie krytyczne&nbsp;</b><br />");
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=2&p4=4&p5=1'; self.focus(); \" />";	
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_priorytet='4') and (zgl_status='1')", $conn_hd)); 
			$CountNowe+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=2&p4=4&p5=2'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_priorytet='4') and (zgl_status='2')", $conn_hd)); 
			$CountPrzypisane+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";		
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=2&p4=4&p5=2&p6=".urlencode($currentuser)."'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_priorytet='4') and (zgl_status='2') and (zgl_osoba_przypisana='$currentuser')", $conn_hd)); 
			$CountPrzypisaneDoMnie+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";		
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=2&p4=4&p5=3'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_priorytet='4') and (zgl_status='3')", $conn_hd)); 
			$CountRozpoczete+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";				
		_td();
	_tr();	

	tbl_tr_highlight(1);
		td("150;rt;<br /><b>Awarie zwykłe&nbsp;</b><br />");
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=2&p4=2&p5=1'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_priorytet='2') and (zgl_status='1')", $conn_hd)); 
			$CountNowe+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=2&p4=2&p5=2'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_priorytet='2') and (zgl_status='2')", $conn_hd)); 
			$CountPrzypisane+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";		
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=2&p4=2&p5=2&p6=".urlencode($currentuser)."'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_priorytet='2') and (zgl_status='2') and (zgl_osoba_przypisana='$currentuser')", $conn_hd)); 
			$CountPrzypisaneDoMnie+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";		
		_td();		
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=2&p4=2&p5=3'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_priorytet='2') and (zgl_status='3')", $conn_hd)); 
			$CountRozpoczete+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";				
		_td();
	_tr();	

	tbl_tr_highlight(2);
		td("150;rt;<br /><b>Prace wg umowy (priorytet niski)&nbsp;</b><br />");
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=1&p5=1'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='1') and (zgl_status='1')", $conn_hd)); 
			$CountNowe+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=1&p5=2'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='1') and (zgl_status='2')", $conn_hd)); 
			$CountPrzypisane+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";		
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=1&p5=2&p6=".urlencode($currentuser)."'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='1') and (zgl_status='2') and (zgl_osoba_przypisana='$currentuser')", $conn_hd)); 
			$CountPrzypisaneDoMnie+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";		
		_td();
		td_(";c;;");		 
			echo "-";		
		_td();
	_tr();	

	tbl_tr_highlight(3);
		td("150;rt;<br /><b>Prace wg umowy (priorytet standardowy)&nbsp;</b><br />");
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=2&p5=1'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='2') and (zgl_status='1')", $conn_hd)); 
			$CountNowe+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=2&p5=2'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='2') and (zgl_status='2')", $conn_hd)); 
			$CountPrzypisane+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";		
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=2&p5=2&p6=".urlencode($currentuser)."'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='2') and (zgl_status='2') and (zgl_osoba_przypisana='$currentuser')", $conn_hd)); 
			$CountPrzypisaneDoMnie+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";		
		_td();		
		td_(";c;;");		 
			echo "-";		
		_td();
	_tr();	

	tbl_tr_highlight(4);
		td("150;rt;<br /><b>Prace wg umowy (priorytet wysoki)&nbsp;</b><br />");
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=3&p5=1'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='3') and (zgl_status='1')", $conn_hd)); 
			$CountNowe+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=3&p5=2'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='3') and (zgl_status='2')", $conn_hd)); 
			$CountPrzypisane+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";		
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=3&p4=3&p5=2&p6=".urlencode($currentuser)."'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='3') and (zgl_status='2') and (zgl_osoba_przypisana='$currentuser')", $conn_hd)); 
			$CountPrzypisaneDoMnie+=$wynik;
			echo "<br /><font color=red size=3>".$wynik."</font><br /><br />";		
		_td();		
		td_(";c;;");		 
			echo "-";		
		_td();
	_tr();	

	tbl_tr_highlight(5);
		td("150;rt;<br /><b>Prace poza umową&nbsp;</b><br />");
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=4&p4=2&p5=1'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='4') and (zgl_priorytet='2') and (zgl_status='1')", $conn_hd)); 
			$CountNowe+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=4&p4=2&p5=2'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='4') and (zgl_priorytet='2') and (zgl_status='2')", $conn_hd)); 
			$CountPrzypisane+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";		
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=4&p4=2&p5=2&p6=".urlencode($currentuser)."'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='4') and (zgl_priorytet='2') and (zgl_status='2') and (zgl_osoba_przypisana='$currentuser')", $conn_hd)); 
			$CountPrzypisaneDoMnie+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";		
		_td();		
		td_(";c;;");		 
			echo "-";		
		_td();
	_tr();	

	tbl_tr_highlight(6);
		td("150;rt;<br /><b>Prace na potrzeby Postdata&nbsp;</b><br />");
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=5&p4=2&p5=1'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='5') and (zgl_priorytet='2') and (zgl_status='1')", $conn_hd)); 
			$CountNowe+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=5&p4=2&p5=2'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='5') and (zgl_priorytet='2') and (zgl_status='2')", $conn_hd)); 
			$CountPrzypisane+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";		
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p2=5&p4=2&p5=2&p6=".urlencode($currentuser)."'; self.focus();\" />";
			list($wynik)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='5') and (zgl_priorytet='2') and (zgl_status='2') and (zgl_osoba_przypisana='$currentuser')", $conn_hd)); 
			$CountPrzypisaneDoMnie+=$wynik;
			echo "<br /><font size=3>".$wynik."</font><br /><br />";		
		_td();		
		td_(";c;;");		 
			echo "-";		
		_td();
	_tr();		

	tbl_tr_highlight(7);
		td("150;rt;<br /><b><font size=4>Sumarycznie: </b><br /><br />");
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p5=1'; self.focus();\" />";
			echo "<br /><b><font size=4>".$CountNowe."</font></b><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p5=2'; self.focus();\" />";	 
			echo "<br /><b><font size=4>".$CountPrzypisane."</font></b><br /><br />";
		_td();
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p5=2&p6=".urlencode($currentuser)."'; self.focus();\" />";	 
			echo "<br /><b><font size=4>".$CountPrzypisaneDoMnie."</font></b><br /><br />";
		_td();		
		echo "<td style='text-align:center' onClick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p5=3'; self.focus();\" />";	 
			echo "<br /><b><font size=4>".$CountRozpoczete."</font></b><br /><br />";
		_td();
	_tr();	
	
endtable();

startbuttonsarea("right");
echo "<input class=buttons type=button onClick=\"self.location.reload(); \" value='Odśwież widok' />";
echo "<input class=buttons id=anuluj type=button onClick=\"self.close();\" value=Zamknij>";
endbuttonsarea();


?>
<meta http-equiv="REFRESH" content="60;">
</body>
</html>