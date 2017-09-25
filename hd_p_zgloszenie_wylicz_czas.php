<?php 
include_once('header_simple.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

$dd = Date('Y-m-d');

list($status_zgloszenia,$kategoria_zgloszenia,$CzasDoZakon, $CzasDoRozp)=mysql_fetch_array(mysql_query("SELECT zgl_status, zgl_kategoria, zgl_data_zakonczenia, zgl_data_rozpoczecia FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd));

					if ((($status_zgloszenia==1) || ($status_zgloszenia==2)) && ($CzasDoRozp!='0000-00-00 00:00:00')) { // status : nowe -> poka¿ czas do ropoczêcia zg³oszenia
						
						$pokazczas = HD_RoznicaDat(date("Y-m-d H:i:s"),$CzasDoRozp);
						$pokazczas_w_s = HD_RoznicaDat_w_s(date("Y-m-d H:i:s"),$CzasDoRozp);
				
						// NIE POKAZUJ OSTRZE¯EÑ DLA WYBRANYCH STATUSÓW
						$ostrzezenia_on_dla = array("1","2","3","7"); 
						if (array_search($status_zgloszenia, $ostrzezenia_on_dla)>-1){
					
							if ($pokazczas_w_s<=$HD_prog_ostrzezenia_o_nierozpoczeciu_zgloszenia) {
								if ($pokazczas[0]!='-') echo $pokazczas;
							}
						}
					}
					
					if ((($status_zgloszenia!=9) && ($status_zgloszenia!=1) && ($status_zgloszenia!=2)) && ($CzasDoZakon!='0000-00-00 00:00:00')) { // je¿eli nie zakoñczone -> poka¿ czas do zakoñczenia
						$pokazczas = HD_RoznicaDat(date("Y-m-d H:i:s"),$CzasDoZakon);
						$pokazczas_w_s = HD_RoznicaDat_w_s(date("Y-m-d H:i:s"),$CzasDoZakon);
						
						// NIE POKAZUJ OSTRZE¯EÑ DLA WYBRANYCH STATUSÓW
						$ostrzezenia_on_dla = array("1","2","3","7"); 
						if (array_search($status_zgloszenia, $ostrzezenia_on_dla)>-1){
					
							if ($pokazczas_w_s<=$HD_prog_ostrzezenia_o_niezakonczeniu_zgloszenia) {
								if ($pokazczas[0]!='-') echo $pokazczas;
							}
						}
					}
					
?><script>
//document.getElementById("ilosc_godzin").innerHTML='<font color=blue>&nbsp;<?php echo $RazemDzien; ?></font>';
</script>