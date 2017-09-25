<?php include_once('header.php'); ?>
<body onLoad=\"document.getElementById('').focus();\">
<?php include('body_start.php'); ?>
<?php

include_once('login_info.php');
include_once('mainmenu.php');
echo "<br />";

if ($opis=='1') { $od = ''; } else { $od = 'none'; }

$sql="SELECT * FROM $dbname.serwis_kb_kategorie WHERE (kb_parent_id=0) ";

if ($action=='view') $sql = $sql . "and (kb_kategoria_status=1) and (kb_kategoria_accesible_for<=$es_prawa) "; 
//and (kb_kategoria_accesible_for<=$es_prawa) ";

$sql = $sql. " ORDER BY kb_kategoria_nazwa ASC";

$result = mysql_query($sql, $conn) or die($k_b);
$totalrows = mysql_num_rows($result);

if ($totalrows!=0) {

	if ($action=='manage') 
	{
		pageheader("Zarządzanie kategoriami w Bazie Wiedzy",0,0);
	}

	if ($action=='view') 
	{
		pageheader("Lista kategorii w Bazie Wiedzy",0,0);
	}

	startbuttonsarea('center');
	echo "<form name=szukajwbazie action=szukaj.php method=POST style='display:inline;'>";
	echo "Znajdź w bazie wiedzy ";
	echo "<input id=sz size=30 type=text name=search onKeyUp=\"key=window.event.keyCode; if (key==13) { document.getElementById('submit111').focus();  }\" />";
	echo "&nbsp;<input class=buttons style='padding-top:2px; padding-bottom:2px;' type=submit id=submit111 name=submit value='Szukaj' onFocus=\"return false;\">";
	echo "<input type=hidden name=typs value='B'>";
	echo "<input type=hidden name=gdzie value='B'>";
	echo "<input type=hidden name=from value='BW'>";
	echo "</form>";
	endbuttonsarea();
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	starttable();
	echo "<tr>";
	echo "<th colspan=1>Kategorie | Ilość wątków";

	echo "<div id=opis style=display:$od>";
	echo "<i>Opis kategorii</i>";
	echo "</div>";
	echo "</th>";

	echo "<th class=center width=40>Opcje<br />pytań";
	echo "</th>";
	if ($action=='manage') 
	{
		echo "<th width=55>Aktywna</th><th class=center width=100>Widoczna dla</th>";
		// -
		// access control 
		$accessLevels = array("9");
		if(array_search($es_prawa, $accessLevels)>-1)
		{
			echo "<th width=60 class=center>Opcje<br />kategorii</th>";
		}
		// access control koniec
		// -
	}

	echo "</tr>";
	$i = 1;
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_kat_id 		= $newArray['kb_kategoria_id'];
		$temp_par_id		= $newArray['kb_parent_id'];
		$temp_nazwa			= $newArray['kb_kategoria_nazwa'];
		$temp_opis			= $newArray['kb_kategoria_opis'];
		$temp_status		= $newArray['kb_kategoria_status'];
		$temp_access_for	= $newArray['kb_kategoria_accesible_for'];	

		tbl_tr_highlight_dblClick_z_kb_kategorie($i);

		$sql_pk = "SELECT * FROM $dbname.serwis_kb_kategorie WHERE (kb_parent_id=$temp_kat_id) ";
		if ($action=='view') $sql_pk = $sql_pk . "and (kb_kategoria_status=1) and (kb_kategoria_accesible_for<=$es_prawa)"; 
		$sql_pk = $sql_pk . " ORDER BY kb_parent_id";

		$result_pk = mysql_query($sql_pk, $conn) or die($k_b);
		$totalrows_pk = mysql_num_rows($result_pk);
		
		echo "<input type=hidden name=pozid_$i id=pozid_$i value=$temp_kat_id>";
		echo "<input type=hidden name=action id=action value=$action>";
		
		echo "<td ";
		echo "colspan=1 ";
		echo "valign=absmiddle>";
		echo "<a href=# class=normalfont onclick=\"newWindow_r(800,600,'p_kb_pytania.php?id=$temp_kat_id&poziom=0&action=$action')\">";
		
		echo "<b>$temp_nazwa</b>";
		
		
		// wpisanie ilości pytań w danej kategorii
		$sql_k_ilosc = "SELECT * FROM $dbname.serwis_kb_pytania WHERE (kb_kategoria_id=$temp_kat_id)";
		$result_k_ilosc = mysql_query($sql_k_ilosc, $conn) or die($k_b);
		$totalrows_k_ilosc = mysql_num_rows($result_k_ilosc);
		// --------

		echo "&nbsp;|&nbsp;Wątków: <b>$totalrows_k_ilosc</b>";
		echo "</a>";
		
		$data_graniczna = SubstractWorkingDays(7,date('Y-m-d H:i:s'));
		
		list($czy_sa_nowe_wpisy)=mysql_fetch_array(mysql_query("SELECT COUNT(kb_pytanie_id) FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_data>'$data_graniczna') and (kb_kategoria_id=$temp_kat_id)", $conn));
		if ($czy_sa_nowe_wpisy>0) {
			if ($czy_sa_nowe_wpisy==1) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowy wątek</font>";
			if ($czy_sa_nowe_wpisy==2) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowe wątki</font>";
			if ($czy_sa_nowe_wpisy==3) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowe wątki</font>";
			if ($czy_sa_nowe_wpisy==4) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowe wątki</font>";
			if ($czy_sa_nowe_wpisy>4) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowych wątków</font>";
		}
		
		$result66 = mysql_query("SELECT kb_pytanie_id FROM $dbname.serwis_kb_odpowiedzi WHERE (kb_odpowiedz_data>'$data_graniczna')", $conn);
		$vv = 0;
		while ($newArray66 = mysql_fetch_array($result66)) {
			$temp_odp 		= $newArray66['kb_pytanie_id'];
			list($temp_id_kategorii_kb)=mysql_fetch_array(mysql_query("SELECT kb_kategoria_id FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_id='$temp_odp') ", $conn));
			if ($temp_kat_id==$temp_id_kategorii_kb) $vv++;
		}
		
		if ($vv>0) {
	
			if ($vv==1) echo "&nbsp;|&nbsp;<font color=blue>$vv nowa odpowiedź</font>";
			if ($vv==2) echo "&nbsp;|&nbsp;<font color=blue>$vv nowe odpowiedzi</font>";
			if ($vv==3) echo "&nbsp;|&nbsp;<font color=blue>$vv nowe odpowiedzi</font>";
			if ($vv==4) echo "&nbsp;|&nbsp;<font color=blue>$vv nowe odpowiedzi</font>";
			
			if ($vv>4) echo "&nbsp;|&nbsp;<font color=blue>$vv nowych odpowiedzi</font>";
		}
		
		echo "<div id=opis style=display:$od>";
		echo "<i>$temp_opis</i>";
		echo "</div>";
		
		echo "</td>";

		echo "<td class=center>";
		echo "<a valign=absmiddle title=' Pokaż pytania w kategorii : $temp_nazwa '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_kb_pytania.php?id=$temp_kat_id&poziom=0&action=$action')\"></a>";
		echo "</td>";	
		// wylistowanie wszystkich podkategorii
		$i++;
		if ($totalrows_pk>0) {
			
			if ($action=='manage') 
			{
				echo "<td class=center>";
				if ($temp_status==0) echo "NIE<a title=' Aktywuj kategorię $temp_nazwa '><input class=imgoption type=image src=img/off.gif align=top onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=status&value=1&submit=1')\"></a>";
				if ($temp_status==1) echo "TAK<a title=' Deaktywuj kategorię $temp_nazwa '><input class=imgoption type=image src=img/on.gif align=top onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=status&value=0&submit=1')\"></a>";
				
				echo "</td>"; 

				echo "<td class=center>";
				if ($temp_access_for==0) echo "wszystkich<a title=' Ustaw widoczność kategorii : $temp_nazwa tylko dla administratorów '><input class=imgoption type=image src=img/off.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=dostep&value=9&submit=1')\"></a>";
				if ($temp_access_for==9) echo "administratorów<a title=' Ustaw widoczność kategorii : $temp_nazwa  dla wszystkich '><input class=imgoption type=image src=img/on.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=dostep&value=0&submit=1')\"></a>";
				echo "</td>";
				
				echo "<td class=center>";
				echo "<a title=' Dodaj podkategorię do kategorii : $temp_nazwa '><input class=imgoption type=image src=img/add.gif onclick=\"newWindow(540,295,'d_kb_kategoria.php?id=$temp_kat_id&poziom=1')\"></a>";	
				echo "<a title=' Edycja kategorii : $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(540,300,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=dane')\"></a>";		
				echo "<a title=' Usuń kategorię $temp_nazwa z listy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_kb_kategoria.php?id=$temp_kat_id&poziom=0')\"></a>";	
				echo "</td>";
			}
			
			echo "</tr>";

			$nr_pk = 1;
			while ($newArray_pk = mysql_fetch_array($result_pk)) 
			{
				$temp_pk_id1		= $newArray_pk['kb_kategoria_id'];
				$temp_pk_nazwa 		= $newArray_pk['kb_kategoria_nazwa'];
				$temp_pk_opis 		= $newArray_pk['kb_kategoria_opis'];
				$temp_pk_status		= $newArray_pk['kb_kategoria_status'];
				$temp_pk_access_for	= $newArray_pk['kb_kategoria_accesible_for'];			
				$i++;
				$i++;

				tbl_tr_highlight_dblClick_z_kb_kategorie2($i);
				echo "<input type=hidden name=pozid_$i id=pozid_$i value=$temp_pk_id1>";
				echo "<td colspan=1>";

				if ($nr_pk<$totalrows_pk) echo "<img class=imgoption src=img/tree_second.gif border=0 align=absmiddle width=16 height=16>";
				if ($nr_pk==$totalrows_pk) echo "<img class=imgoption src=img/tree_last.gif border=0 align=absmiddle width=16 height=16>";
				
				$nr_pk++;
				echo "<a href=# class=normalfont onclick=\"newWindow_r(800,600,'p_kb_pytania.php?id=$temp_pk_id1&poziom=0&action=$action')\">";

				echo "$temp_pk_nazwa";
				
				
				// wpisanie ilości pytań w danej podkategorii
				$sql_k_ilosc = "SELECT * FROM $dbname.serwis_kb_pytania WHERE (kb_kategoria_id=$temp_pk_id1)";
				$result_k_ilosc = mysql_query($sql_k_ilosc, $conn) or die($k_b);
				$totalrows_k_ilosc = mysql_num_rows($result_k_ilosc);
				// --------
				//echo "<i>($totalrows_k_ilosc)</i>&nbsp;";	
				//echo "&nbsp;|&nbsp;<b>$totalrows_k_ilosc</b>";
				echo "&nbsp;|&nbsp;Wątków: <b>$totalrows_k_ilosc</b>";
				echo "</a>";
								
				$data_graniczna = SubstractWorkingDays(7,date('Y-m-d H:i:s'));
				
				list($czy_sa_nowe_wpisy)=mysql_fetch_array(mysql_query("SELECT COUNT(kb_pytanie_id) FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_data>'$data_graniczna') and (kb_kategoria_id=$temp_pk_id1)", $conn));
				if ($czy_sa_nowe_wpisy>0) {
					if ($czy_sa_nowe_wpisy==1) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowy wątek</font>";
					if ($czy_sa_nowe_wpisy==2) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowe wątki</font>";
					if ($czy_sa_nowe_wpisy==3) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowe wątki</font>";
					if ($czy_sa_nowe_wpisy==4) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowe wątki</font>";
					if ($czy_sa_nowe_wpisy>4) echo "&nbsp;|&nbsp;<font color=green>$czy_sa_nowe_wpisy nowych wątków</font>";
				}
				
				$result66 = mysql_query("SELECT kb_pytanie_id FROM $dbname.serwis_kb_odpowiedzi WHERE (kb_odpowiedz_data>'$data_graniczna')", $conn);
				$vv = 0;
				while ($newArray66 = mysql_fetch_array($result66)) {
					$temp_odp 		= $newArray66['kb_pytanie_id'];
					list($temp_id_kategorii_kb)=mysql_fetch_array(mysql_query("SELECT kb_kategoria_id FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_id='$temp_odp') ", $conn));
					if ($temp_pk_id1==$temp_id_kategorii_kb) $vv++;
				}
				
				if ($vv>0) {
			
					if ($vv==1) echo "&nbsp;|&nbsp;<font color=blue>$vv nowa odpowiedź</font>";
					if ($vv==2) echo "&nbsp;|&nbsp;<font color=blue>$vv nowe odpowiedzi</font>";
					if ($vv==3) echo "&nbsp;|&nbsp;<font color=blue>$vv nowe odpowiedzi</font>";
					if ($vv==4) echo "&nbsp;|&nbsp;<font color=blue>$vv nowe odpowiedzi</font>";
					
					if ($vv>4) echo "&nbsp;|&nbsp;<font color=blue>$vv nowych odpowiedzi</font>";
				}
		
				echo "<div id=opis style=display:$od>";
				if ($nr_pk<=$totalrows_pk) { echo "<img class=imgoption src=img/tree_opis.gif border=0 align=absmiddle width=16 height=16><i>$temp_pk_opis</i>"; } else {
					echo "<img class=imgoption src=img/tree_opis_last.gif border=0 align=absmiddle width=16 height=16><i>$temp_pk_opis</i>"; }
				echo "</div>";
				
				echo "</td>";

				echo "<td class=center>";
				echo "<a valign=absmiddle title=' Pokaż pytania w podkategorii : $temp_pk_nazwa '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_kb_pytania.php?id=$temp_pk_id1&poziom=1&action=$action')\"></a>";
				echo "</td>";				
				
				if ($action=='manage') 
				{		
					echo "<td class=center>";
					if ($temp_pk_status==0) echo "NIE<a title=' Aktywuj podkategorię $temp_nazwa->$temp_pk_nazwa '><input class=imgoption type=image align=top src=img/off.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_pk_id1&poziom=1&co=status&value=1&submit=1')\"></a>";
					if ($temp_pk_status==1) echo "TAK<a title=' Deaktywuj podkategorię $temp_nazwa->$temp_pk_nazwa '><input class=imgoption align=top type=image src=img/on.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_pk_id1&poziom=1&co=status&value=0&submit=1')\"></a>";
					echo "</td>"; 
					
					echo "<td class=center>";
					if ($temp_pk_access_for==0) echo "wszystkich<a title=' Ustaw widoczność kategorii : $temp_nazwa->$temp_pk_nazwa tylko dla administratorów '><input class=imgoption type=image src=img/off.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_pk_id1&poziom=0&co=dostep&value=9&submit=1')\"></a>";
					if ($temp_pk_access_for==9) echo "administratorów<a title=' Ustaw widoczność kategorii : $temp_nazwa->$temp_pk_nazwa  dla wszystkich '><input class=imgoption type=image src=img/on.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_pk_id1&poziom=0&co=dostep&value=0&submit=1')\"></a>";
					echo "</td>"; 
					
					echo "<td class=center>";
					echo "<a title=' Edycja podkategorii : $temp_nazwa->$temp_pk_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(540,300,'e_kb_kategoria.php?id=$temp_pk_id1&poziom=1&co=dane')\"></a>";		
					echo "<a title=' Usuń podkategorię $temp_nazwa->$temp_pk_nazwa z listy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_kb_kategoria.php?id=$temp_pk_id1&poziom=1')\"></a>";	
					echo "</td>";
				}
				echo "</tr>";
			}
		} else
		{
			
			if ($action=='manage') 
			{
				echo "<td class=center>";
				if ($temp_status==0) echo "NIE<a title=' Aktywuj kategorię $temp_nazwa '><input class=imgoption type=image src=img/off.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=status&value=1&submit=1')\"></a>";
				if ($temp_status==1) echo "TAK<a title=' Deaktywuj kategorię $temp_nazwa '><input class=imgoption type=image src=img/on.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=status&value=0&submit=1')\"></a>";
				echo "</td>"; 
				
				echo "<td class=center>";
				if ($temp_access_for==0) echo "wszystkich<a title=' Ustaw widoczność kategorii : $temp_nazwa tylko dla administratorów '><input class=imgoption type=image src=img/off.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=dostep&value=9&submit=1')\"></a>";
				if ($temp_access_for==9) echo "administratorów<a title=' Ustaw widoczność kategorii : $temp_nazwa  dla wszystkich '><input class=imgoption type=image src=img/on.gif onclick=\"newWindow(540,295,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=dostep&value=0&submit=1')\"></a>";
				echo "</td>";
				
				echo "<td class=center>";
				echo "<a title=' Dodaj podkategorię do kategorii : $temp_nazwa '><input class=imgoption type=image src=img/add.gif onclick=\"newWindow(540,295,'d_kb_kategoria.php?id=$temp_kat_id&poziom=1')\"></a>";
				echo "<a title=' Edycja kategorii : $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(540,300,'e_kb_kategoria.php?id=$temp_kat_id&poziom=0&co=dane')\"></a>";		
				echo "<a title=' Usuń kategorię $temp_nazwa z listy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_kb_kategoria.php?id=$temp_kat_id&poziom=0')\"></a>";	
				echo "</td>";
			}
			$i++;
			echo "</tr>";	
		}

	} 
	endtable();	

} else {
	errorheader("W Bazie Wiedzy nie zdefiniowano żadnych kategorii");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
}	

startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
if ($opis=='1') {
	addlinkbutton("'Ukryj opisy kategorii'","z_kb_kategorie.php?opis=0&action=$action");
} else
{
	addlinkbutton("'Pokaż opisy kategorii'","z_kb_kategorie.php?opis=1&action=$action");
}
echo "</span>";

if ($action=='manage') 
{
	addownlinkbutton("'Dodaj kategorię główną'","button1","button","newWindow(540,285,'d_kb_kategoria.php?poziom=0')");
	addownlinkbutton("'Dodaj podkategorię'","button1","button","newWindow(540,295,'d_kb_kategoria.php?poziom=1')");
}

addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
addownlinkbutton("'Dodaj nowy wątek'","button", "button","newWindow(700,500,'d_kb_pytanie.php?poziom=1&id=')");
//addbuttons("start");
endbuttonsarea();

include('body_stop.php');
?>
<script type="text/javascript">
//<![CDATA[

var listMenu = new FSMenu('listMenu', true, 'display', 'block', 'none');
listMenu.showDelay = 100;
listMenu.switchDelay = 125;
listMenu.hideDelay = 300;
listMenu.cssLitClass = 'highlighted';
//listMenu.showOnClick = 1;
listMenu.animInSpeed = 0.5;
listMenu.animOutSpeed = 0.5;

