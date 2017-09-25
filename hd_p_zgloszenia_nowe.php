<?php 
include_once('header_begin.php');
include_once('cfg_helpdesk.php');

?>
<link rel="stylesheet" type="text/css" href="css/flexigrid.css" />
<script type="text/javascript" src="jquery-1.2.3.pack.js"></script>
<script type="text/javascript" src="flexigrid.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$("#flex1").flexigrid
			(
			{
			url: 'post2.php',
			dataType: 'json',
			colModel : [
				{display: 'Nr zgłoszenia', name : 'id', width : 40, sortable : true, align: 'center'},
				{display: 'Status', name : 'iso', width : 40, sortable : true, align: 'center'},
				{display: 'Data zgłoszenia', name : 'name', width : 180, sortable : true, align: 'left'},
				{display: 'Placówka zgłaszająca', name : 'printable_name', width : 120, sortable : true, align: 'left'},
				{display: 'Temat zgłoszenia', name : 'iso3', width : 130, sortable : true, align: 'left'}
				],
			buttons : [{name: 'Dodaj zgłoszenie', bclass: 'add', onpress : test}],
			searchitems : [
				{display: 'ISO', name : 'iso'},
				{display: 'Name', name : 'name', isdefault: true}
				],
			sortname: "id",
			sortorder: "asc",
			usepager: true,
			title: 'Przeglądanie bazy zgłoszeń',
			useRp: true,
			rp: 15,
			showTableToggleBtn: false,
			width: 700,
			height: 255
			}
			);   
	
});

function test(com,grid)
{
    if (com=='Delete')
        {
           if($('.trSelected',grid).length>0){
		   if(confirm('Delete ' + $('.trSelected',grid).length + ' items?')){
            var items = $('.trSelected',grid);
            var itemlist ='';
        	for(i=0;i<items.length;i++){
				itemlist+= items[i].id.substr(3)+",";
			}
			$.ajax({
			   type: "POST",
			   dataType: "json",
			   url: "delete.php",
			   data: "items="+itemlist,
			   success: function(data){
			   	alert("Query: "+data.query+" - Total affected rows: "+data.total);
			   $("#flex1").flexReload();
			   }
			 });
			}
			} else {
				return false;
			} 
        }
    else if (com=='Add')
        {
            alert('Add New Item Action');
           
        }            
} 
</script>

<?php 

echo "</head><body>";

if ($es_mminhd==1) { 
	echo "<div id=mainmenu style='display:;'>";
include_once('login_info.php');
include_once('mainmenu.php');
echo "<br />";

	echo "</div>";
}

$sql="SELECT *";

if ($_REQUEST[p0]=='R') { $sql.= ", TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia "; }
if ($_REQUEST[p0]=='Z') { $sql.= ", TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia "; }	

$sql.= " FROM $dbname_hd.hd_zgloszenie "; 

if ($_REQUEST[add]=='ptr') $sql.= ",$dbname_hd.hd_zgloszenie_szcz ";
if ($_REQUEST[add]=='drk0') $sql.= ",$dbname_hd.hd_zgloszenie_szcz ";
if ($_REQUEST[add]=='startstop') $sql.= ",$dbname_hd.hd_zgloszenie_szcz ";

$sql.= " WHERE ";

$sql=$sql."((hd_zgloszenie.belongs_to=$es_filia)  or (hd_zgloszenie.zgl_przekazane_do=$es_filia)) and (hd_zgloszenie.zgl_widoczne=1) ";
// wg dnia
if (($_REQUEST[p1]!='X') && ($_REQUEST[p1]!='0') && ($_REQUEST[p1]!='')) $sql.="AND (zgl_data='$_REQUEST[p1]') ";
// wg kategorii
if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='0') && ($_REQUEST[p2]!='')) $sql.="AND (zgl_kategoria='$_REQUEST[p2]') ";
// wg podkategorii
if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='')) $sql.="AND (zgl_podkategoria='$_REQUEST[p3]') ";
// wg priorytetu
if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='0') && ($_REQUEST[p4]!='')) $sql.="AND (zgl_priorytet='$_REQUEST[p4]') ";

// je�eli chcemy wy�wietli� sprawdzone - to ustwiamy automatycznie status na "zamkni�te"
if ($_REQUEST[p8]=='1') $_REQUEST[p5]='9';

// wg statusu
if (($_REQUEST[p5]!='X') && ($_REQUEST[p5]!='0') && ($_REQUEST[p5]!='') && ($_REQUEST[p5]!='BZ')) $sql.="AND (zgl_status='$_REQUEST[p5]') ";

if ($_REQUEST[p5]=='BZ') $sql.="AND (zgl_status<>'9') ";
// wg przypisania
if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {
	$p_6='';
	if ($_REQUEST[p6]!='9') $p_6=$_REQUEST[p6];
	$sql.="AND (zgl_osoba_przypisana='$p_6') ";
}

if (($_REQUEST[p8]!='X') && ($_REQUEST[p8]!='')) {
	if ($_REQUEST[p8]=='1') $sql.="AND (zgl_sprawdzone_osoba<>'') ";
	if ($_REQUEST[p8]=='0') $sql.="AND (zgl_sprawdzone_osoba='') ";
}

