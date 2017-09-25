<?php
$pw = array($_f."/hd_d_zgloszenie.php",$_f."/hd_e_zgloszenie_new.php",$_f."/d_zadanie.php",$_f."/e_zadanie.php");
if (array_search($PHP_SELF, $pw)>-1) { ?>
	<script>
	function formatTime(time) {
		var result1 = false, m;
		var re = /^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/;
		if ((m = time.match(re))) {
			//result1 = (m[1].length == 2 ? "" : "0") + m[1] + ":" + m[2];
			result1 = true;
		}
		return result1;
	}
	function CheckTime2(time) {
		var result1 = false, m;
		var re = /^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/;
		if ((m = time.match(re))) {
			result1 = true;
		}
		alert('Błędnie wpisany czas'); document.getElementById('nowy_czas_rozpoczecia').value=''; document.getElementById('nowy_czas_rozpoczecia').focus();
		return result1;
	}
	function CheckTimePrzesuniecia(t) {
		if (t=='') return true;
		if (formatTime(t)==false) {
			//if (t=='') return true;
			alert('Błędnie wpisana godzina przesunięcia'); document.getElementById('nowy_czas_rozpoczecia').value=''; document.getElementById('nowy_czas_rozpoczecia').focus(); return false;
		} 
	}
	function DopiszKreski(v) {
		var r = document.getElementById(v).value.length;
		if (r==4) document.getElementById(v).value = document.getElementById(v).value+'-';
		if (r==7) document.getElementById(v).value = document.getElementById(v).value+'-';
	}
	function SzybkiSkokZeStatusu(el) { 
		var kategoria_select = document.getElementById('kat_id').value; 
		var podkategoria_select = document.getElementById('podkat_id').value; 
		var status_select = document.getElementById('status_id').value; 
		if (kategoria_select=='1') {
			if (status_select=="9") document.getElementById('czas_wykonywania_h').focus();
		}
		
		if (kategoria_select=='2') {
			if (status_select=="1") document.getElementById('czas_wykonywania_h').focus();
			if (status_select=="2") document.getElementById('PrzypiszDoOsobyValue').focus();
			if (status_select=="9") document.getElementById('czas_wykonywania_h').focus();
		}
		if (kategoria_select=='3') {
			if (status_select=="1") document.getElementById('czas_wykonywania_h').focus();
			if (status_select=="2") document.getElementById('PrzypiszDoOsobyValue').focus();
			if (status_select=="9") document.getElementById('czas_wykonywania_h').focus();
		}
		if (kategoria_select=='4') {
			if (status_select=="1") document.getElementById('czas_wykonywania_h').focus();
			if (status_select=="2") document.getElementById('PrzypiszDoOsobyValue').focus();
			if (status_select=="3") document.getElementById('SelectZdiagnozowany').focus();
			if (status_select=="7") document.getElementById('SelectZdiagnozowany').focus();
			if (status_select=="9") document.getElementById('czas_wykonywania_h').focus();
		}
		if (kategoria_select=='5') {
			if (status_select=="1") document.getElementById('czas_wykonywania_h').focus();
			if (status_select=="2") document.getElementById('PrzypiszDoOsobyValue').focus();
			if (status_select=="3") document.getElementById('SelectZdiagnozowany').focus();
			if (status_select=="7") document.getElementById('SelectZdiagnozowany').focus();
			if (status_select=="9") document.getElementById('czas_wykonywania_h').focus();
		}
		
	}
	function SzybkiSkokZKategorii(val) {
		var kateg_select = document.getElementById('kat_id').value;
		if (kateg_select.value=="1") document.getElementById('czas_wykonywania_h').focus();
		if (kateg_select.value=="5") document.getElementById('status_id').focus();
	}
	function AddToList(listname, list_opis, list_value, bool1, bool2) {
		listname.options[listname.options.length] = new Option(""+list_opis+"",""+list_value+"",bool1, bool2);
	}
	function MakePodkategoriaList(o) {
		if (document.getElementById('kat_id')) {
			var kateg_select = document.getElementById('kat_id');
		} else {
			var kateg_select = document.getElementById('dz_kat');
		}
		
		if (document.getElementById('podkat_id')) {
			var ps = document.getElementById('podkat_id');
			ps.options.length=0;
		} else {
			var ps = document.getElementById('dz_podkat');
			ps.options.length=0;
		}
		
		if (document.getElementById('sub_podkat_id')) {
			var sps = document.getElementById('sub_podkat_id');
			sps.options.length=0;
		}
		
		if (document.getElementById('priorytet_id')) var prs = document.getElementById('priorytet_id');
		if (prs) prs.options.length=0;
		if (document.getElementById('status_id')) var ss = document.getElementById('status_id');

		if ((!document.getElementById('NewDefaultPodkat_id')) && ((document.getElementById('status_id')))) ss.options.length=0;
		
		if (document.getElementById('WyslijEmail')) var swe = document.getElementById('WyslijEmail');
		if (document.getElementById('WyslijEmailCheckbox')) sweCheckbox = document.getElementById('WyslijEmailCheckbox');
		
		if (swe) swe.style.display='none';
		if (document.getElementById('czy_synchronizowac')) document.getElementById('czy_synchronizowac').checked=true;		
		
		if (o == "7") {
			ps.options[ps.options.length] = new Option("Brak","1",true,true);
			ps.disabled=false;
			if (prs) prs.options[prs.options.length] = new Option("standard","2",true,true);
			if (prs) prs.disabled=false;
			
			if (document.getElementById('status_id')) {
				ss.options[ss.options.length] = new Option("nowe","1",true,true);
				ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
				ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);
				ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
				ss.disabled=false;
			}
			
			AddToList(sps,'Brak','',true,true);
			
			if (document.getElementById('WyslijEmailCheckbox')) 
				if (sweCheckbox) sweCheckbox.checked=false;
		}
		
		if (o == "1") {
			ps.options[ps.options.length] = new Option("Brak","1",true,true);
			ps.disabled=false;
			if (prs) prs.options[prs.options.length] = new Option("standard","2",true,true);
			if (prs) prs.disabled=false;
			
			if (document.getElementById('status_id')) {
				ss.options[ss.options.length] = new Option("zamknięte","9",true,true);
				ss.disabled=false;
			}
			AddToList(sps,'Sprzęt','Sprzęt',false,false);
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'Inne','Inne',false,false);
			
			if (document.getElementById('WyslijEmailCheckbox'))
				if (sweCheckbox) sweCheckbox.checked=false;
		}
		if (o == "2") {
			//ps.options[ps.options.length] = new Option("Oprogramowanie biurowe","5",false,false);
			//ps.options[ps.options.length] = new Option("Oprogramowanie techniczne","7",false,false);
			ps.options[ps.options.length] = new Option("Serwer","4",false,false);
			ps.options[ps.options.length] = new Option("Stacja robocza","3",true,true);
			ps.options[ps.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",false,false);
			ps.options[ps.options.length] = new Option("WAN/LAN","0",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
			ps.disabled=false;
			
			AddToList(sps,'Sprzęt','Sprzęt',true,true);
			AddToList(sps,'Oprogramowanie','Oprogramowanie',false,false);
			
			if (document.getElementById('NewDefaultPodkat_id')) ps.value=document.getElementById('NewDefaultPodkat_id').value;
			
			if (prs) prs.options[prs.options.length] = new Option("standard","2",true,true);
			if (prs) prs.options[prs.options.length] = new Option("wysoki","3",false,false);	
			if (prs) prs.options[prs.options.length] = new Option("krytyczny","4",false,false);	
			if (prs) prs.disabled=false;	
			
			if (document.getElementById('status_id')) {
				if (!document.getElementById('NewDefaultPodkat_id')) {
					ss.options[ss.options.length] = new Option("nowe","1",false,false);
					ss.disabled=false;
				}
			}
			
			if (swe) swe.style.display='';
			
			if (document.getElementById('WyslijEmailCheckbox'))
				if (sweCheckbox) sweCheckbox.checked=true;	
			
		}
		// awaria krytyczna
		if (o == "6") {
			//ps.options[ps.options.length] = new Option("Oprogramowanie biurowe","5",false,false);
			//ps.options[ps.options.length] = new Option("Oprogramowanie","2",false,false);
			ps.options[ps.options.length] = new Option("Serwer","4",true,true);
			ps.options[ps.options.length] = new Option("Stacja robocza","3",false,false);
			ps.options[ps.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps.options[ps.options.length] = new Option("WAN/LAN","0",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
			
			ps.disabled=false;
			if (prs) prs.options[prs.options.length] = new Option("standard","2",true,true);
			if (prs) prs.options[prs.options.length] = new Option("wysoki","3",false,false);	
			if (prs) prs.options[prs.options.length] = new Option("krytyczny","4",false,false);	
			if (prs) prs.disabled=false;	
			
			AddToList(sps,'Sprzęt','Sprzęt',true,true);
			AddToList(sps,'Oprogramowanie','Oprogramowanie',false,false);
			
			if (document.getElementById('status_id')) {
				ss.options[ss.options.length] = new Option("nowe","1",false,false);
				ss.disabled=false;
			}
			
			if (swe) swe.style.display='';
			if (document.getElementById('WyslijEmailCheckbox'))
				if (sweCheckbox) sweCheckbox.checked=true;	
		}
		
		if (o == "3") {
			ps.options[ps.options.length] = new Option("Serwer","4",false,false);
			ps.options[ps.options.length] = new Option("Stacja robocza","3",false,false);
			ps.options[ps.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			
			//ps.options[ps.options.length] = new Option("Konserwacja sprzętu","8",false,false);
			//ps.options[ps.options.length] = new Option("Oprogramowanie biurowe","5",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",true,true);
			ps.options[ps.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);

			ps.options[ps.options.length] = new Option("Aktualizacje oprogramowania","6",false,false);

			<?php $pw = array($_f."/d_zadanie.php",$_f."/e_zadanie.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
			ps.options[ps.options.length] = new Option("Projekty","G",false,false);
			<?php } ?>
	
			//ps.options[ps.options.length] = new Option("Projekty","G",false,false);
			ps.options[ps.options.length] = new Option("Kopie bezpieczeństwa","H",false,false);
			ps.options[ps.options.length] = new Option("Domena","I",false,false);
			
			ps.options[ps.options.length] = new Option("Alarmy","E",false,false);
			ps.options[ps.options.length] = new Option("WAN/LAN","0",false,false);
			
			ps.options[ps.options.length] = new Option("Inne","D",false,false);

			ps.options[ps.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps.options[ps.options.length] = new Option("Przeniesienie placówki pocztowej","B",false,false);
			ps.options[ps.options.length] = new Option("Zamknięcie placówki pocztowej","C",false,false);
			
			ps.options[ps.options.length] = new Option("Prace administracyjno-sprawozdawcze","F",false,false);
			
			ps.disabled=false;
			
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'MBP','MBP',false,false);
			AddToList(sps,'ŁP14','ŁP14',false,false);
			AddToList(sps,'MRU','MRU',false,false);
			AddToList(sps,'SEDI','SEDI',false,false);
			AddToList(sps,'MRUm','MRUm',false,false);
			AddToList(sps,'ZST WiemPost','ZST WiemPost',false,false);
			AddToList(sps,'Reklamacje','Reklamacje',false,false);
			AddToList(sps,'Lotus Notes','Lotus Notes',false,false);
			AddToList(sps,'Office','Office',false,false);
			AddToList(sps,'Przeglądarka internetowa','Przeglądarka internetowa',false,false);
			AddToList(sps,'Outlook Express','Outlook Express',false,false);
			AddToList(sps,'S.N.O.S.P.','S.N.O.S.P.',false,false);
			AddToList(sps,'PenGuard','PenGuard',false,false);			
			AddToList(sps,'Acrobat Reader','Acrobat Reader',false,false);
			AddToList(sps,'Obsługa systemu operacyjnego','Obsługa systemu operacyjnego',false,false);
			AddToList(sps,'Inne','Inne',false,false);
			
			if (document.getElementById('NewDefaultPodkat_id')) ps.value=document.getElementById('NewDefaultPodkat_id').value;
						
			if (prs) prs.options[prs.options.length] = new Option("niski","1",false,false);
			if (prs) prs.options[prs.options.length] = new Option("standard","2",true,true);	
			if (prs) prs.options[prs.options.length] = new Option("wysoki","3",false,false);	
			if (prs) prs.disabled=false;
		
			if (document.getElementById('status_id')) {
				if (!document.getElementById('NewDefaultPodkat_id')) {
					ss.options[ss.options.length] = new Option("nowe","1",true,true);
					//ss.options[ss.options.length] = new Option("przypisane","2",true,true);
					ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
					ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);
					ss.options[ss.options.length] = new Option("w firmie","3B",false,false);	
					//ss.options[ss.options.length] = new Option("w serwisie zewnętrznym","3A",false,false);			
					ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
					ss.disabled=false;	
				}
			}
			
			if (document.getElementById('WyslijEmailCheckbox'))
				if (sweCheckbox) sweCheckbox.checked=false;		
			
		}
		
		if (o == "4") {
			ps.options[ps.options.length] = new Option("Inne","D",false,false);
			ps.options[ps.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps.disabled=false;
			if (prs) prs.options[prs.options.length] = new Option("standard","2",true,true);
			if (prs) prs.disabled=false;
			
			if (document.getElementById('status_id')) {
				ss.options[ss.options.length] = new Option("nowe","1",true,true);
				//ss.options[ss.options.length] = new Option("przypisane","2",true,true);
				ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
				ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);		
				ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
				ss.disabled=false;
			}
			AddToList(sps,'Brak','',true,true);
				
			if (document.getElementById('WyslijEmailCheckbox'))
				if (sweCheckbox) sweCheckbox.checked=false;		
		}
		if (o == "5") {
			ps.options[ps.options.length] = new Option("Brak","1",true,true);
			ps.disabled=false;
			if (prs) prs.options[prs.options.length] = new Option("standard","2",true,true);
			if (prs) prs.disabled=false;
			
			if (document.getElementById('status_id')) {
				ss.options[ss.options.length] = new Option("nowe","1",true,true);
				//ss.options[ss.options.length] = new Option("przypisane","2",true,true);
				ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
				ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);		
				ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
				ss.disabled=false;
			}
			
			AddToList(sps,'Brak','',true,true);
			
			if (document.getElementById('WyslijEmailCheckbox'))
				if (sweCheckbox) sweCheckbox.checked=false;		
			// jezeli kategoria to "Prace na potrzeby Postdata" -> odznacz widocznosc zgloszenia dla Poczty
			
			document.getElementById('czy_synchronizowac').checked=false;
		}	
		
	}
	
	$().ready(function() {
		$("#up_list").autocomplete("hd_get_up_list.php?filia=<?php echo $es_filia; ?>", {
			width: 360,
			max: 150,
			matchContains: false,
			mustMatch: false,
			minChars: 1,
			//multiple: true,
			//highlight: false,
			//multipleSeparator: ",",
			selectFirst: false
		});
		$("#up_list").result(function(event, data, formatted) { $("#up_list_id").val(data[1]); });
		$("#up_list").result(function(event, data, formatted) { $("#up_wanport").val(data[3]); 
			if ((data[3])=='') {
				$("#BlokAwariaWAN").hide();
				$("#BrakWANportuKomunikat").show("slow");	
			} else {
				$("#BrakWANportuKomunikat").hide("slow");	
				$("#BlokAwariaWAN").show();
				$("#numerzgloszenia").show();
				$("#numerzgloszenia").val('');
			}
		});
		$("#up_list").result(function(event, data, formatted) { $("#up_lokalizacja").val(data[4]); });	
		$("#up_list").result(function(event, data, formatted) { $("#up_telefon").val(data[5]); });
		$("#up_list").result(function(event, data, formatted) { $("#up_nazwa1").val(data[7]); });
		$("#up_list").result(function(event, data, formatted) { $("#up_ip1").val(data[6]); });	
		
		$("#up_list").result(function(event, data, formatted) { 
			if ((data[1])!='') {
				$("#EW_S").show();
			}
			
			if (data[8]!='') {
				var pn = data[8].split(";")[0].split("@");	$("#PN").text(pn[1]); 
				var wt = data[8].split(";")[1].split("@");	$("#WT").text(wt[1]); 
				var sr = data[8].split(";")[2].split("@");	$("#SR").text(sr[1]); 
				var cz = data[8].split(";")[3].split("@");	$("#CZ").text(cz[1]); 
				var pt = data[8].split(";")[4].split("@");	$("#PT").text(pt[1]); 
				var so = data[8].split(";")[5].split("@");	$("#SO").text(so[1]); 
				var ni = data[8].split(";")[6].split("@");	$("#NI").text(ni[1]); 
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
			
			document.getElementById('test_g3').value = data[8];
			
			if ((data[10]!='0000-00-00') && (data[11]!='0000-00-00')) {
				var s1 = data[10];
				var s2 = data[11];
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
				
				if (data[9]!='') {
					var pn = data[9].split(";")[0].split("@");	$("#PNa").text(pn[1]); 
					var wt = data[9].split(";")[1].split("@");	$("#WTa").text(wt[1]); 
					var sr = data[9].split(";")[2].split("@");	$("#SRa").text(sr[1]); 
					var cz = data[9].split(";")[3].split("@");	$("#CZa").text(cz[1]); 
					var pt = data[9].split(";")[4].split("@");	$("#PTa").text(pt[1]); 
					var so = data[9].split(";")[5].split("@");	$("#SOa").text(so[1]); 
					var ni = data[9].split(";")[6].split("@");	$("#NIa").text(ni[1]); 
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
				
				document.getElementById('test_g3').value = data[10];
			}
		});	
		$("#up_list").result(function(event, data, formatted) { 	
			
			$('#lista_zgloszen_data').load('wait_ajax.php?randval='+ Math.random());	
			$('#lista_zgloszen_data').load('hd_g_lista_zgloszen.php?randval='+ Math.random() +'&komorka='+urlencode1(data[0]));	
			
		});	
		
		$("#up_list").result(function(event, data, formatted) { 
			var ku = data[13];
			$("#kategoria_komorki").val(ku);
			
			var pj = data[14];
			$("#przypisanie_jednostki").val(pj);
		});
		
		$("#up_list").result(function(event, data, formatted) { 
			$('#nowa_data_rozpoczecia').val(0);
			//$('#nowa_data_rozpoczecia2').val('');
			if ((data[12]!='')) {
				var typu = data[12];
				var typu_select = document.getElementById('hdtu');
				typu_select.options.length=0;
				$("#up_typ_uslugi").show();
				if ((typu.indexOf(','))<0) {
					// jeden typ wybrany				
					if (typu=='KOI') typu_select.options[typu_select.options.length] = new Option("Kompleksowa Obsługa Informatyczna (KOI)","KOI",true,false);
					if (typu=='OK') typu_select.options[typu_select.options.length] = new Option("Okresowa konserwacja (OK)","OK",true,false);
					if (typu=='UZ') typu_select.options[typu_select.options.length] = new Option("Usługi na żądanie (UZ)","UZ",true,false);
					if (typu=='UUAK') typu_select.options[typu_select.options.length] = new Option("Usługa Usuwania Awarii Krytycznych (UUAK)","UUAK",true,false);
					$("#up_typ_uslugi").hide();
					
				} else {
					// wiele typów wybranych
					var typu1 = typu.split(",");
					if ((typu1[0]=='KOI') || (typu1[1]=='KOI') || (typu1[2]=='KOI') || (typu1[3]=='KOI')) typu_select.options[typu_select.options.length] = new Option("Kompleksowa Obsługa Informatyczna (KOI)","KOI",true,false);
					
					if ((typu1[0]=='OK') || (typu1[1]=='OK') || (typu1[2]=='OK') || (typu1[3]=='OK')) typu_select.options[typu_select.options.length] = new Option("Okresowa konserwacja (OK)","OK",false,false);
					
					if ((typu1[0]=='UZ') || (typu1[1]=='UZ') || (typu1[2]=='UZ') || (typu1[3]=='UZ')) typu_select.options[typu_select.options.length] = new Option("Usługi na żądanie (UZ)","UZ",false,false);
					
					if ((typu1[0]=='UUAK') || (typu1[1]=='UUAK') || (typu1[2]=='UUAK') || (typu1[3]=='UUAK')) typu_select.options[typu_select.options.length] = new Option("Usługa Usuwania Awarii Krytycznych (UUAK)","UUAK",false,false);
					
					typu_select.disabled=false;
				}			
			}
		});	
		
		$("#up_list").result(function(event, data, formatted) { 
			if ((data[6]!='')) {
				$("#up_adres_podsieci").val(data[6]); 
				$("#adres_podsieci_title").show();
			} else {
				$("#up_adres_podsieci").val(''); 
				$("#adres_podsieci_title").hide();	
				$("#tel_do_komorki").val(''); 
				$("#tel_do_komorki_title").hide();					
			}
		});	
		
		$("#up_list").result(function(event, data, formatted) { 
			if ((data[5]!='')) {
				$("#tel_do_komorki").val(data[5]); 
				$("#tel_do_komorki_title").show();
			} else {
				$("#tel_do_komorki").val(''); 
				$("#tel_do_komorki_title").hide();		
			}
			
		/*	var sss = $('#up_list').val();
			sss = sss.toUpperCase();
			$('#up_list').val(""+sss+"");
		*/
		});	
		
		$("#hd_oz").autocomplete("hd_get_pracownik_list.php", {
			width: 360,
			max:100,
			matchContains: true,
			mustMatch: false,
			minChars: 1,
			extraParams: { komorka: function() { return $("#up_list_id").val(); } }, 
			//multiple: true,
			//highlight: false,
			//multipleSeparator: ",",
			selectFirst: false
		});
		
		//$("#hd_oz").result(function(event, data, formatted) { $("#hd_oz_id").val(data[1]); 	});  
		$("#hd_oz").result(function(event, data, formatted) { $("#hdoztelefon").val(data[1]); 	});  
		$("#hd_opp").autocomplete("hd_get_pracownik_list.php", {
			width: 360,
			max:100,
			matchContains: true,
			mustMatch: false,
			minChars: 1,
			extraParams: { komorka: function() { return $("#up_list_id").val(); } }, 
			//multiple: true,
			//highlight: false,
			//multipleSeparator: ",",
			selectFirst: false
		});
		$("#hd_opz").autocomplete("hd_get_pracownik_list.php", {
			width: 360,
			max:100,
			matchContains: true,
			mustMatch: false,
			minChars: 1,
			extraParams: { komorka: function() { return $("#up_list_id").val(); } }, 
			//multiple: true,
			//highlight: false,
			//multipleSeparator: ",",
			selectFirst: false
		});
		
	});
	
	function StatusChanged(v, v1, v2) {
		var zawartosc = '';
		var rowpr = document.getElementById("priorytet_id").value;
		var row = document.getElementById("StatusZakonczony");
		var row2 = document.getElementById("ZasadnoscZgloszenia");
		var row2a = document.getElementById("OsobaPotwierdzajacaZamkniecie");
		var row3 = document.getElementById("WpiszWyjazdTrasa");
		var row4 = document.getElementById("WpiszWyjazdKm");
		var row5 = document.getElementById("PowiazaneZWyjazdem");	
		var row5a = document.getElementById("PozwolWpisacKm");
		var row5b = document.getElementById("DataWyjazdu");
		var row6 = document.getElementById("PrzypiszDoOsoby");
		var row7 = document.getElementById("AwariaWAN");
		var row8 = document.getElementById("UstalonaDataZakonczenia");
		var row8ch = document.getElementById("UstalonaDataZakonczeniaTR");
		var row8data = document.getElementById("nowa_data_zakonczenia");
		var row9 = document.getElementById("Zdiagnozowany");
		var row10 = document.getElementById("AkceptacjaKosztow");
		var row11 = document.getElementById("NrZgloszeniaGdansk");
		var podkat = document.getElementById("podkat_id");
		var row_ww = document.getElementById("show_ww");
		
		var czas = 0;
		// standardowy widok (czysty)
		if (row) row.style.display = 'none';
		if (row2) row2.style.display = 'none';
		if (row2a) row2a.style.display = 'none';
		if (row6) row6.style.display = 'none';
		if (row8ch) row8ch.style.display = 'none';
		if (row8) row8.style.display = 'none';
		if (row9) row9.style.display = 'none';
		if (row5) row5.style.display = 'none';
		if (row7) row7.style.display = 'none';
		if (row_ww) row_ww.style.display = '';
		
		if (row5b) row5b.style.display = 'none';
		if (document.getElementById('trasa')) document.getElementById('trasa').value='';
		if (document.getElementById('km')) document.getElementById('km').value='';
		
		if (row3) row3.style.display = 'none';
		if (row4) row4.style.display = 'none';
		
		if (row9) {row9.style.display = 'none'; row9.value='';}
		if (row10) {row10.style.display = 'none'; row10.value='';}
		if (row11) {row11.style.display = 'none'; row11.value='';}
		
		if (document.getElementById('hd_opz')) document.getElementById('hd_opz').value = '';
		if (document.getElementById('UstalonaDataZakonczeniaCheck')) document.getElementById('UstalonaDataZakonczeniaCheck').checked = false;
		if (document.getElementById('PozwolWpisacKm')) document.getElementById('PozwolWpisacKm').checked = false;
		if (document.getElementById('DataWyjazdu')) document.getElementById('DataWyjazdu').checked = false;
		
		// warianty dla kategorii: Konsultacje
		if (v==1) { 
			row.style.display = '';
			row2.style.display = '';
			row2a.style.display = '';
			row_ww.style.display = 'none';
		}
		if (v==7) { 
			row.style.display = '';
			if (v1==9) {	
				row2a.style.display = '';
				row2.style.display = '';
			}
			row_ww.style.display = 'none';
			if ((v1==3) || (v1==7) || (v1==9)) {
				row5.style.display = ''; // km
			}
		}		
		// warianty dla kategorii: Awarie
		if (v==2) {
			row.style.display = '';
			if (v1==2) row6.style.display = '';
			if ((v1==3) || (v1==7) || (v1=='3B') || (v1==9)) {
				row5.style.display = ''; // km
			}
			
			if (v1=='3A') {
				document.getElementById("AkceptacjaKosztow").value='';
				row5.style.display = ''; // km
			}
			if ((v1==1) || (v1==2) || (v1==3) || (v1==7)) {
				if (rowpr!='4') {
					row8.style.display = '';
				} else {
					row8.style.display = 'none';
				}
			}
			if ((podkat.value==0) && (v1=='3A')) row7.style.display=''; 		
			if (v1==9) {
				row2.style.display = '';
				row2a.style.display = '';
			}
			
			if (((podkat.value=='2') || (podkat.value=='5') || (podkat.value=='7') || (podkat.value=='6')) && (v1=='3A')) {
				row11.style.display = '';
			} else {
				row11.style.display = 'none';
			}
			
			
		}
		// warianty dla kategorii: Awarie krytyczne
		if (v==6) {
			row.style.display = '';
			if (v1==2) row6.style.display = '';
			if ((v1==3) || (v1==7) || (v1=='3B') || (v1==9)) {
				row5.style.display = ''; // km
			}
			
			if ((v1==1) || (v1==2) || (v1==3) || (v1==7)) {
				if (document.getElementById('up_list_id').value>0) {
				
					//alert(document.getElementById('test_gSTOP').value);
					row8.style.display = 'none';				
					
				}
			}
			
			if (v1=='3A') {
				document.getElementById("AkceptacjaKosztow").value='';
				row5.style.display = ''; // km
			}
			if ((podkat.value==0) && (v1=='3A')) row7.style.display=''; 		
			if (v1==9) {
				row2.style.display = '';
				row2a.style.display = '';
			}
			
			if (((podkat.value=='2') || (podkat.value=='5') || (podkat.value=='7') || (podkat.value=='6')) && (v1=='3A')) {
				row11.style.display = '';
			} else {
				row11.style.display = 'none';
			}		
			
		}
		
		// warianty dla kategorii: Prace zlecone w ramach umowy
		if (v==3) {
			row.style.display = '';	
			if (v1==2) row6.style.display = '';
			if ((v1==3) || (v1==7) || (v1=='3A') || (v1=='3B') || (v1==9)) row5.style.display = ''; 
			if (v1==9) {
				row2.style.display = '';
				row2a.style.display = '';
			}
			
			if (((podkat.value=='2') || (podkat.value=='5') || (podkat.value=='7') || (podkat.value=='6')) && (v1=='3A')) {
				row11.style.display = '';
			} else {
				row11.style.display = 'none';
			}		
			
			if ((v1==3) || (v1==7) || (v1=='3B') || (v1==9)) {
				if ((podkat.value=='2') || 
						(podkat.value=='3') ||
						(podkat.value=='4') ||
						(podkat.value=='5') ||
						(podkat.value=='9') ||
						(podkat.value=='0') ||
						(podkat.value=='E') ||
						(podkat.value=='D')) {
					
				}
			}
			
		}
		// warianty dla kategorii: Prace zlecone poza umową
		if (v==4) {
			row.style.display = '';	
			if (v1==2) row6.style.display = '';
			if ((v1==3) || (v1==7) || (v1=='3A') || (v1=='3B') || (v1==9)) row5.style.display = ''; 
			if (v1==9) {
				row2.style.display = '';
				row2a.style.display = '';
			}
			if ((v1==3) || (v1==7) || (v1=='3B') || (v1==9)) {
				if ((podkat.value=='A') || (podkat.value=='D')) {
					//document.getElementById("SelectZdiagnozowany").value='';
					//if (v1!=9) row9.style.display = ''; // zdiagnozowano
				}
			}
			
		}
		// warianty dla kategorii: Prace na potrzeby Postdata
		if (v==5) {
			row.style.display = '';	
			if (v1==2) row6.style.display = '';
			if ((v1==3) || (v1==7) || (v1=='3A') || (v1=='3B') || (v1==9)) row5.style.display = ''; 
			if (v1==9) {
				row2.style.display = '';
				row2a.style.display = '';
			}
			if ((v1==3) || (v1==7) || (v1=='3B') || (v1==9)) {
				//document.getElementById("SelectZdiagnozowany").value='';
				//if (v1!=9) row9.style.display = ''; // zdiagnozowano
			}
			
		}
		
		if (v1==9) {
			row_ww.style.display = 'none';
		}
	}
		function StatusChanged_fromtask(v, v1, v2) {
			var zawartosc = '';
		var row = document.getElementById("StatusZakonczony");
		
		var row2 = document.getElementById("ZasadnoscZgloszenia");
		var row2a = document.getElementById("OsobaPotwierdzajacaZamkniecie");
		var row3 = document.getElementById("WpiszWyjazdTrasa");
		var row4 = document.getElementById("WpiszWyjazdKm");
		var row5 = document.getElementById("PowiazaneZWyjazdem");	
		var row5a = document.getElementById("PozwolWpisacKm");
		var row5b = document.getElementById("DataWyjazdu");
		
		var row6 = document.getElementById("PrzypiszDoOsoby");
		//	var row7 = document.getElementById("AwariaWAN");		
		var podkat = document.getElementById("podkat_id");
		var czas = 0;
		// standardowy widok (czysty)
		row.style.display = '';
		row2.style.display = 'none';
		row2a.style.display = 'none';
		row6.style.display = 'none';
		//	row8ch.style.display = 'none';
		//	row8.style.display = 'none';
		//	row9.style.display = 'none';
		row5.style.display = 'none';
		//	row7.style.display = 'none';
		
		row5b.style.display = 'none';
		document.getElementById('trasa').value='';
		document.getElementById('km').value='';
		
		row3.style.display = 'none';
		row4.style.display = 'none';
		
		document.getElementById('hd_opz').value = '';
		document.getElementById('PozwolWpisacKm').checked = false;
		document.getElementById('DataWyjazdu').checked = false;
		var row_ww = document.getElementById('show_ww');
		row_ww.style.display='';
		
		if (v==3) {
			row.style.display = '';	
			if (v1==2) {
				row6.style.display = '';
			}
			
			if ((v1==3) || (v1==7) || (v1=='3A') || (v1=='3B') || (v1==9)) row5.style.display = ''; 
			
			if (v1==9) {
				row2.style.display = '';
				row2a.style.display = '';
			}
			
			if (((podkat.value=='2') || (podkat.value=='5') || (podkat.value=='7') || (podkat.value=='6')) && (v1=='3A')) {
			
			} else {
			
			}		
			
			if ((v1==3) || (v1==7) || (v1=='3B') || (v1==9)) {
				if ((podkat.value=='2') || 
						(podkat.value=='3') ||
						(podkat.value=='4') ||
						(podkat.value=='5') ||
						(podkat.value=='9') ||
						(podkat.value=='0') ||
						(podkat.value=='E') ||
						(podkat.value=='D')) {
					
				}
			}
			
		}
		if (v1==9) {
			row_ww.style.display='none';				
		}
		
	}
	function urlencode1 (str) {
		str = (str+'').toString();
		return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}function WybierzWartosc(c) {
	var wartosc = c.split('>>>>>')[1].split('<<<<<');
	var wynik = wartosc[0].substring(1,wartosc[0].length);
	if (wynik=='') { 
		$('#submit').hide();
		$('#info1').show('slow');
		$('#lista_zgloszen').hide();
		$('#lista_zgloszen_pokaz').hide();
		$('#tel_do_komorki_title').hide();
		$('#adres_podsieci_title').hide();
		$('#gpa').hide();
		$('#gp').hide();
		$('#gp_default').hide();
		$('#up_typ_uslugi').hide();
		$('#none_gp').hide();
		
	} else {
		$('#info1').hide('slow');
		$('#submit').show();
	}
	return wynik;
}function SprawdzKomorke(k) {
var xhr = getXhr();
	if (document.getElementById('up_list').value!='') {
		xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
				leselect = xhr.responseText;
				document.getElementById('up_list_id1').innerHTML = leselect;
				document.getElementById('up_list_id').value = WybierzWartosc((leselect));				
			}
		}
		xhr.open("POST","hd_get_up_list_id.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		
		sel = document.getElementById('up_list').value;
		
		var parametry = ""+urlencode1(sel);
		xhr.send("wybierzid="+parametry);
		return true;
	} else {
		document.getElementById('up_adres_podsieci').value='';
		document.getElementById('adres_podsieci_title').style.display='none';
		document.getElementById('tel_do_komorki').value='';
		document.getElementById('tel_do_komorki_title').style.display='none';		
		return true;
	}
	}
function CompareDatesDodajZgloszenie() {
	var data1 = document.getElementById('hddz').value;
	
	//if (document.getElementById('nowa_data_rozpoczecia').length==0) {
		
		var data2 = document.getElementById('nowa_data_rozpoczecia').value;
		//var data3 = document.getElementById('UDR_MinDate').value;
		if (data2<data1) {
			alert("Ustalona data rozpoczęcia zgłoszenia nie może być wcześniejsza niż data rejestracji zgłoszenia: " + data1);
			document.getElementById('nowa_data_rozpoczecia').focus();
			return '0';		
		} else {
			return '1';
			
		}
	//}

}
function pytanie_zatwierdz(message){ 
	if (CheckTime4(document.getElementById('hdgz').value)!=true) return false;
		if (document.getElementById('UstalonaDataZakonczeniaCheck')) {
		if (document.getElementById('UstalonaDataZakonczeniaCheck').checked) {
			if (OdrzucNiedozwolone('hd_opp',document.getElementById('hd_opp').value)==1) {
				alert("Wpisano nieprawidłową wartość w polu. Osobę potwierdzającą ustalenia należy wpisać z imienia i nazwiska");
				document.getElementById('hd_opp').focus();
				return false;		
			}
		}
	}
	
	if (document.getElementById('hdgz')) {	
		if (document.getElementById('hdgz').value=='') { 
			alert('Nie podałeś godziny zgłoszenia'); document.getElementById('hdgz').focus(); return false; 
		} else {
			if (CheckTime(document.getElementById('hdgz').value)==false) {
				return false;
			}
		}
	}

	if (document.getElementById('up_list')) {
		if (document.getElementById('up_list').value=='') { 
			alert('Nie podałeś komórki'); document.getElementById('up_list').focus(); return false; 
		}
	}
	
	if (document.getElementById('hd_oz')) { 
		if (document.getElementById('hd_oz').value=='') { 
			alert('Nie podałeś osoby zgłaszającej'); document.getElementById('hd_oz').focus(); return false; 
		}
	
		if (OdrzucNiedozwolone('hd_oz',document.getElementById('hd_oz').value)==1) {
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			document.getElementById('hd_oz').focus();
			return false;
		}
	}
	
	if (($('#status_id').val()=='9') && (document.getElementById('hd_opz').value!='')) {
	
		if (OdrzucNiedozwolone('hd_opz',document.getElementById('hd_opz').value)==1) {
			alert("Wpisano nieprawidłową wartość w polu. Osobę potwierdzającą zamknięcie zgłoszenia należy wpisać z imienia i nazwiska");
			document.getElementById('hd_opz').focus();
			return false;
		}
		
	}
	
	<?php 
	if ($czy_wymagany_nr_telefonu==1) {
	?>
		if (document.getElementById('hdoztelefon').value=='') { 
			alert('Nie podałeś numeru telefonu osoby zgłaszającej'); document.getElementById('hdoztelefon').focus(); return false; 
		}
	<?php 
	}
	?>
		if (document.getElementById('hd_tresc').value=='') { 
		alert('Nie podałeś treści zgłoszenia'); document.getElementById('hd_tresc').focus(); return false; 
	}
		if (document.getElementById('hd_temat').value=='') { 
		alert('Wygenerowano pusty temat zgłoszenia - usuń pierwszą pustą linię z treści zgłoszenia.'); document.getElementById('hd_tresc').focus(); return false; 
	}
		if (document.getElementById('kat_id')) {
		if (document.getElementById('kat_id').value=='') {
			alert('Nie wybrałeś kategorii zgłoszenia'); document.getElementById('kat_id').focus(); return false; 
		}
	}
	
	if (document.getElementById('podkat_id').value=='') {
		//alert('Wystąpił błąd podczas ustawiania podkategorii zgłoszenia.');  document.getElementById('podkat_id').focus(); return false; 
		//document.getElementById('podkat_id').value = document.getElementById('NewDefaultPodkat_id').value;
	}
		
		if (document.getElementById('BrakWANportuKomunikat')) {
		if (document.getElementById('BrakWANportuKomunikat').style.display=='none') {
			if (document.getElementById('AwariaWAN').style.display=='') {
				if (document.getElementById('numerzgloszenia').value=='') {
					alert('Nie wpisałeś numeru zgłoszenia w Orange'); document.getElementById('numerzgloszenia').focus(); return false; 
				}
			}
		}
	}
		if (document.getElementById('StatusZakonczony')) {
		if (document.getElementById('StatusZakonczony').style.display=='') {
			if ((document.getElementById('czas_wykonywania_h').value=='') && (document.getElementById('czas_wykonywania_m').value=='')) {
				alert('Nie podałeś czasu wykonywania');
				document.getElementById('czas_wykonywania_h').focus();
				return false;
			}
		}
	}
		if (document.getElementById('PozwolWpisacKm')) {
		if (document.getElementById('PozwolWpisacKm').checked) {
			if (document.getElementById('hd_wyjazd_rp').value=='P') {
				if (document.getElementById('trasa').value=='') {
					alert('Nie wpisałeś trasy przejazdu');	document.getElementById('trasa').focus();	return false;
				}
				if (document.getElementById('km').value=='') {
					alert('Nie wpisałeś ilości km');	document.getElementById('km').focus();	return false;
				}		
				
				//if ((document.getElementById('km').value=='0') || (document.getElementById('km').value=='00') || (document.getElementById('km').value=='000')) {
				//	alert('Podałeś złą ilość km');	document.getElementById('km').focus();	return false;
				//}		
			}
			if ((document.getElementById('czas_przejazdu_h').value=='') && (document.getElementById('czas_przejazdu_m').value=='')) {
				alert('Nie podałeś czasu trwania wyjazdu');
				document.getElementById('czas_przejazdu_h').focus();
				return false;
			}
		}
	}
	
	<?php if ($zerowe_czasy_wykonania==FALSE) { ?>
		if ((document.getElementById('czas_wykonywania_h').value==0) && (document.getElementById('czas_wykonywania_m').value==0)) {
			//if (confirm('Podano zerowy czas wykonywania. Czy chcesz poprawić te dane ?')) {
			
			alert('Nie podałeś czasu wykonywania');
			document.getElementById('czas_wykonywania_m').select();
			document.getElementById('czas_wykonywania_m').focus();
			return false;
			}
	<?php } ?>

	if ((document.getElementById('kat_id')) && (document.getElementById('kat_id').value=='1')) {
		var il_m = (document.getElementById('czas_wykonywania_h').value*60);
		il_m = il_m + ((document.getElementById('czas_wykonywania_m').value)*1);
		
		if (il_m > <?php echo $max_ilosc_minut_dla_konsultacji; ?>) {
			alert('Przekroczono maksymalną dopuszczalną ilość minut dla konsultacji (<?php echo $max_ilosc_minut_dla_konsultacji; ?> minut).\r\n\r\nNależy zmienić kategorię zgłoszenia na \"Prace zlecone w ramach umowy\"');
			if (document.getElementById('kat_id')) document.getElementById('kat_id').focus();
			return false;
		}
	}
	
	if (document.getElementById('UstalonaDataZakonczeniaCheck')) {
		if (document.getElementById('UstalonaDataZakonczeniaCheck').checked) {
			if (document.getElementById('nowa_data_rozpoczecia').value=='') {
				alert('Nie wybrałeś ustalonej daty rozpoczęcia zgłoszenia');	
				document.getElementById('nowa_data_rozpoczecia').focus();	return false;
			} else {
					if (CheckTime5(document.getElementById('nowy_czas_rozpoczecia').value)==false) return false;
					<?php if ($idw_dla_zbh_testowa!=true) { ?>
					if (CompareDatesDodajZgloszenie()=='0') return false;
					<?php } ?>
			}
			
	/*		if (document.getElementById('nowy_czas_rozpoczecia').value=='') {
				alert('Nie wpisałeś ustalonej godziny rozpoczęcia zgłoszenia');	
				document.getElementById('nowy_czas_rozpoczecia').focus();	return false;
			} else {
				if (CheckTime2(document.getElementById('nowy_czas_rozpoczecia').value)==false) {
					alert('cos nie tak');
					return false;
				}
			}
	*/
			if (document.getElementById('hd_opp').value=='') {
				alert('Nie wpisałeś osoby potwierdzającej przesunięcie godziny rozpoczęcia zgłoszenia');	
				document.getElementById('hd_opp').focus();	
				return false;
			}
			
			/*
			alert(document.getElementById('kat_id').value);
			alert(document.getElementById('nowa_data_rozpoczecia').value);
			alert(document.getElementById('hddz').value);
			alert(document.getElementById('nowy_czas_rozpoczecia').value);
			alert(document.getElementById('test_gSTART').value);
			alert(document.getElementById('test_przesun').value);
			*/
			
			if (document.getElementById('kat_id').value==6) {
				var t1 = document.getElementById('nowy_czas_rozpoczecia').value + ':00';
				var t2 = document.getElementById('test_gSTART').value + ':00';
				var t2a = document.getElementById('test_gSTOP').value + ':00';
				var t3 = document.getElementById('test_maxDR').value.substring(11,16)+':00';
				var t3a = document.getElementById('test_maxDR').value.substring(11,16);
				var t4 = document.getElementById('test_maxDR').value.substring(0,10);
				//alert(t3);
				//t1 = t1.replace(":","")*1;
				//t2 = t2.replace(":","")*1;
				//alert('#');
				//alert('t1='+t1+ '    t3='+t3+'       t4='+t4+'|     '+document.getElementById('test_przesun').value+'   NDR='+document.getElementById('nowa_data_rozpoczecia').value+'');
				
				if (document.getElementById('nowa_data_rozpoczecia').value>t4) {
					alert('Ustalona data rozpoczęcia przekracza graniczną datę rozpoczęcia wynikającą z umowy ('+t4+' '+t3a+')');
					document.getElementById('nowa_data_rozpoczecia').value='';
					document.getElementById('nowy_czas_rozpoczecia').value='';
					return false;
				}
	
				if (document.getElementById('nowa_data_rozpoczecia').value==document.getElementById('hddz').value) {
					var t2 = document.getElementById('hdgz').value + ':00';
					if (t1<t2) {
						alert('Ustalona data rozpoczęcia ('+document.getElementById('nowa_data_rozpoczecia').value+' '+document.getElementById('nowy_czas_rozpoczecia').value+') nie może być wcześniejsza niż data rejestracji zgłoszenia ('+document.getElementById('hddz').value+' '+document.getElementById('hdgz').value+')');
						document.getElementById('nowa_data_rozpoczecia').value='';
						document.getElementById('nowy_czas_rozpoczecia').value='';
						return false;
					}
					
					if (document.getElementById('nowa_data_rozpoczecia').value<t4) {
						if ((t1>t2a) && (t1<'21:00:00')) {
						} else {
							if (t1>'21:00:00') {
								alert('Ustalona data rozpoczęcia ('+document.getElementById('nowa_data_rozpoczecia').value+' '+document.getElementById('nowy_czas_rozpoczecia').value+') nie może być późniejsza niż godzina graniczna pracy serwisu (21:00)');
								document.getElementById('nowa_data_rozpoczecia').value='';
								document.getElementById('nowy_czas_rozpoczecia').value='';
								return false;
							}
						}
						
					}
				}
				
				if (document.getElementById('nowa_data_rozpoczecia').value>document.getElementById('hddz').value) {

					if (t1>t3) {
						//alert('123');
						if (document.getElementById('test_przesun').value=='NEXT_DAY') {
							//alert('3456');
							alert('Dla awarii krytycznej ustalony czas rozpoczęcia działań nie może być późniejszy niż początek następnego dnia pracy komórka (dla zgłoszeń które wpłynęły po godzinach pracy komórki');
							
							document.getElementById('nowy_czas_rozpoczecia').select();
							document.getElementById('nowy_czas_rozpoczecia').focus();
							return false;
						}
						
						if (document.getElementById('test_przesun').value=='START') {
							//alert('3456');
							alert('Dla awarii krytycznej ustalony czas rozpoczęcia działań nie może być późniejszy niż graniczna data rozpoczęcia ('+document.getElementById('nowa_data_rozpoczecia').value+' '+t3a+')');
							
							document.getElementById('nowy_czas_rozpoczecia').select();
							document.getElementById('nowy_czas_rozpoczecia').focus();
							return false;
						}
						
					}
				}
				
		/*		
				if (document.getElementById('nowa_data_rozpoczecia').value>document.getElementById('hddz').value) {
					//alert(t1+ '    '+t3+'        '+document.getElementById('test_przesun').value+'');
					//alert('X1');
					if (t1>t3) {
						//alert('123');
						if (document.getElementById('test_przesun').value=='NEXT_DAY') {
							//alert('3456');
							alert('Dla awarii krytycznej ustalony czas rozpoczęcia działań nie może być późniejszy niż początek następnego dnia pracy komórka (dla zgłoszeń które wpłynęły po godzinach pracy komórki');
							
							document.getElementById('nowy_czas_rozpoczecia').select();
							document.getElementById('nowy_czas_rozpoczecia').focus();
							return false;
						}
						
						if (document.getElementById('test_przesun').value=='START') {
							//alert('3456');
							alert('Dla awarii krytycznej ustalony czas rozpoczęcia działań nie może być późniejszy niż graniczna data rozpoczęcia '+document.getElementById('nowa_data_rozpoczecia').value+' '+t3+'');
							
							document.getElementById('nowy_czas_rozpoczecia').select();
							document.getElementById('nowy_czas_rozpoczecia').focus();
							return false;
						}
						
					}
				}
				

				
				if (document.getElementById('nowa_data_rozpoczecia').value==t4) {
					//alert('X3');
					if (document.getElementById('nowa_data_rozpoczecia').value>document.getElementById('hddz').value) {
					
						if (t1<t3) {
					
							alert('Dla awarii krytycznej ustalona godzina rozpoczęcia działań dla tego samego dnia musi być zgodna z wyliczoną wg umowy');
							
							document.getElementById('nowy_czas_rozpoczecia').value=''+t3.substring(0,5)+'';
							document.getElementById('nowy_czas_rozpoczecia').focus();
	//							return false;
						}

					}
					
				}
				//alert('X4');
		*/
			}
		}
	}
	
		if (confirm(message)) { 
			document.getElementById('content').style.display='none';	
			document.getElementById('submit').style.display='none';		
			document.getElementById('reset').style.display='none';
			document.getElementById('anuluj').style.display='none';
			document.getElementById('Saving').style.display='';
			
			document.forms.hd_dodaj_zgl.submit(); 
			return true; 
		} else return false; 
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function ClearCookie (name, value) {  
	document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
function SetCookie(name, value) {  
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}



function getXhr2(){
var xhr2 = null; 
	if(window.XMLHttpRequest) // Firefox
	   xhr2 = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // IE
		try {
			xhr2 = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xhr2 = new ActiveXObject("Microsoft.XMLHTTP");
		}
	} else { 
		alert("XMLHTTPRequest not supported"); 
		xhr2 = false; 
	} 
	return xhr2;
}

function WybierzWartosc2(c) {
	var wartosc = c.split('>>>>>')[1].split('<<<<<');
	var wynik = wartosc[0].substring(1,wartosc[0].length);
	return wynik;
}

function urlencode1a (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function PrzygotujListeDat(x) {
if ((x!=2) && (x!=6)) return false;
var xhr2 = getXhr2();

	xhr2.onreadystatechange = function(){
		if(xhr2.readyState == 4 && xhr2.status == 200){
			leselect = xhr2.responseText;
			document.getElementById('test2').innerHTML = leselect;
			document.getElementById('test').value = WybierzWartosc2((leselect));	
		}
	} 	
	xhr2.open("POST","hd_set_data_rozp_list.php",true);
	xhr2.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	sel = document.getElementById('hddz').value;
	sel2 = document.getElementById('hdgz').value;	
	var parametry = "sdate="+urlencode1a(sel)+"&ctime="+urlencode1a(sel2)+"&k="+x+"";
	xhr2.send(parametry);
	return false;	
}

function WybierzWartoscSTART(c) {

	var wartosc = c.split('#');
	//var wartosc = c.split('#')[1].split('<<<<<');
	var wynik = wartosc[0].substring(wartosc[0].length-5,wartosc[0].length);
	
	return wynik;
}

function WybierzWartoscSTOP(c) {
	var wartosc = c.split('#');
	//var wartosc = c.split('#')[1].split('<<<<<');
	var wynik = wartosc[1];
	return wynik;
}

function WybierzWartoscDN(c) {
	var wartosc = c.split('#');
	//var wartosc = c.split('#')[1].split('<<<<<');
	var wynik = wartosc[2];
	return wynik;
}

function TimePlus(m) {
	var fd = document.getElementById('hdgz').value;	
	var hnew = ((fd.split(':')[0]*1)+m);
	if (hnew<10) hnew = '0' + hnew;
	
	if (hnew<24) {
		return (hnew + ':' + fd.split(':')[1]);
	} else return fd;
}

function TimeMinus(m) {
	var fd = document.getElementById('hdgz').value;	
	var hnew = ((fd.split(':')[0]*1)-m);
	if (hnew<10) hnew = '0' + hnew;
	
	if (hnew<24) {
		return (hnew + ':' + fd.split(':')[1]);
	} else return fd;
}

function MakeListaDat(n) {
var x = document.getElementById('kat_id').value;
var wt = document.getElementById('test_g3').value;

if ((x!=2) && (x!=6)) return false;
if (document.getElementById('up_list_id').value=='') { alert('Nie podałeś komórki'); document.getElementById('kat_id').value=1; MakePodkategoriaList('1'); document.getElementById('up_list').focus(); return false; }
	
var xhr2 = getXhr2();

	xhr2.onreadystatechange = function(){
		if(xhr2.readyState == 4 && xhr2.status == 200){
			leselect = xhr2.responseText;
			document.getElementById('test_g1').innerHTML = leselect;
			//document.getElementById('nowy_czas_rozpoczecia').value = WybierzWartosc2(leselect);
			//alert(WybierzWartoscSTART(leselect));
			if ((document.getElementById('hdgz').value>WybierzWartoscSTART(leselect)) && (TimePlus(<?php echo $AK_margines_tolerancji; ?>)<WybierzWartoscSTOP(leselect))) {
				document.getElementById('MGP_allow').value = '0';
				
				if (n==0) {
					document.getElementById('UstalonaDataZakonczenia').style.display='none'; 
					document.getElementById('UstalonaDataZakonczeniaCheck').checked=false;
					document.getElementById('UstalonaDataZakonczeniaTR').style.display='none'; 
				}				
				
			} else {
				document.getElementById('MGP_allow').value = '1';
				
				if (n==0) {
					document.getElementById('UstalonaDataZakonczenia').style.display=''; 
					//document.getElementById('UstalonaDataZakonczeniaCheck').checked=false;
				//	document.getElementById('UstalonaDataZakonczeniaTR').style.display=''; 
				}

			}
			
			//if ((document.getElementById('nowy_czas_rozpoczecia').value=='') && (document.getElementById('up_list_id').value!='')) alert('Komórka nie pracuje w tym dniu');
		}
	}
	
	xhr2.open("POST","hd_get_start_stop_time.php",true);
	xhr2.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	if (document.getElementById('nowa_data_rozpoczecia').value!='') {
		sel = document.getElementById('nowa_data_rozpoczecia').value;
	} else {
		sel = document.getElementById('hddz').value;
	}
	
	if (document.getElementById('nowy_czas_rozpoczecia').value!='') {
		sel2 = document.getElementById('nowy_czas_rozpoczecia').value;
	} else {
		sel2 = document.getElementById('hdgz').value;
	}
	//sel2 = document.getElementById('nowy_czas_rozpoczecia').value;
	var parametry = "sdate="+urlencode1a(sel)+"&ctime="+urlencode1a(sel2)+"&k="+x+"&wt="+wt+"";
	xhr2.send(parametry);

	if (document.getElementById('kat_id').value=='2') {
		$('#UCR_opis_AZ_1').show();
		$('#UCR_opis_AZ_2').show();
		$('#UCR_opis_AZ_3').show();

		$('#UCR_opis_AK_1').hide();
		$('#UCR_opis_AK_2').hide();
		$('#UCR_opis_AK_3').hide();
		
		$('#UCR_info_AK').hide();
		$('#UCR_info_AK2').hide();
		$('#UCR_info_AK2a').hide();
		
		if ((document.getElementById('parent_zgl')) && (document.getElementById('parent_zgl').value!='')) { 
			$('#UCR_opis_AZ_1').hide();
			$('#UCR_opis_AZ_2').hide();
			$('#UCR_opis_AZ_3').hide();		

			$('#UCR_opis_AK_1').hide();
			$('#UCR_opis_AK_2').hide();
			$('#UCR_opis_AK_3').hide();
			document.getElementById('UstalonaDataZakonczeniaCheck').checked=false;
			document.getElementById('UstalonaDataZakonczeniaCheck').style.display = 'none';
		}
	}
	
	if (document.getElementById('kat_id').value=='6') {
		
		$('#UCR_info_AK').show();
		$('#UCR_info_AK2').show();
		$('#UCR_info_AK2a').show();
		
		$('#UCR_opis_AZ_1').hide();
		$('#UCR_opis_AZ_2').hide();
		$('#UCR_opis_AZ_3').hide();
		
		$('#UCR_opis_AK_1').show();
		$('#UCR_opis_AK_2').show();
		$('#UCR_opis_AK_3').show();
	}
	
	document.getElementById('nowy_czas_rozpoczecia').value = '';
}

function WybierzWartosc2a(c) {
	var wartosc = c.split('>>>>>')[1].split('<<<<<');
	var wynik = wartosc[0].substring(1,wartosc[0].length);
	return wynik;
}

function PrzygotujMaxDateRozpoczeciaDlaAwarii(x) {
if (x!=6) return false;
var xhr2 = getXhr2();

	xhr2.onreadystatechange = function(){
		if(xhr2.readyState == 4 && xhr2.status == 200){
			leselect = xhr2.responseText;
			document.getElementById('test_maxDRa').innerHTML = leselect;
			document.getElementById('test_maxDR').value = WybierzWartosc2a((leselect));	
		}
	} 	
	xhr2.open("POST","hd_set_max_data_rozp.php",true);
	xhr2.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	sel = document.getElementById('hddz').value;
	sel2 = document.getElementById('hdgz').value;
	sel3 = document.getElementById('up_list_id').value;
	var parametry = "sdate="+urlencode1a(sel)+"&ctime="+urlencode1a(sel2)+"&k="+x+"&upid="+sel3+"";
	xhr2.send(parametry);
	return false;	
}

function BudujListeDatRozpoczecia(o) {

if ((o!=2) && (o!=6)) return false;

document.getElementById('test_dayname').value = '';
document.getElementById('test_gSTART').value = '';
document.getElementById('test_gSTOP').value = '';
document.getElementById('test_przesun').value = '';
document.getElementById('test_maxDR').value = '';
document.getElementById('UCR_granica_AK').style.display='none';

var NDR = document.getElementById('nowa_data_rozpoczecia');
NDR.options.length=0;

var D1 = document.getElementById('hddz').value;
var G1 = document.getElementById('hdgz').value;
var K1 = document.getElementById('kat_id').value;

PrzygotujListeDat(o);

if ((document.getElementById('UstalonaDataZakonczeniaCheck').checked==true) && (o==6)) {
	
	PrzygotujMaxDateRozpoczeciaDlaAwarii(o);
	document.getElementById('UCR_granica_AK').style.display='';
	
}

/*
if (document.getElementById('nowa_data_rozpoczecia').length==0) {
	document.getElementById('nowa_data_rozpoczecia2').disabled=false;
} else {
	document.getElementById('nowa_data_rozpoczecia2').disabled=false;
}
*/

var ca = document.getElementById('test').value.split('#');

for(var i=0;i < ca.length;i++) {
	var c = ca[i];
	
	if (i==0) {
		NDR.options[NDR.options.length] = new Option(""+c+"",""+c+"",true,true);
	} else {
		NDR.options[NDR.options.length] = new Option(""+c+"",""+c+"",false,false);
	}
}

NDR.disabled=false;

}

function GetStopDateFromDate(sel) {

var x = document.getElementById('kat_id').value;
var wt = document.getElementById('test_g3').value;

if ((x!=2) && (x!=6)) return false;
var xhr2 = getXhr2();

	xhr2.onreadystatechange = function(){
		if(xhr2.readyState == 4 && xhr2.status == 200){
			leselect = xhr2.responseText;
			document.getElementById('test_g1').innerHTML = leselect;
			document.getElementById('nowy_czas_rozpoczecia').value = WybierzWartosc2(leselect);
			//alert(document.getElementById('nowy_czas_rozpoczecia').value);
			//return WybierzWartosc2(leselect);
			if (document.getElementById('up_list_id').value=='') { alert('Nie wybrano komórki z listy'); document.getElementById('up_list_id').focus(); return false; }
			
			//if ((document.getElementById('nowy_czas_rozpoczecia').value=='') && (document.getElementById('up_list_id').value!='')) alert('Komórka nie pracuje w tym dniu');
		}
	} 	
	xhr2.open("POST","hd_set_end_time.php",true);
	xhr2.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	sel = document.getElementById('nowa_data_rozpoczecia').value;
	sel2 = document.getElementById('nowy_czas_rozpoczecia').value;
	var parametry = "sdate="+urlencode1a(sel)+"&ctime="+urlencode1a(sel2)+"&k="+x+"&wt="+wt+"";
	xhr2.send(parametry);
	return false;
}


function GetStartStopTimeFromDate(sel) {

if (sel=='') {
	document.getElementById('nowa_data_rozpoczecia').value = '';
	document.getElementById('nowy_czas_rozpoczecia').value = '';
	document.getElementById('test_gSTART').value = '';
	document.getElementById('test_gSTOP').value = '';
	document.getElementById('test_przesun').value = '';	
	document.getElementById('test_dayname').value = '';	
	return false;
}

var x = document.getElementById('kat_id').value;
var wt = document.getElementById('test_g3').value;

if ((x!=2) && (x!=6)) return false;
var xhr2 = getXhr2();

	xhr2.onreadystatechange = function(){
		if(xhr2.readyState == 4 && xhr2.status == 200){
			leselect = xhr2.responseText;
			document.getElementById('test_g1').innerHTML = leselect;

			if (document.getElementById('nowa_data_rozpoczecia').value!='') {
				document.getElementById('test_gSTART').value = WybierzWartoscSTART(leselect);
				document.getElementById('test_gSTOP').value = WybierzWartoscSTOP(leselect);
				document.getElementById('test_dayname').value = WybierzWartoscDN(leselect);
			}
			
			if (document.getElementById('up_list_id').value=='') alert('Nie wybrano komórki z listy'); 
		}
	} 	
	xhr2.open("POST","hd_get_start_stop_time.php",true);
	xhr2.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	sel = document.getElementById('nowa_data_rozpoczecia').value;
	//alert(sel);
	sel2 = document.getElementById('nowy_czas_rozpoczecia').value;
	var parametry = "sdate="+urlencode1a(sel)+"&ctime="+urlencode1a(sel2)+"&k="+x+"&wt="+wt+"";
	xhr2.send(parametry);
	//return false;
}

function AnalyseTimes() {

	//document.getElementById('test_przesun').value = '';
	if (document.getElementById('test_gSTART').value=='') return false;
	if (document.getElementById('test_gSTOP').value=='') return false;
	
	document.getElementById('nowy_czas_rozpoczecia').readOnly = false;
	document.getElementById('UCR_opis_AK_4r').style.display='none';
	document.getElementById('UCR_opis_AK_4z').style.display='none';
	
	if ((document.getElementById('nowa_data_rozpoczecia').value>document.getElementById('hddz').value) && (document.getElementById('test_przesun').value == '')) {
	
		if ((TimePlus(<?php echo $AK_margines_tolerancji; ?>)>document.getElementById('test_gSTOP').value)) {
			if (confirm('Czy zmienić godzinę rozpoczęcia pracy w dniu '+document.getElementById('nowa_data_rozpoczecia').value+' ?\r\n(użytkownik chce rozpoczęcia rozwiązywania problemu przed godziną rozpoczęcia pracy '+document.getElementById('test_gSTART').value+')\r\n\r\nOK - umożliwi wpisanie godziny\r\nCancel - podstawienie godziny rozpoczęcia pracy komórki\r\n\r\n')) {
				document.getElementById('nowy_czas_rozpoczecia').value = '';
				document.getElementById('nowy_czas_rozpoczecia').readOnly = false;
				document.getElementById('test_przesun').value = 'NEXT_DAY';
				document.getElementById('nowy_czas_rozpoczecia').select();
				document.getElementById('nowy_czas_rozpoczecia').focus();
			} else {
				document.getElementById('nowy_czas_rozpoczecia').value = document.getElementById('test_gSTART').value;
				document.getElementById('nowy_czas_rozpoczecia').readOnly = false;
				document.getElementById('test_przesun').value = 'START';
				document.getElementById('nowy_czas_rozpoczecia').select();
				document.getElementById('nowy_czas_rozpoczecia').focus();
			}
		} else {
		
			alert('Awaria krytyczna zgodnie z umową musi być realizowana w bieżącym dniu');
			document.getElementById('nowa_data_rozpoczecia').value = document.getElementById('nowa_data_rozpoczecia').options[0].value;
			
			document.getElementById('nowy_czas_rozpoczecia').value=''; 
			document.getElementById('test_dayname').value=''; 
			document.getElementById('test_gSTART').value=''; 
			document.getElementById('test_gSTOP').value=''; 
			document.getElementById('test_przesun').value='';

			document.getElementById('nowa_data_rozpoczecia').select();
			document.getElementById('nowa_data_rozpoczecia').focus();
					
			if (document.getElementById('test_przesun').value == 'START') document.getElementById('UCR_opis_AK_4r').style.display='';
			if (document.getElementById('test_przesun').value == 'NEXT_DAY') document.getElementById('UCR_opis_AK_4r').style.display='';
			if (document.getElementById('test_przesun').value == 'STOP') document.getElementById('UCR_opis_AK_4z').style.display='';
		}
		
		return false;
	}
	
	//alert(document.getElementById('hdgz').value+' | '+document.getElementById('test_gSTART').value);
	
	if ((document.getElementById('hdgz').value<document.getElementById('test_gSTART').value) && (document.getElementById('nowa_data_rozpoczecia').value==document.getElementById('hddz').value)) { 
		document.getElementById('nowy_czas_rozpoczecia').value = document.getElementById('hdgz').value;
		alert('Ponieważ godzina rejestracji zgłoszenia jest wcześniejsza niż rozpoczęcie godzin pracy komórki, w tym dniu godzina rozpoczęcia pracy komórki zostanie zmodyfikowana z '+document.getElementById('test_gSTART').value+' na '+document.getElementById('hdgz').value+'\r\n\r\n');
		document.getElementById('nowy_czas_rozpoczecia').readOnly = true;
		document.getElementById('test_przesun').value = 'START';

		if (document.getElementById('test_przesun').value == 'START') document.getElementById('UCR_opis_AK_4r').style.display='';
		if (document.getElementById('test_przesun').value == 'NEXT_DAY') document.getElementById('UCR_opis_AK_4r').style.display='';
		if (document.getElementById('test_przesun').value == 'STOP') document.getElementById('UCR_opis_AK_4z').style.display='';
	
		return false;
	}

	if ((document.getElementById('hdgz').value>document.getElementById('test_gSTOP').value) && (document.getElementById('nowa_data_rozpoczecia').value==document.getElementById('hddz').value) && (document.getElementById('test_przesun').value == '')) { 
		//alert('x2');
		document.getElementById('nowy_czas_rozpoczecia').value = document.getElementById('hdgz').value;
		if (confirm('Godzina rejestracji zgłoszenia jest późniejsza niż godzina zakończenia pracy komórki. \r\n\r\nCzy osoba zgłaszająca chce podjęcia działań w dniu '+document.getElementById('nowa_data_rozpoczecia').value+' ?\r\n\r\nOK - umożliwi wpisanie godziny\r\nCancel - umożliwi przesunięcie rozpoczęcia na dzień następny\r\n\r\n')) {
			document.getElementById('test_przesun').value = 'STOP';
			document.getElementById('nowy_czas_rozpoczecia').value='';
			document.getElementById('nowy_czas_rozpoczecia').readOnly = false;
			document.getElementById('nowy_czas_rozpoczecia').select();
			document.getElementById('nowy_czas_rozpoczecia').focus();
		} else {
			var XX = document.getElementById('nowa_data_rozpoczecia').selectedIndex+1;
			document.getElementById('nowa_data_rozpoczecia').value = document.getElementById('nowa_data_rozpoczecia').options[XX].value;
			
			if (confirm('Czy zmienić godzinę rozpoczęcia realizacji zgłoszenia w dniu '+document.getElementById('nowa_data_rozpoczecia').value+' ?\r\n(użytkownik chce rozpoczęcia rozwiązywania problemu przed godziną rozpoczęcia pracy '+document.getElementById('test_gSTART').value+')\r\n\r\nOK - umożliwi wpisanie godziny\r\nCancel - podstawienie godziny rozpoczęcia pracy komórki\r\n\r\n')) {
				document.getElementById('nowy_czas_rozpoczecia').value = '';
				document.getElementById('nowy_czas_rozpoczecia').readOnly = false;
				document.getElementById('test_przesun').value = 'NEXT_DAY';
				document.getElementById('nowy_czas_rozpoczecia').select();
				document.getElementById('nowy_czas_rozpoczecia').focus();
			} else {
				document.getElementById('nowy_czas_rozpoczecia').value = document.getElementById('test_gSTART').value;
				document.getElementById('nowy_czas_rozpoczecia').readOnly = false;
				document.getElementById('test_przesun').value = 'START';
				document.getElementById('nowy_czas_rozpoczecia').select();
				document.getElementById('nowy_czas_rozpoczecia').focus();
			}
		}

		if (document.getElementById('test_przesun').value == 'START') document.getElementById('UCR_opis_AK_4r').style.display='';
		if (document.getElementById('test_przesun').value == 'NEXT_DAY') document.getElementById('UCR_opis_AK_4r').style.display='';
		if (document.getElementById('test_przesun').value == 'STOP') document.getElementById('UCR_opis_AK_4z').style.display='';
	
		return false;
	}
	
	if ((document.getElementById('hdgz').value<document.getElementById('test_gSTOP').value) && (document.getElementById('hdgz').value>document.getElementById('test_gSTART').value) && (document.getElementById('nowa_data_rozpoczecia').value==document.getElementById('hddz').value) && (document.getElementById('test_przesun').value == '')) { 
		document.getElementById('nowy_czas_rozpoczecia').value = '';
		document.getElementById('nowy_czas_rozpoczecia').readOnly = false;
		document.getElementById('test_przesun').value = 'STOP';
		document.getElementById('nowy_czas_rozpoczecia').select();
		document.getElementById('nowy_czas_rozpoczecia').focus();	
	}
	//document.getElementById('nowy_czas_rozpoczecia').value = document.getElementById('test_gSTART').value;

}
</script>
<?php } ?>