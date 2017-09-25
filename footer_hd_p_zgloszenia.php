<?php 

?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<?php if ($ClueTipOn==1) { ?><script type="text/javascript" src="js/jquery/jquery.cluetip_min.js"></script><?php } ?>

<script type="text/javascript" src="js/colorlightrow.js"></script>
<script type="text/javascript" src="js/calendar.js"></script>
<?php if ($_GET[search]!='1') { ?>
<script type="text/javascript" src="js/anylinkcssmenu/anylinkcssmenu-min.js"></script>
<script type="text/javascript" src="js/anylinkcssmenu/anylink.js"></script>
<?php } ?>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="js/recoverscroll_min.js"></script>
<script type="text/javascript" src="templates/<?php echo $template;?>/lightrow.js"></script>
<script type="text/javascript" src="js/security.js"></script>
<script type="text/javascript">

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

function urlencode1(str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function ApplyFiltrHD(bool) {
if (bool) {

var sa = document.getElementById('showall1').value; 
var pa = document.getElementById('page11').value; 
var pt = document.getElementById('page12').value; 

var data = document.getElementById('filtr1').value; 
var kat = document.getElementById('filtr2').value; 
var podkat = document.getElementById('filtr3').value; 
var pr = document.getElementById('filtr4').value; 
var stat = document.getElementById('filtr5').value; 
var przyp = document.getElementById('filtr6').value; 
przyp = przyp.replace(" ","+");
var auto = document.getElementById('autofiltr').checked; 
var addit = document.getElementById('additional_param').value;

var rpsite1 = document.getElementById('rpsite').value; 

self.location='hd_p_zgloszenia.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p1='+data+'&p2='+kat+'&p3='+podkat+'&p4='+pr+'&p5='+stat+'&p6='+przyp+'&p7='+auto+'&add='+addit+'';

} else {
	var data = document.getElementById('filtr1').value; 
	var kat = document.getElementById('filtr2').value; 
	var podkat = document.getElementById('filtr3').value; 
	var pr = document.getElementById('filtr4').value; 
	var stat = document.getElementById('filtr5').value; 
	var przyp = document.getElementById('filtr6').value; 
	var addit = document.getElementById('additional_param').value;

	$('#KomunikatOIlosciZgloszen').load('hd_p_zgloszenia_fast_search.php?randval='+ Math.random()+'&p1='+data+'&p2='+kat+'&p3='+podkat+'&p4='+pr+'&p5='+stat+'&add='+addit+'&p6='+urlencode1(przyp)).show();
}
}

<?php if ($_GET[search]=='1') { ?>

function DopiszKreski(v) {
	var r = document.getElementById(v).value.length;
	if (r==4) document.getElementById(v).value = document.getElementById(v).value+'-';
	if (r==7) document.getElementById(v).value = document.getElementById(v).value+'-';
}

function filterInput(filterType, evt, allowDecimal, allowCustom){ 
    var keyCode, Char, inputField, filter = ''; 
    var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    var num   = '0123456789'; 
    // Get the Key Code of the Key pressed if possible else - allow 
    if(window.event){ 
        keyCode = window.event.keyCode; 
        evt = window.event; 
    }else if (evt)keyCode = evt.which; 
    else return true; 
    // Setup the allowed Character Set 
    if(filterType == 0) filter = alpha; 
    else if(filterType == 1) filter = num; 
    else if(filterType == 2) filter = alpha + num; 
    if(allowCustom)filter += allowCustom; 
    if(filter == '')return true; 
    // Get the Element that triggered the Event 
    inputField = evt.srcElement ? evt.srcElement : evt.target || evt.currentTarget; 
    // If the Key Pressed is a CTRL key like Esc, Enter etc - allow 
    if((keyCode==null) || (keyCode==0) || (keyCode==8) || (keyCode==9) || (keyCode==27) )return true; 
    // Get the Pressed Character 
    Char = String.fromCharCode(keyCode); 
    // If the Character is a number - allow 
    if((filter.indexOf(Char) > -1)) return true; 
    // Else if Decimal Point is allowed and the Character is '.' - allow 
    else if(filterType == 1 && allowDecimal && (Char == '.') && inputField.value.indexOf('.') == -1)return true; 
    else return false; 
}
<?php } ?>

function filterInputEnter(filterType, evt, allowDecimal, allowCustom){ 
    var keyCode, Char, inputField, filter = ''; 
    var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    var num   = '0123456789'; 
    // Get the Key Code of the Key pressed if possible else - allow 
    if(window.event){ 
        keyCode = window.event.keyCode; 
        evt = window.event; 
    }else if (evt)keyCode = evt.which; 
    else return true; 
    // Setup the allowed Character Set 
    if(filterType == 0) filter = alpha; 
    else if(filterType == 1) filter = num; 
    else if(filterType == 2) filter = alpha + num; 
    if(allowCustom)filter += allowCustom; 
    if(filter == '')return true; 
    // Get the Element that triggered the Event 
    inputField = evt.srcElement ? evt.srcElement : evt.target || evt.currentTarget; 
    // If the Key Pressed is a CTRL key like Esc, Enter etc - allow 
    if((keyCode==null) || (keyCode==0) || (keyCode==8) || (keyCode==9) || (keyCode==13) || (keyCode==27) )return true; 
    // Get the Pressed Character 
    Char = String.fromCharCode(keyCode); 
    // If the Character is a number - allow 
    if((filter.indexOf(Char) > -1)) return true; 
    // Else if Decimal Point is allowed and the Character is '.' - allow 
    else if(filterType == 1 && allowDecimal && (Char == '.') && inputField.value.indexOf('.') == -1)return true; 
    else return false; 
}

<?php if ($_GET[search]=='1') { ?>
$().ready(function() {
	$("#search_komorka").autocomplete("hd_get_up_list.php?filia=<?php echo $es_filia; ?>", {
		width: 360,
		max:150,
		matchContains: true,
		mustMatch: false,
		minChars: 1,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#search_komorka").result(function(event, data, formatted) { $("#search_komorka_id").val(data[1]); });
});

function SzukajSprawdzPola() {

	if ((document.getElementById('search_zgl_nr').value=='') && 
		(document.getElementById('search_hadim_nr').value=='') && 
		(document.getElementById('search_eserwis_nr').value=='') && 
		(document.getElementById('search_data').value=='') &&
		(document.getElementById('search_text').value=='') &&
		(document.getElementById('search_text_wc').value=='') &&
		(document.getElementById('search_komorka').value=='')) {

			alert('Nie podałeś żadnego kryterium wyszukiwania'); 
			return false; 
		
		}
}
<?php } else { ?>

$().ready(function() {
	$("#search_komorka").autocomplete("hd_get_up_list.php?filia=<?php echo $es_filia; ?>", {
		width: 360,
		max:150,
		matchContains: true,
		mustMatch: false,
		minChars: 1,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#search_komorka").result(function(event, data, formatted) { $("#search_komorka_id").val(data[1]); });
});

<?php } ?>

function expand(frameName, contentName, expandImageName, a, trid) {
		
        var nodeEl = document.getElementById(contentName);
        var expandImage = document.getElementById(expandImageName);
		var trelement = document.getElementById(trid);
		
        if (nodeEl.className == 'hidden')
        {
            if (a && a.href != "javascript:;")
            {
				trelement.style.display='';
	            nodeEl.className = 'shown';
	            nodeEl.innerHTML = 'Proszę czekać...';
			    a.target = frameName;
				document.getElementById(contentName).innerHTML="<iframe name='eSerwis' scrolling='yes' frameborder='0' width='100%' src='"+a+"'></iframe>";
			    expandImage.src = 'img/collapse.gif';
            }
            else
            {
				trelement.style.display='';
	            nodeEl.className = 'shown';
	        	$('#' + contentName).slideDown();
	        	expandImage.src = 'img/collapse.gif';
            }

        }
        else
        {
            if (a)
                a.href = "javascript:;";
			$('#' + contentName).slideUp();
            nodeEl.className = 'hidden';
            expandImage.src = 'img/expand.gif';
			trelement.style.display='none';
        }
        return true;
}

function PrzypiszSeryjnieDoOsoby() {
var elLength = document.obsluga.elements.length;

	var count_checked = 0;
	var params = "numery=";

    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;
		
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')){
			count_checked = count_checked + 1;
			params += document.obsluga.elements[i].value + ",";
        }
    }
	
	var dl = params.length;
	dl--;
	
	if (dl==6) {
		alert('Nie zaznaczyłeś żadnego zgłoszenia');
		return false;
	}
	
	var params1 = params.substring(0,dl);
	var osobaw = document.getElementById('pdo').value;
	
	var lokacja = "hd_o_zgloszenia_s_przypisz_do_osoby.php?" + params1 + "&cnt=" + count_checked + "&wo=" + urlencode1(osobaw);
	
	
	var x = 1;
	var y = 1;
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	
	var opcje="scrollbars=no, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	}
//	if (confirm("Czy napewno chcesz przpisać wybrane zgłoszenia do siebie ?")) {
		window.open(lokacja, "eSerwisOSZPDO", opcje);
//	}
}

</script>

<script>
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
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

function eraseCookie(name) {
    createCookie(name,"",-1);
}

function Refresh_Moje() {
	if (readCookie('hd_p_zgloszenia_moje')=='TAK') {
		$(document).ready(function() {
			$("#mz_priorytet_rozpoczecia").load('hd_p_zgloszenia_live_view.php?typ=mz_priorytet_rozpoczecia&randval='+ Math.random());
			$("#mz_priorytet_zakonczenia").load('hd_p_zgloszenia_live_view.php?typ=mz_priorytet_zakonczenia&randval='+ Math.random());
			$("#mz_p").load('hd_p_zgloszenia_live_view.php?typ=mz_p&randval='+ Math.random());
			//$("#mz_rnz").load('hd_p_zgloszenia_live_view.php?typ=mz_rnz&randval='+ Math.random());
			$("#mz_r").load('hd_p_zgloszenia_live_view.php?typ=mz_r&randval='+ Math.random());
			$("#mz_nz").load('hd_p_zgloszenia_live_view.php?typ=mz_nz&randval='+ Math.random());
	//		$("#mz_z").load('hd_p_zgloszenia_live_view.php?typ=mz_z&randval='+ Math.random());
			$("#mz_do").load('hd_p_zgloszenia_live_view.php?typ=mz_do&randval='+ Math.random());
			$("#mz_wf").load('hd_p_zgloszenia_live_view.php?typ=mz_wf&randval='+ Math.random());
			$("#mz_ws").load('hd_p_zgloszenia_live_view.php?typ=mz_ws&randval='+ Math.random());
			$('#count_notatki_moje').load('hd_count_notes.php?user_id=<?php echo $es_nr; ?>&randval='+ Math.random()).show();
			$('#notatki').load('hd_refresh_notes.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random());	
		});
	}
}

$(document).ready(function () {
  $('#ilosc_godzin_count').click(
    function()
    {
		$('#ilosc_godzin').load('hd_p_zgloszenia_count_hours.php?randval='+ Math.random()).show();
		return false;
    }
  ); 
});


if ((readCookie('hd_p_zgloszenia_div_nr')!='') && (readCookie('hd_p_zgloszenia_div_nr')!='$sciezka_do_cookie')) {
	$(document).ready(function() {
		$('#ZawartoscDIV').load(readCookie('hd_p_zgloszenia_div_nr'));
	});		
}	
	
function Refresh_Wszystkie() {
	$(document).ready(function() {
		$("#wz_priorytet_rozpoczecia").load('hd_p_zgloszenia_live_view.php?on_load=0&typ=wz_priorytet_rozpoczecia&randval='+ Math.random());
		$("#wz_priorytet_zakonczenia").load('hd_p_zgloszenia_live_view.php?on_load=0&typ=wz_priorytet_zakonczenia&randval='+ Math.random());
		$("#wz_ws").load('hd_p_zgloszenia_live_view.php?typ=wz_ws&randval='+ Math.random());
		$("#wz_n").load('hd_p_zgloszenia_live_view.php?typ=wz_n&randval='+ Math.random());
		$("#wz_rnz").load('hd_p_zgloszenia_live_view.php?typ=wz_rnz&randval='+ Math.random());
		$("#wz_do").load('hd_p_zgloszenia_live_view.php?typ=wz_do&randval='+ Math.random());
		$("#wz_p").load('hd_p_zgloszenia_live_view.php?typ=wz_p&randval='+ Math.random());
		$("#wz_r").load('hd_p_zgloszenia_live_view.php?typ=wz_r&randval='+ Math.random());	  
		$("#wz_nz").load('hd_p_zgloszenia_live_view.php?typ=wz_nz&randval='+ Math.random());
		//$("#wz_z").load('hd_p_zgloszenia_live_view.php?typ=wz_z&randval='+ Math.random());
		//$("#wz_w").load('hd_p_zgloszenia_live_view.php?typ=wz_w&randval='+ Math.random());
		$("#wz_wf").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_wf&randval='+ Math.random());
	});
}

if (readCookie('hd_p_zgloszenia_moje')=='TAK') {
	$(document).ready(function() {
		$("#mz_priorytet_rozpoczecia").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_priorytet_rozpoczecia&randval='+ Math.random());		
		$("#mz_priorytet_zakonczenia").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_priorytet_zakonczenia&randval='+ Math.random());
		$("#mz_p").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_p&randval='+ Math.random());
		//$("#mz_rnz").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_rnz&randval='+ Math.random());
		$("#mz_r").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_r&randval='+ Math.random());
		$("#mz_nz").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_nz&randval='+ Math.random());
	//	$("#mz_z").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_z&randval='+ Math.random());
		$("#mz_do").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_do&randval='+ Math.random());
		$("#mz_wf").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_wf&randval='+ Math.random());
		$("#mz_ws").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=mz_ws&randval='+ Math.random());
	});
}

if (readCookie('hd_p_zgloszenia_wszystkie')=='TAK') {
	$(document).ready(function() {
		$("#wz_priorytet_rozpoczecia").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_priorytet_rozpoczecia&randval='+ Math.random());
		$("#wz_priorytet_zakonczenia").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_priorytet_zakonczenia&randval='+ Math.random());
	//	$("#wz_ws").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_ws&randval='+ Math.random());
		$("#wz_n").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_n&randval='+ Math.random());
		$("#wz_do").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_do&randval='+ Math.random());
		$("#wz_rnz").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_rnz&randval='+ Math.random());
	//	$("#wz_p").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_p&randval='+ Math.random());
	//	$("#wz_r").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_r&randval='+ Math.random());	  
	//	$("#wz_nz").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_nz&randval='+ Math.random());
	//	$("#wz_z").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_z&randval='+ Math.random());
	//	$("#wz_w").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_w&randval='+ Math.random());
	
		$("#wz_p").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_p&randval='+ Math.random());
	//	$("#wz_rnz").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_rnz&randval='+ Math.random());
		$("#wz_r").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_r&randval='+ Math.random());
		$("#wz_nz").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_nz&randval='+ Math.random());		
		//$("#wz_z").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_z&randval='+ Math.random());
		//$("#wz_w").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_w&randval='+ Math.random());
		$("#wz_ws").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_ws&randval='+ Math.random());		
		$("#wz_wf").load('hd_p_zgloszenia_live_view.php?on_load=1&typ=wz_wf&randval='+ Math.random());
			
	});
}

if (readCookie('hd_p_zgloszenia_moje')=='TAK') {
	$(document).ready(function() {
	   var refreshId_co_1_minute_mz = setInterval(function() { 
		  $("#mz_p").load('hd_p_zgloszenia_live_view.php?typ=mz_p&randval='+ Math.random());
		  //$("#mz_rnz").load('hd_p_zgloszenia_live_view.php?typ=mz_rnz&randval='+ Math.random());
		  $("#mz_r").load('hd_p_zgloszenia_live_view.php?typ=mz_r&randval='+ Math.random());
		  $("#mz_nz").load('hd_p_zgloszenia_live_view.php?typ=mz_nz&randval='+ Math.random());
	   }, 60000);
	});
}
 
if (readCookie('hd_p_zgloszenia_wszystkie')=='TAK') {
  	$(document).ready(function() {
	   var refreshId_co_1_minute_wz = setInterval(function() { 
		  $("#wz_n").load('hd_p_zgloszenia_live_view.php?typ=wz_n&randval='+ Math.random());
		  $("#wz_rnz").load('hd_p_zgloszenia_live_view.php?typ=wz_rnz&randval='+ Math.random());
	   }, 60000);
	});
}

if (readCookie('hd_p_zgloszenia_moje')=='TAK') {	
	$(document).ready(function() {   
		var refreshId_co_5_minut_mz = setInterval(function() { 	
			//$("#mz_z").load('hd_p_zgloszenia_live_view.php?typ=mz_z&randval='+ Math.random());
			$("#mz_ws").load('hd_p_zgloszenia_live_view.php?typ=mz_ws&randval='+ Math.random());
			$("#mz_wf").load('hd_p_zgloszenia_live_view.php?typ=mz_wf&randval='+ Math.random());			
			$("#mz_priorytet_rozpoczecia").load('hd_p_zgloszenia_live_view.php?on_load=0&typ=mz_priorytet_rozpoczecia&randval='+ Math.random());
			$("#mz_priorytet_zakonczenia").load('hd_p_zgloszenia_live_view.php?on_load=0&typ=mz_priorytet_zakonczenia&randval='+ Math.random());
		}, 300000);
	});
}

if (readCookie('hd_p_zgloszenia_wszystkie')=='TAK') {	
	$(document).ready(function() {   
		var refreshId_co_5_minut_wz = setInterval(function() { 	
			$("#wz_p").load('hd_p_zgloszenia_live_view.php?typ=wz_p&randval='+ Math.random());
			$("#wz_rnz").load('hd_p_zgloszenia_live_view.php?typ=wz_rnz&randval='+ Math.random());
			$("#wz_r").load('hd_p_zgloszenia_live_view.php?typ=wz_r&randval='+ Math.random());
			$("#wz_nz").load('hd_p_zgloszenia_live_view.php?typ=wz_nz&randval='+ Math.random());		
			//$("#wz_z").load('hd_p_zgloszenia_live_view.php?typ=wz_z&randval='+ Math.random());
			//$("#wz_w").load('hd_p_zgloszenia_live_view.php?typ=wz_w&randval='+ Math.random());
			$("#wz_ws").load('hd_p_zgloszenia_live_view.php?typ=wz_ws&randval='+ Math.random());		
			$("#wz_wf").load('hd_p_zgloszenia_live_view.php?typ=wz_wf&randval='+ Math.random());			
			$("#wz_priorytet_rozpoczecia").load('hd_p_zgloszenia_live_view.php?on_load=0&typ=wz_priorytet_rozpoczecia&randval='+ Math.random());
			$("#wz_priorytet_zakonczenia").load('hd_p_zgloszenia_live_view.php?on_load=0&typ=wz_priorytet_zakonczenia&randval='+ Math.random());
		}, 300000); 
		
	});	
}
</script>

<script>

function MojeZgloszeniaShow() {
	document.getElementById('tr_mz_priorytet_rozpoczecia').style.display=''; 
	document.getElementById('tr_mz_priorytet_zakonczenia').style.display=''; 
	document.getElementById('tr_mz_p').style.display=''; 
//	document.getElementById('tr_mz_rnz').style.display=''; 
	document.getElementById('tr_mz_r').style.display=''; 
	document.getElementById('tr_mz_wf').style.display=''; 
	document.getElementById('tr_mz_ws').style.display=''; 
	document.getElementById('tr_mz_nz').style.display=''; 
	document.getElementById('tr_mz_z').style.display=''; 
	document.getElementById('tr_mz_do').style.display=''; 
	return false;
}

function MojeZgloszeniaHide() {
	document.getElementById('tr_mz_priorytet_rozpoczecia').style.display='none'; 
	document.getElementById('tr_mz_priorytet_zakonczenia').style.display='none'; 
	document.getElementById('tr_mz_p').style.display='none'; 
//	document.getElementById('tr_mz_rnz').style.display='none';
	document.getElementById('tr_mz_r').style.display='none'; 
	document.getElementById('tr_mz_wf').style.display='none'; 	
	document.getElementById('tr_mz_ws').style.display='none'; 
	document.getElementById('tr_mz_nz').style.display='none'; 
	document.getElementById('tr_mz_z').style.display='none'; 
	document.getElementById('tr_mz_do').style.display='none'; 	
	return false;
}

function WszystkieZgloszeniaShow() {
	document.getElementById('tr_wz_priorytet_rozpoczecia').style.display=''; 
	document.getElementById('tr_wz_priorytet_zakonczenia').style.display=''; 
	document.getElementById('tr_wz_p').style.display=''; 
	document.getElementById('tr_wz_rnz').style.display='';	
	document.getElementById('tr_wz_r').style.display=''; 
	document.getElementById('tr_wz_wf').style.display=''; 	
	document.getElementById('tr_wz_ws').style.display=''; 
	document.getElementById('tr_wz_nz').style.display=''; 
	document.getElementById('tr_wz_z').style.display=''; 
	document.getElementById('tr_wz_n').style.display=''; 
	document.getElementById('tr_wz_do').style.display=''; 	
	return false;
}

function PokazNotatki(what) {
	var state = '';
	if (what==true) state = '';
	if (what==false) state = 'none';
	
    for (i=0; i<30; i++) {
		if (document.getElementById('tr_notatki_'+i+'')) {
			document.getElementById('tr_notatki_'+i+'').style.display=state;
		} else break;
	}
	document.getElementById('tr_notatki_opcje').style.display=state;
	document.getElementById('tr_notatki').style.display=state;
	
}

<?php if ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_kontroli_pracownikow==1)) { ?>
function PokazPracownikow(what) {
	var state = '';
	if (what==true) state = '';
	if (what==false) state = 'none';
	
	for (i=0; i<30; i++) {
		if (document.getElementById('tr_pracownik_'+i+'')) {
			document.getElementById('tr_pracownik_'+i+'').style.display=state;
		} else break;
	}
	//document.getElementById('tr_notatki_opcje').style.display=state;

	document.getElementById('tr_pracownicy').style.display=state;
	
}
<?php } ?>

function WszystkieZgloszeniaHide() {
	document.getElementById('tr_wz_priorytet_rozpoczecia').style.display='none'; 
	document.getElementById('tr_wz_priorytet_zakonczenia').style.display='none'; 
	document.getElementById('tr_wz_p').style.display='none'; 
	document.getElementById('tr_wz_rnz').style.display='none';
	document.getElementById('tr_wz_r').style.display='none'; 
	document.getElementById('tr_wz_wf').style.display='none'; 	
	document.getElementById('tr_wz_ws').style.display='none'; 
	document.getElementById('tr_wz_nz').style.display='none'; 
	document.getElementById('tr_wz_z').style.display='none'; 
	document.getElementById('tr_wz_n').style.display='none'; 
	document.getElementById('tr_wz_do').style.display='none'; 
	return false;
}

function ClearCookie (name, value) {  
	document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}

function CountCheckboxesChecked(){
    var elLength = document.obsluga.elements.length;

	var count_checked = 0;
    for (i=0; i<elLength; i++)
    {
        var type = obsluga.elements[i].type;
        if (type=="checkbox" && obsluga.elements[i].checked){
            count_checked = count_checked + 1;
        }
    }
	return count_checked;
}

function SetCookie (name, value) {  
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

function PokazNrZgloszen() {

	SetCookie('byly_zmiany', '0');
	var elLength = document.obsluga.elements.length;
	var count_checked = 0;
	var params = "nr=";

	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;
		
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.obsluga.elements[i].value + ",";
        }

        if (type=="radio" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.obsluga.elements[i].value + ",";
        }
		
	}
	
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}
	
	var dl = params.length;
	var dl2 = params2.length;
	
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnego zgłoszenia');
		return false;
	}
	
	ClearCookie('saved_seryjna_obsluga_zgloszen', '1');
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "hd_o_zgloszenia_s.php?";		
		var lokacja2 = "hd_o_zgloszenia.php?action=obsluga&";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 1;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "hd_o_zgloszenia.php?action=obsluga&" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}

	if (okno_max==0) {
		var x = 800;
		var y = 600;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	NewWindowOpen = window.open(lokacja, "eSerwisOSZ", opcje);
	NewWindowOpen.focus;
	//return false;
}