function animClipDown(ref, counter)
{
var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);
ref.style.clip = (counter==100 ? (window.opera ? '': 'rect(auto, auto, auto, auto)') :
'rect(0, ' + ref.offsetWidth + 'px, '+(ref.offsetHeight*cP)+'px, 0)');
};

function animFade(ref, counter)
{
var f = ref.filters, done = (counter==100);
if (f)
{
if (!done && ref.style.filter.indexOf("alpha") == -1)
ref.style.filter += ' alpha(opacity=' + counter + ')';
else if (f.length && f.alpha) with (f.alpha)
{
if (done) enabled = false;
else { opacity = counter; enabled=true }
}
}
else ref.style.opacity = ref.style.MozOpacity = counter/100.1;
};

// I'm applying them both to this menu and setting the speed to 20%. Delete this to disable.
			//listMenu.animations[listMenu.animations.length] = animFade;
			//listMenu.animations[listMenu.animations.length] = animClipDown;
			//listMenu.animations[listMenu.animations.length] = FSMenu.animFade;
			//listMenu.animations[listMenu.animations.length] = FSMenu.animSwipeDown;

			//listMenu.animSpeed = 500;

			var arrow = null;
			if (document.createElement && document.documentElement)
			{
				arrow = document.createElement('img');
				arrow.src = 'img/menu.gif';
				arrow.style.borderWidth = '0';
				arrow.className = 'subind';
				arrow.width = '16';
				arrow.height = '16';
			}

			addEvent(window, 'load', new Function('listMenu.activateMenu("listMenuRoot", arrow)'));

			//]]>
</script>
<script>HideWaitingMessage();</script>
</body>
</html>