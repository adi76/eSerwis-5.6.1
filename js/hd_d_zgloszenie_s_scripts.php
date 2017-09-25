<?php
$pw = array($_f."/hd_d_zgloszenie_s.php");
if (array_search($PHP_SELF, $pw)>-1) { ?>
	<link type="text/css" rel="stylesheet" media="screen" href="js/jquery/jquery.toChecklist.css" />
	<script type="text/javascript" src="js/jquery/jquery.toChecklist_min.js"></script>
	<script>
	function SzybkiSkokZKategorii1(val) {
		var kateg_select = document.getElementById('kat_id').value;
		if (kateg_select.value=="1") document.getElementById('czas_wykonywania_h').focus();
		if (kateg_select.value=="7") document.getElementById('czas_wykonywania_h').focus();
		if (kateg_select.value=="2") document.getElementById('podkat_id').focus();
		if (kateg_select.value=="5") document.getElementById('status_id').focus();
	}
	function AddToList(listname, list_opis, list_value, bool1, bool2) {
		listname.options[listname.options.length] = new Option(""+list_opis+"",""+list_value+"",bool1, bool2);
	}
	function MakePodkategoriaList2(o) { 
		var kateg_select = document.getElementById('kat_id');
		var ps = document.getElementById('podkat_id');
		ps.options.length=0;
		var sps = document.getElementById('sub_podkat_id');
		sps.options.length=0;
		var prior_select = document.getElementById('priorytet_id');
		prior_select.options.length=0;
		var status_select = document.getElementById('status_id');
		status_select.options.length=0;
		var SpanWyslijEmail = document.getElementById('WyslijEmail');
		SpanWyslijEmailCheckbox = document.getElementById('WyslijEmailCheckbox');
		SpanWyslijEmail.style.display='none';
		if (document.getElementById('priorytet_id')) var prs = document.getElementById('priorytet_id');
		if (prs) prs.options.length=0;
		document.getElementById('czy_synchronizowac').checked=true;		
	
		ps.disabled=true;
		sps.disabled=true;
		status_select.disabled=true;
		
		if (o == "7") {
			ps.options[ps.options.length] = new Option("Konserwacja sprzętu","8",true,true);
			ps.disabled=false;
			
			if (prs) prs.options[prs.options.length] = new Option("standard","2",true,true);
			if (prs) prs.disabled=false;
			status_select.options[status_select.options.length] = new Option("nowe","1",true,true);
			status_select.options[status_select.options.length] = new Option("rozpoczęte","3",false,false);
			status_select.options[status_select.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);
			status_select.options[status_select.options.length] = new Option("zamknięte","9",false,false);
			status_select.disabled=false;
			AddToList(sps,'Brak','',true,true);
			document.getElementById('sub_podkat_id').disabled=false;
			
		}
		
		if (o == "1") {
			ps.options[ps.options.length] = new Option("Brak","1",true,true);
			ps.disabled=false;
			
			AddToList(sps,'Brak','',true,true);
			document.getElementById('sub_podkat_id').disabled=false;
			
			prior_select.options[prior_select.options.length] = new Option("standard","2",true,true);
			prior_select.disabled=false;
			status_select.options[status_select.options.length] = new Option("zamknięte","9",true,true);
			status_select.disabled=false;
			SpanWyslijEmailCheckbox.checked=false;
		}
		if (o == "2") {
			ps.options[ps.options.length] = new Option("Oprogramowanie biurowe","5",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - placówka pocztowa","2",false,false);
			ps.options[ps.options.length] = new Option("Serwer","4",false,false);
			ps.options[ps.options.length] = new Option("Stacja robocza","3",true,true);
			ps.options[ps.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps.disabled=false;
			
			document.getElementById('sub_podkat_id').disabled=false;
			
			prior_select.options[prior_select.options.length] = new Option("standard","2",true,true);
			prior_select.options[prior_select.options.length] = new Option("krytyczny","4",false,false);	
			prior_select.disabled=false;	
			status_select.options[status_select.options.length] = new Option("nowe","1",true,true);
			//status_select.options[status_select.options.length] = new Option("przypisane","2",true,true);
			status_select.options[status_select.options.length] = new Option("rozpoczęte","3",false,false);
			status_select.options[status_select.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);
			status_select.options[status_select.options.length] = new Option("w firmie","3B",false,false);				
			status_select.options[status_select.options.length] = new Option("zamknięte","9",false,false);
			status_select.disabled=false;
			
			SpanWyslijEmail.style.display='';
			SpanWyslijEmailCheckbox.checked=true;
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
			
			
		/*	ps.options[ps.options.length] = new Option("Aktualizacja SP2000","6",false,false);
			ps.options[ps.options.length] = new Option("Alarmy","E",false,false);
			ps.options[ps.options.length] = new Option("Inne","D",false,false);
			ps.options[ps.options.length] = new Option("Konserwacja sprzętu","8",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie biurowe","5",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - placówka pocztowa","2",true,true);
			ps.options[ps.options.length] = new Option("Oprogramowanie techniczne","7",false,false);
			ps.options[ps.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps.options[ps.options.length] = new Option("Prace administracyjno-sprawozdawcze","F",false,false);
			ps.options[ps.options.length] = new Option("Przeniesienie placówki pocztowej","B",false,false);
			ps.options[ps.options.length] = new Option("Serwer","4",false,false);
			ps.options[ps.options.length] = new Option("Stacja robocza","3",false,false);
			ps.options[ps.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps.options[ps.options.length] = new Option("WAN/LAN","0",false,false);
			ps.options[ps.options.length] = new Option("Zamknięcie placówki pocztowej","C",false,false);
		*/
			ps.disabled=false;
			
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'MBP','MBP',false,false);
			AddToList(sps,'ŁP14','ŁP14',false,false);
			AddToList(sps,'MRU','MRU',false,false);
			AddToList(sps,'SEDI','SEDI',false,false);
			AddToList(sps,'MRUm','MRUm',false,false);
			AddToList(sps,'ORM','ORM',false,false);
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
			document.getElementById('sub_podkat_id').disabled=false;
			
			prior_select.options[prior_select.options.length] = new Option("niski","1",false,false);
			prior_select.options[prior_select.options.length] = new Option("standard","2",true,true);	
			prior_select.options[prior_select.options.length] = new Option("wysoki","3",false,false);	
			prior_select.disabled=false;
			status_select.options[status_select.options.length] = new Option("nowe","1",true,true);
			//status_select.options[status_select.options.length] = new Option("przypisane","2",true,true);
			status_select.options[status_select.options.length] = new Option("zamknięte","9",false,false);
			status_select.disabled=false;	
			SpanWyslijEmailCheckbox.checked=false;
		}
		if (o == "4") {
			ps.options[ps.options.length] = new Option("Inne","D",false,false);
			ps.options[ps.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps.disabled=false;
			
			AddToList(sps,'Brak','',true,true);
			document.getElementById('sub_podkat_id').disabled=false;
			
			prior_select.options[prior_select.options.length] = new Option("standard","2",true,true);
			prior_select.disabled=false;
			status_select.options[status_select.options.length] = new Option("nowe","1",true,true);
			//status_select.options[status_select.options.length] = new Option("przypisane","2",true,true);
			status_select.options[status_select.options.length] = new Option("zamknięte","9",false,false);
			status_select.disabled=false;
			SpanWyslijEmailCheckbox.checked=false;		
		}
		if (o == "5") {
			ps.options[ps.options.length] = new Option("brak","1",true,true);
			ps.disabled=false;
			
			AddToList(sps,'Brak','',true,true);
			document.getElementById('sub_podkat_id').disabled=false;
			
			prior_select.options[prior_select.options.length] = new Option("standard","2",true,true);
			prior_select.disabled=false;
			status_select.options[status_select.options.length] = new Option("nowe","1",true,true);
			//status_select.options[status_select.options.length] = new Option("przypisane","2",true,true);
			status_select.options[status_select.options.length] = new Option("zamknięte","9",false,false);
			status_select.disabled=false;	
			SpanWyslijEmailCheckbox.checked=false;		
			document.getElementById('czy_synchronizowac').checked=false;		
		}
	}
	function check_up_in_list() {
		var x1 = rtrim(document.getElementById('up_list').value);
		var x = x1.toUpperCase(); 
		
		l_up = document.hd_dodaj_zgl.upid.length;
		
		if (x=='') {
			document.hd_dodaj_zgl.pokaz_id_pracownikow.style.display='none';	
			document.getElementById('info1').style.display='none';
			return false;
		}
		
		for (i=0; i<l_up; i++) {
			var y1 = document.hd_dodaj_zgl.upid.options[i].text;
			var y = y1.toUpperCase();
			if (x==y) { 
				document.hd_dodaj_zgl.submit.style.display=''; 
				document.hd_dodaj_zgl.up_list.value=document.hd_dodaj_zgl.up_list.value.toUpperCase(); 
				document.getElementById("info1").style.display='none';
				document.hd_dodaj_zgl.up_list_id.value=document.hd_dodaj_zgl.upid.options[i].value;
				document.hd_dodaj_zgl.pokaz_id_pracownikow.style.display='';
				return false;
			} 
		}
		
		document.hd_dodaj_zgl.submit.style.display='none';
		document.getElementById("info1").style.display='';
		document.hd_dodaj_zgl.up_list_id.value='';
		document.hd_dodaj_zgl.pokaz_id_pracownikow.style.display='none';
	}
	function StatusChanged2(v, v1, v2) {
		//alert(v+' '+v1+' '+v2);
		var zawartosc = '';
		var row = document.getElementById("StatusZakonczony");
		var row2 = document.getElementById("ZasadnoscZgloszenia");
		var row2a = document.getElementById("OsobaPotwierdzajacaZamkniecie");
		var row3 = document.getElementById("WpiszWyjazdTrasa");
		var row4 = document.getElementById("WpiszWyjazdKm");
		var row5 = document.getElementById("PowiazaneZWyjazdem");	
		var row5a = document.getElementById("PozwolWpisacKm");
		var row6 = document.getElementById("PrzypiszDoOsoby");
		var row9 = document.getElementById("Zdiagnozowany");
		var podkat = document.getElementById("podkat_id");
		var info1 = "v="+v+" , v1="+v1+" , v2="+v2+" , podkat="+podkat.value+"";
		var row_ww = document.getElementById("show_ww");
		
		var czas = 0;
		// standardowy widok (czysty
		var zawartosc = '';	
		row.style.display = 'none';
		row2.style.display = 'none';
		row2a.style.display = 'none'; 
		row6.style.display = 'none';
		row9.style.display = 'none';
		row5.style.display = 'none';
		
		row3.style.display = 'none';
		row4.style.display = 'none';
		row_ww.style.display = '';
		
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
			if ((v1==3) || (v1==7) || (v1=='3A') || (v1=='3B') || (v1==9)) {
				document.getElementById("SelectZdiagnozowany").value='';
				if (v1!=9) row9.style.display = ''; // zdiagnozowano
				row5.style.display = ''; // km
			}
			if ((v1==1) || (v1==2) || (v1==3) || (v1==7)) {
				row8.style.display = '';
			}
			if ((podkat.value==0) && (v1=='3A')) row7.style.display=''; 		
			if (v1==9) {
				row2.style.display = '';
				row2a.style.display = '';
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
		}
		// warianty dla kategorii: Prace zlecone poza umowa
		if (v==4) {
			row.style.display = '';	
			if (v1==2) row6.style.display = '';
			if ((v1==3) || (v1==7) || (v1=='3A') || (v1=='3B') || (v1==9)) row5.style.display = '';
			if (v1==9) {
				row2.style.display = '';
				row2a.style.display = '';
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
		}
		
		if (v1==9) {
			row_ww.style.display = 'none';
		}
		
	}
	$(function() {
		$("#upids").toChecklist({
			addSearchBox : true,
			searchBoxText : 'wyszukaj komórki',
			showSelectedItems : true,
			submitDataAsArray : true,
showCheckboxes:true,
			preferIdOverName : false
		});
		
	});
	
	$().ready(function() {
		$("#hd_oz").autocomplete("hd_get_oz_zgl_ser.php?filia=<?php echo $es_filia; ?>", {
width: 360,
max:150,
matchContains: true,
mustMatch: false,
minChars: 1,
			//multiple: true, highlight: false, multipleSeparator: ",",
selectFirst: false
		});
		$("#hd_oz").result(function(event, data, formatted) { $("#hdoztelefon").val(data[1]); });
	});
	function ZgloszenieSeryjneWyznaczTrase() { 
		var tadl = document.getElementById('upids_selectedItems').value.length;
		if (tadl==0) { alert('Nie wybrałeś żadnej komórki'); return false; }
		var ups = document.getElementById("upids_selectedItems").value.toUpperCase();
		var trasaups = ups.replace(/, /g, " - ");
		document.getElementById("trasa").value=document.getElementById('lokalizacjazrodlowa').value.toUpperCase()+' - '+trasaups.substring(0,trasaups.length-3)+' - '+document.getElementById('lokalizacjazrodlowa').value.toUpperCase();
		document.getElementById('km').focus();
	}
	function ApplyFiltr(bool) {
		if (bool) {
			var s = document.getElementById('stage').value;
			var f1 = document.getElementById('upfiltr1').value; 
			var f2 = document.getElementById('upfiltr2').value; 
			var f3 = document.getElementById('upfiltr3').value;
			var f4 = document.getElementById('upfiltr4').value;
			var a = document.getElementById('autofiltr').checked;
			var data = document.getElementById('hddz').value;
			var godzina = document.getElementById('hdgz').value;
			var hadim = document.getElementById('hdnzhadim').value;
			var oz = document.getElementById('hd_oz').value;
			var tel = document.getElementById('hdoztelefon').value;
			var zap = document.getElementById('zapamietaj_oz').checked;
			var temat = document.getElementById('hd_temat').value;
			var tresc = document.getElementById('hd_tresc').value;
			self.location='hd_d_zgloszenie_s.php?stage='+s+'&filtr='+f1+'-' +f2+'-'+f3+'-'+f4+'-'+a+'&p1='+data+'&p2='+godzina+'&p3='+hadim+'&p4='+oz+'&p5='+tel+'&p6='+zap+'&p7='+urlencode(temat)+'&p8='+urlencode(tresc)+''; 
		}
	}
	function OdrzucNiedozwolone(iid, v) {
		var test = trimAll(v.toUpperCase());
		var l = test.length;
		if (l==0) return true;
		var x = 0;
		if ((test=="NACZELNIK") || 
				(test=="ASYSTENT") || 
				(test=="AGENT") || 
				(test=="AGENTKA") || 
				(test=="AGENT POCZTOWY") || 
				(test=="CIT") || 
				(test=="EKSPEDYCJA") || 
				(test=="KASA GŁÓWNA") ||
				(test=="KASJER") ||
				(test=="KASJERKA") || 
				(test=="KIEROWNIK") || 
				(test=="KIEROWNIK ZMIANY") || 
				(test=="KONTROLER") || 
				(test=="POCZTA POLSKA") || 
				(test=="PRACOWNIK") || 
				(test=="PRACOWNIK UP") || 
				(test=="PRACOWNIK EKSPEDYCJI") || 
				(test=="PRACOWNIK AGENCJI") || 
				(test=="PRACOWNIK FILII") || 
				(test=="PRACOWNIK FUP")) {
			document.getElementById(iid).value="";
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			x = 1;
		}
		
		if (test.indexOf(" ")<0) {
			document.getElementById(iid).value="";
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			x = 1;
		}
		
		if  ((test.indexOf("NACZELNIK")>=0) || 
		(test.indexOf("ASYSTENT")>=0) || 
		(test.indexOf("AGENT")>=0) || 
		(test.indexOf("AGENTKA")>=0) || 
		(test.indexOf("AGENT POCZTOWY")>=0) || 
		(test.indexOf("CIT")>=0) || 
		(test.indexOf("EKSPEDYCJA")>=0) || 
		(test.indexOf("KASA GŁÓWN")>=0) || 
		(test.indexOf("KASJER")>=0) || 
		(test.indexOf("KASJERKA")>=0) || 
		(test.indexOf("KIEROWNIK")>=0) || 
		(test.indexOf("KIEROWNIK ZMIANY")>=0) || 
		(test.indexOf("KONTROLER")>=0) || 
		(test.indexOf("POCZTA POLSKA")>=0) || 
		(test.indexOf("PRACOWNIK")>=0) || 
		(test.indexOf("PRACOWNIK UP")>=0) || 
		(test.indexOf("PRACOWNIK EKSPEDYCJI")>=0) || 
		(test.indexOf("PRACOWNIK AGENCJI")>=0) || 
		(test.indexOf("PRACOWNIK FILII")>=0) || 
		(test.indexOf("PRACOWNIK FUP")>=0))		
		{
			document.getElementById(iid).value="";
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			x = 1;
		}
		
		if (l<6) {
			document.getElementById(iid).value="";
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			x = 1;
		}
		return x;
	}
	function pytanie_zatwierdz2(message){ 
		var tadl = document.getElementById('upids_selectedItems').value.length;
		if (document.getElementById('hdgz').value=='') { 
			alert('Nie podałeś godziny zgłoszenia'); document.getElementById('hdgz').focus(); return false; 
		} else {
			if (CheckTime(document.getElementById('hdgz').value)==false) return false;
		}
		
		if (tadl==0) { alert('Nie wybrałeś żadnej komórki'); return false; }
		
		if (document.getElementById('hd_oz').value=='') { alert('Nie podałeś osoby zgłaszającej'); document.getElementById('hd_oz').focus(); return false; }
		
		if (OdrzucNiedozwolone('hd_oz',document.getElementById('hd_oz').value)==1) {
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			document.getElementById('hd_oz').focus();
			return false;
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
	
		if (($('#status_id').val()=='9') && (document.getElementById('hd_opz').value!='')) {
			if (OdrzucNiedozwolone('hd_opz',document.getElementById('hd_opz').value)==1) {
				alert("Wpisano nieprawidłową wartość w polu. Osobę potwierdzającą zamknięcie zgłoszenia należy wpisać z imienia i nazwiska");
				document.getElementById('hd_opz').focus();
				return false;
			}
		}
		
		if (document.getElementById('hd_tresc').value=='') { alert('Nie podałeś treści zgłoszenia'); document.getElementById('hd_tresc').focus(); return false; }
		
		if (document.getElementById('hd_temat').value=='') { 
			alert('Wygenerowano pusty temat zgłoszenia - usuń pierwszą pustą linię z treści zgłoszenia.'); document.getElementById('hd_tresc').focus(); return false; 
		}
		
		if (document.getElementById('kat_id').value=='') { alert('Nie wybrałeś kategorii zgłoszenia'); document.getElementById('kat_id').focus(); return false; }
		
		if (document.getElementById('StatusZakonczony').style.display=='') {
			if ((document.getElementById('czas_wykonywania_h').value=='') && (document.getElementById('czas_wykonywania_m').value=='')) {
				alert('Nie podałeś czasu wykonywania zgłoszenia');
				document.getElementById('czas_wykonywania_h').focus();
				return false;
			}
		}
		
		<?php if ($zerowe_czasy_wykonania==FALSE) { ?>
			if ((document.getElementById('czas_wykonywania_h').value==0) && (document.getElementById('czas_wykonywania_m').value==0)) {
				//if (confirm('Podano zerowy czas wykonywania. Czy chcesz poprawić te dane ?')) {
				alert('Nie podałeś czasu wykonywania');
				document.getElementById('czas_wykonywania_m').select();
				document.getElementById('czas_wykonywania_m').focus();
				return false;
			//}
			}
		<?php } ?>		
		
		if ((document.getElementById('kat_id')) && (document.getElementById('kat_id').value=='1')) {
			var tadl1 = (document.getElementById('upids_selectedItems').value.split(';').length-1)*1;
			var il_m = (document.getElementById('czas_wykonywania_h').value*60);
			il_m = il_m + ((document.getElementById('czas_wykonywania_m').value)*1);
			
			if ((document.getElementById('CzasWykonywaniaLaczny')) && (document.getElementById('CzasWykonywaniaLaczny').checked==true)) {
				il_m = Math.floor(il_m / tadl1);
			} 
			
			// alert(il_m+' minut na zgłoszenie');
			
			if (il_m > <?php echo $max_ilosc_minut_dla_konsultacji; ?>) {
				alert('Przekroczono maksymalną dopuszczalną ilość minut dla jednego zgłoszenia konsultacji (<?php echo $max_ilosc_minut_dla_konsultacji; ?> minut).\r\n\r\nNależy zmienić kategorię zgłoszeń na \"Prace zlecone w ramach umowy\"');
				if (document.getElementById('kat_id')) document.getElementById('kat_id').focus();
				return false;
			}
			
			if (il_m == 0) {
				alert('Wyliczona ilość minut dla jednego zgłoszenia wynosi 0 minut.\r\n\r\nPopraw czas wykonywania.');
				document.getElementById('czas_wykonywania_m').select();
				document.getElementById('czas_wykonywania_m').focus();
				return false;
			}
		}
	
		if (document.getElementById('WpiszWyjazdTrasa').style.display=='') {
			if (document.getElementById('trasa').value=='') {
				alert('Nie wpisałeś trasy przejazdu');	document.getElementById('trasa').focus();	return false;
			}
			if (document.getElementById('km').value=='') {
				alert('Nie wpisałeś ilości km');	document.getElementById('km').focus();	return false;
			}		
			
			if ((document.getElementById('km').value=='0') || (document.getElementById('km').value=='00') || (document.getElementById('km').value=='000')) {
				alert('Podałeś złą ilość km');	document.getElementById('km').focus();	return false;
			}		
		}
		
		if (confirm(message)) { 
			document.getElementById('content').style.display='none';
			
			document.getElementById('submit').style.display='none';		
			document.getElementById('reset').style.display='none';
			document.getElementById('anuluj').style.display='none';
			document.getElementById('Saving').style.display='';
			
			document.forms.hd_dodaj_zgl_s.submit(); 
			return true; 
		} else return false; 
		
	}
	function pytanie_wyczysc_seryjne(message){ if (confirm(message)) window.location.href='hd_d_zgloszenie_s.php?stage=1&filtr=X-X-X-X';}
	function OdrzucNiedozwoloneOPZ(iid, v) {
		var test = trimAll(v.toUpperCase());
		var l = test.length;
		if (l==0) return true;
		var x = 0;
		if ((test=="NACZELNIK") || 
				(test=="ASYSTENT") || 
				(test=="AGENT") || 
				(test=="AGENTKA") || 
				(test=="AGENT POCZTOWY") || 
				(test=="CIT") || 
				(test=="EKSPEDYCJA") || 
				(test=="KASA GŁÓWNA") ||
				(test=="KASJER") ||
				(test=="KASJERKA") || 
				(test=="KIEROWNIK") || 
				(test=="KIEROWNIK ZMIANY") || 
				(test=="KONTROLER") || 
				(test=="POCZTA POLSKA") || 
				(test=="PRACOWNIK") || 
				(test=="PRACOWNIK UP") || 
				(test=="PRACOWNIK EKSPEDYCJI") || 
				(test=="PRACOWNIK AGENCJI") || 
				(test=="PRACOWNIK FILII") || 
				(test=="PRACOWNIK FUP")) {
			
			alert("Wpisano nieprawidłową wartość w polu. Osobę potwierdzającą zamknięcie zgłoszenia należy wpisać z imienia i nazwiska");
			x = 1;
		}
		
		if (test.indexOf(" ")<0) {
			alert("Wpisano nieprawidłową wartość w polu. Osobę potwierdzającą zamknięcie zgłoszenia należy wpisać z imienia i nazwiska");
			x = 1;
		}
		
		if  ((test.indexOf("NACZELNIK")>=0) || 
		(test.indexOf("ASYSTENT")>=0) || 
		(test.indexOf("AGENT")>=0) || 
		(test.indexOf("AGENTKA")>=0) || 
		(test.indexOf("AGENT POCZTOWY")>=0) || 
		(test.indexOf("CIT")>=0) || 
		(test.indexOf("EKSPEDYCJA")>=0) || 
		(test.indexOf("KASA GŁÓWN")>=0) || 
		(test.indexOf("KASJER")>=0) || 
		(test.indexOf("KASJERKA")>=0) || 
		(test.indexOf("KIEROWNIK")>=0) || 
		(test.indexOf("KIEROWNIK ZMIANY")>=0) || 
		(test.indexOf("KONTROLER")>=0) || 
		(test.indexOf("POCZTA POLSKA")>=0) || 
		(test.indexOf("PRACOWNIK")>=0) || 
		(test.indexOf("PRACOWNIK UP")>=0) || 
		(test.indexOf("PRACOWNIK EKSPEDYCJI")>=0) || 
		(test.indexOf("PRACOWNIK AGENCJI")>=0) || 
		(test.indexOf("PRACOWNIK FILII")>=0) || 
		(test.indexOf("PRACOWNIK FUP")>=0))		
		{
			alert("Wpisano nieprawidłową wartość w polu. Osobę potwierdzającą zamknięcie zgłoszenia należy wpisać z imienia i nazwiska");
			x = 1;
		}
		
		if (l<6) {
			alert("Wpisano nieprawidłową wartość w polu. Osobę potwierdzającą zamknięcie zgłoszenia należy wpisać z imienia i nazwiska");
			x = 1;
		}
		return x;
	}
	</script>
	<?php } ?>