function MarkCheckboxes1(x,b,t,j){
	if (typeof x!='object')x=document.forms[x];
	else x=x.form
	for(j=0;t=x.getElementsByTagName("input")[j++];)
		if( /chec/i.test( t.type ) )t.checked=b!=-1?b:!t.checked;
}

function UpdateIloscZaznaczen() {
	var elLength = document.obsluga.elements.length;
	
	var count_checked = 0;
	var count_radio = 0;
	
    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;
		
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) count_checked = count_checked + 1;

        if (type=="radio" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) count_radio = count_radio + 1;
		
	}
	
	if (count_checked>=0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;	
		document.getElementById('IloscZaznaczonych').textContent=count_checked;
		if (count_checked==0) {
			document.getElementById('ObslugaZgloszen').style.display='none';
			document.getElementById('NapisPrzed').style.display='none'; 
			if (document.getElementById('FSPrzypiszDoOsoby'))
				document.getElementById('FSPrzypiszDoOsoby').style.display='none';
		} else {
			document.getElementById('ObslugaZgloszen').style.display='';
			document.getElementById('NapisPrzed').style.display=''; 
			if (document.getElementById('FSPrzypiszDoOsoby'))
				document.getElementById('FSPrzypiszDoOsoby').style.display='inline';
		}
	}
	
	if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
		document.getElementById('IloscZaznaczonych').textContent=count_radio;
		if (count_radio>0) {
			document.getElementById('NapisPrzed').style.display=''; 
			document.getElementById('ObslugaZgloszen').style.display=''; 
			if (document.getElementById('FSPrzypiszDoOsoby'))			
				document.getElementById('FSPrzypiszDoOsoby').style.display='inline';
		} else {
			document.getElementById('ObslugaZgloszen').style.display='none';
			document.getElementById('NapisPrzed').style.display='none'; 
			if (document.getElementById('FSPrzypiszDoOsoby'))
				document.getElementById('FSPrzypiszDoOsoby').style.display='none';
		}
	}
	
}