if ($_REQUEST[p0]=='R') $sql .= "AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2)) ";
if ($_REQUEST[p0]=='Z') $sql .= "AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2)) ";	
if ($_REQUEST[add]=='ptr') $sql.=" AND (hd_zgloszenie.zgl_id=hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (hd_zgloszenie_szcz.zgl_szcz_przesuniety_termin_rozpoczecia=1) and ((hd_zgloszenie.zgl_status=1) or (hd_zgloszenie.zgl_status=2)) ";
if ($_REQUEST[add]=='drk0')	$sql.=" AND (hd_zgloszenie.zgl_id=hd_zgloszenie_szcz.zgl_szcz_zgl_id) and ((hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku LIKE  '%0000-00-00%') or (hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku LIKE  '%1970-01-01%') or (hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku LIKE  '%1969-12-31%'))";
if ($_REQUEST[add]=='pzw') $sql.=" AND (hd_zgloszenie.zgl_razem_km>0) ";
if ($_REQUEST[add]=='pzn') $sql.=" AND (hd_zgloszenie.zgl_naprawa_id>0) ";
if ($_REQUEST[add]=='pzpss') $sql.=" AND ((hd_zgloszenie.zgl_sprzet_serwisowy_id>-1) or (hd_zgloszenie.zgl_naprawa_id=(SELECT naprawa_id FROM $dbname.serwis_naprawa WHERE (naprawa_sprzet_zastepczy_id>0) and (serwis_naprawa.naprawa_hd_zgl_id=hd_zgloszenie.zgl_id) LIMIT 1)))";
if ($_REQUEST[add]=='sn14') { $data_min = SubstractDays(14, Date('Y-m-d')); $sql.=" AND (hd_zgloszenie.zgl_data<'$data_min') "; }
if ($_REQUEST[add]=='rekl') $sql.=" AND (hd_zgloszenie.zgl_czy_to_jest_reklamacyjne=1) ";
if ($_REQUEST[add]=='wp') $sql.=" AND (hd_zgloszenie.zgl_czy_powiazane_z_wymiana_podzespolow=1) ";
if ($_REQUEST[add]=='nr') $sql.=" AND (hd_zgloszenie.zgl_czy_rozwiazany_problem=0) ";
if ($_REQUEST[add]=='ww') $sql.=" AND (hd_zgloszenie.zgl_wymagany_wyjazd=1) ";
if ($_REQUEST[add]=='drz0') $sql.=" AND (hd_zgloszenie.zgl_data='0000-00-00') ";
if ($_REQUEST[add]=='startstop') $sql.=" AND ((hd_zgloszenie.zgl_id=hd_zgloszenie_szcz.zgl_szcz_zgl_id) AND (hd_zgloszenie_szcz.zgl_szcz_widoczne='1') AND (((hd_zgloszenie_szcz.zgl_szcz_czas_start_stop='START') AND (hd_zgloszenie_szcz.zgl_szcz_status IN ('3A','4'))) OR ((hd_zgloszenie_szcz.zgl_szcz_czas_start_stop='STOP') AND (hd_zgloszenie_szcz.zgl_szcz_status IN ('2','3','3B','5','6','7','8'))) OR ((hd_zgloszenie_szcz.zgl_szcz_czas_start_stop='STOP') AND (hd_zgloszenie_szcz.zgl_szcz_status IN ('1'))  AND (hd_zgloszenie_szcz.zgl_szcz_przesuniety_termin_rozpoczecia='0'))) and (hd_zgloszenie.zgl_data>='$_REQUEST[STARTSTOP_data]')) ";
// wg tresci
if ($_REQUEST[st]!='') $sql.=" AND (zgl_tresc LIKE '%$_REQUEST[st]%') ";
	$sql=$sql."ORDER BY ";
if ($_REQUEST[p5]!='9') {
	if ($_REQUEST[s]=='') $_REQUEST[s]='AD';
	if ($_REQUEST[s]!='') {
		if ($_REQUEST[s]=='AA') $sql=$sql."hd_zgloszenie.zgl_nr ASC";
		if ($_REQUEST[s]=='AD') $sql=$sql."hd_zgloszenie.zgl_nr DESC";

		if ($_REQUEST[s]=='BA') $sql=$sql."hd_zgloszenie.zgl_data ASC";
		if ($_REQUEST[s]=='BD') $sql=$sql."hd_zgloszenie.zgl_data DESC";

		if ($_REQUEST[s]=='CA') $sql=$sql."hd_zgloszenie.zgl_komorka ASC";
		if ($_REQUEST[s]=='CD') $sql=$sql."hd_zgloszenie.zgl_komorka DESC";	

		if ($_REQUEST[s]=='DA') $sql=$sql."hd_zgloszenie.zgl_priorytet ASC";
		if ($_REQUEST[s]=='DD') $sql=$sql."hd_zgloszenie.zgl_priorytet DESC";

		if ($_REQUEST[s]=='EA') $sql=$sql."hd_zgloszenie.zgl_status ASC";
		if ($_REQUEST[s]=='ED') $sql=$sql."hd_zgloszenie.zgl_status DESC";

		if ($_REQUEST[s]=='FA') $sql=$sql."hd_zgloszenie.zgl_temat ASC";
		if ($_REQUEST[s]=='FD') $sql=$sql."hd_zgloszenie.zgl_temat DESC";

		if ($_REQUEST[p0]=='R') {
			if ($_REQUEST[s]!='') $sql.=", ";
			$sql .= " CzasDoRozpoczecia ASC ";
			//$sql=$sql.",hd_zgloszenie.zgl_nr DESC";
		}	

		if ($_REQUEST[p0]=='Z') {
			if ($_REQUEST[s]!='') $sql.=", ";
			$sql .= " CzasDoZakonczenia ASC ";
			//$sql=$sql.",hd_zgloszenie.zgl_nr DESC";
		}	

		if (($_REQUEST[p0]!='R') && ($_REQUEST[p0]!='Z') && ($_REQUEST[s]=='')) {
			if ($_REQUEST[s]=='') $sql=$sql."hd_zgloszenie.zgl_nr DESC";
		}
	} else {
		$sql=$sql."hd_zgloszenie.zgl_nr DESC";
	}
}
	
?>
<table id="flex1" style="display:none"></table>
</body>
</html>