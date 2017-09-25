<?php 
include_once('header.php');
include_once('cfg_helpdesk.php');

$komorka99 = $_REQUEST[wk];

	$sql = "SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa, serwis_komorki.up_umowa_id, serwis_komorki.up_adres, serwis_komorki.up_telefon, serwis_komorki.up_nrwanportu, serwis_komorki.up_ip, serwis_komorki.up_working_time, serwis_komorki.up_working_time_alternative, serwis_komorki.up_working_time_alternative_start_date, serwis_komorki.up_working_time_alternative_stop_date, serwis_komorki.up_typ_uslugi, serwis_komorki.up_kategoria, serwis_komorki.up_przypisanie_jednostki FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (UCASE(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='".$komorka99."'))) LIMIT 1";
	$rsd = mysql_query($sql,$conn_hd);
	$rsd_c = mysql_num_rows($rsd);
	
	echo "";
	
if ($rsd_c==1) {
	
	while($rs = mysql_fetch_array($rsd)) {
		$cid = $rs['up_id'];
		$cnazwa = $rs['up_nazwa'];
		$cpion = $rs['pion_nazwa'];
		$cumid = $rs['up_umowa_id'];
		
		$clok = $rs['up_adres'];
		$ctel = $rs['up_telefon'];
		$cnrwanportu = $rs['up_nrwanportu'];
		$cip = $rs['up_ip'];
		
		$cwt = $rs['up_working_time'];
		$cwta = $rs['up_working_time_alternative'];
		$cwta1 = $rs['up_working_time_alternative_start_date'];
		$cwta2 = $rs['up_working_time_alternative_stop_date'];
		
		$ctu = $rs['up_typ_uslugi'];
		$cku = $rs['up_kategoria'];
		$cpj = $rs['up_przypisanie_jednostki'];
	}
	
	?>

	<script>
		
		$("#hdtu").val('<?php echo $ctu; ?>');		
		$("#kategoria_komorki").val('<?php echo $cku; ?>');		
		$("#przypisanie_jednostki").val('<?php echo $cpj; ?>');		
		
		$("#up_list_id").val('<?php echo $cid; ?>');
		$("#up_wanport").val('<?php echo $cnrwanportu; ?>');

		$("#up_lokalizacja").val('<?php echo $clok; ?>');
		$("#up_nazwa1").val('<?php echo $cnazwa; ?>');

		$("#up_telefon").val('<?php echo $ctel; ?>'); 
		<?php if ($ctel!='') { ?>
			$("#tel_do_komorki_title").show();
			$("#tel_do_komorki").val('<?php echo $ctel; ?>');
		<?php } ?>

		$("#up_ip1").val('<?php echo $cip; ?>');
		<?php if ($cip!='') { ?>
			$("#adres_podsieci_title").show();
			$("#up_adres_podsieci").val('<?php echo $cip; ?>');
		<?php } ?>

		$('#lista_zgloszen_data').load('wait_ajax.php?randval='+ Math.random());	
		$('#lista_zgloszen_data').load('hd_g_lista_zgloszen.php?randval='+ Math.random() +'&komorka=<?php echo urlencode($komorka99); ?>');

		var data8 = '<?php echo $cwt; ?>';
		var data9 = '<?php echo $cwta; ?>';
		var data10 = '<?php echo $cwta1; ?>';
		var data11 = '<?php echo $cwta2; ?>';

					if (data8!='') {
						var pn = data8.split(";")[0].split("@");	$("#PN").text(pn[1]); 
						var wt = data8.split(";")[1].split("@");	$("#WT").text(wt[1]); 
						var sr = data8.split(";")[2].split("@");	$("#SR").text(sr[1]); 
						var cz = data8.split(";")[3].split("@");	$("#CZ").text(cz[1]); 
						var pt = data8.split(";")[4].split("@");	$("#PT").text(pt[1]); 
						var so = data8.split(";")[5].split("@");	$("#SO").text(so[1]); 
						var ni = data8.split(";")[6].split("@");	$("#NI").text(ni[1]); 
						$("#none_gp").hide();
						$("#gp_default").hide();
						$("#gp").show();
						$("#gpa").hide();
					} else {
						$("#PN").text('?'); 
						$("#WT").text('?'); 
						$("#SR").text('?'); 
						$("#CZ").text('?'); 
						$("#PT").text('?'); 
						$("#SO").text('?'); 
						$("#NI").text('?'); 
								
						$("#gp").hide();
						$("#gpa").hide();
						$("#none_gp").show();
						$("#gp_default").show();
						
					}
					
					if ((data10!='0000-00-00') && (data11!='0000-00-00')) {
						var s1 = data10;
						var s2 = data11;
						var y = $('#hddz').val();
					
						y1 = y.substring(0,5);
						
						s1 = y1 + s1.substring(5,10);
						s2 = y1 + s2.substring(5,10);
						
						if ((y>=s1) && (y<=s2)) {
							$("#gp_active").hide();
							$("#gpa_active").show();
							$("#gpa").show();
							$("#gp").hide();
						} else {
							$("#gp_active").show();
							$("#gpa_active").hide();
							$("#gp").show();
							$("#gpa").hide();
						}
						
						$("#gpa_start").text(s1);
						$("#gpa_stop").text(s2);
						
						if (data9!='') {
							var pn = data9.split(";")[0].split("@");	$("#PNa").text(pn[1]); 
							var wt = data9.split(";")[1].split("@");	$("#WTa").text(wt[1]); 
							var sr = data9.split(";")[2].split("@");	$("#SRa").text(sr[1]); 
							var cz = data9.split(";")[3].split("@");	$("#CZa").text(cz[1]); 
							var pt = data9.split(";")[4].split("@");	$("#PTa").text(pt[1]); 
							var so = data9.split(";")[5].split("@");	$("#SOa").text(so[1]); 
							var ni = data9.split(";")[6].split("@");	$("#NIa").text(ni[1]); 
							$("#none_gp").hide();
							$("#gp_default").hide();
							//$("#gpa").show();
						} else {
							$("#PNa").text('?'); 
							$("#WTa").text('?'); 
							$("#SRa").text('?'); 
							$("#CZa").text('?'); 
							$("#PTa").text('?'); 
							$("#SOa").text('?'); 
							$("#NIa").text('?'); 
							
							$("#none_gp").show();
							$("#gp_default").show();
							$("#gp").hide();
							$("#gpa").hide();
						}		
					}	
	</script>

<?php 	
	}
?>