function OdznaczRadioButton(obj) {
  for (var i=0; i < obj.length; i++) {
	if (obj[i].type=='radio') obj[i].checked = false;
  }
  UpdateIloscZaznaczen();
}

function OdznaczWszystkieCheckboxy(obj) {
	for(var i=0,l=obj.length; i<l; i++) {	
		if (obj[i].type == 'checkbox') {
			obj[i].checked=false;
		}
	}
	UpdateIloscZaznaczen();
}

function MarkCheckboxes(akcja) {
	for(var i=0,l=document.obsluga.elements.length; i<l; i++) {
		
		if (document.obsluga.elements[i].type == 'checkbox') {
			if (akcja=='odwroc') {
				document.obsluga.elements[i].checked=document.obsluga.elements[i].checked?false:true;
			} else if (akcja=='zaznacz') {
				document.obsluga.elements[i].checked=true;
			} else if (akcja=='odznacz') {
				document.obsluga.elements[i].checked=false;
			}
		}	
	}
	UpdateIloscZaznaczen();
	OdznaczRadioButton(document.obsluga.markzgl);
}

function SelectTRById(v) {
	UpdateIloscZaznaczen();	
}
</script>

<script>

$('#ilosc_godzin').load('hd_p_zgloszenia_count_hours.php?randval='+ Math.random()).show();
$('#count_notatki_moje').load('hd_count_notes.php?user_id=<?php echo $es_nr; ?>&randval='+ Math.random()).show();
$('#notatki').load('hd_refresh_notes.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random());
// $('#pracownicy').load('hd_refresh_pracownicy.php?randval='+ Math.random());
</script>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['szukaj'].elements['search_data']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<script>
if (readCookie('hd_p_zgloszenia_pokaz_statystyki')=='TAK') {
	$('#PodgladStatystyk').show();
	$('#zwin_stats').show();
	$('#pokaz_stats').hide();
}
if (readCookie('hd_p_zgloszenia_pokaz_statystyki')=='NIE') {
	$('#PodgladStatystyk').hide();
	$('#zwin_stats').hide();
	$('#pokaz_stats').show();
} 
</script>
<script>
<?php if ($ClueTipOn==1) { ?>
	$(document).ready(function() { $('a.title').cluetip({splitTitle: '|'}); });
<?php } ?>

<?php if ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_kontroli_pracownikow==1)) { ?>
$('#pracownicy').load('hd_refresh_pracownicy.php?randval=<?php echo date('HHiiss');?>'+ Math.random());
if (readCookie('hd_pokaz_pracownikow')=='TAK') { $('#pracownicy').show(); } else { $('#pracownicy').hide(); }
<?php } ?>

<?php if ($_REQUEST[search]=='search-wyniki') { ?>
	$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=<?php echo $temp_nr;?>&id=<?php echo $temp_id; ?>&randval=<?php echo $rand;?>');
	createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); 
	$('#info').hide(); 
	self.location.href='#SzczegolyZgloszenia';
<?php } ?>

</script>
<script>anylinkcssmenu.init("anchorclass");</script>
<script>
document.getElementById('TrwaLadowanie').style.display='none';
</script>