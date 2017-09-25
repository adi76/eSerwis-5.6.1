<?php
$pliki_wyroznione = array($folder."/main.php");
if ((array_search($PHP_SELF, $pliki_wyroznione)>-1) && ($wersja_light!='on') && ($_GET[action]=='')) {
?>
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
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
function refresh_hd() {
	 $(document).ready(function() {
		  $("#mz_p").load('hd_p_zgloszenia_live_view.php?typ=mz_p&randval='+ Math.random());
		  $("#mz_r").load('hd_p_zgloszenia_live_view.php?typ=mz_r&randval='+ Math.random());
		  $("#mz_nz").load('hd_p_zgloszenia_live_view.php?typ=mz_nz&randval='+ Math.random());
		  $("#mz_z").load('hd_p_zgloszenia_live_view.php?typ=mz_z&randval='+ Math.random());

		  $("#mz_ws").load('hd_p_zgloszenia_live_view.php?typ=mz_ws&randval='+ Math.random());
		  $("#wz_ws").load('hd_p_zgloszenia_live_view.php?typ=wz_ws&randval='+ Math.random());
		  
		  $("#wz_n").load('hd_p_zgloszenia_live_view.php?typ=wz_n&randval='+ Math.random());
		  $("#wz_p").load('hd_p_zgloszenia_live_view.php?typ=wz_p&randval='+ Math.random());
		  $("#wz_r").load('hd_p_zgloszenia_live_view.php?typ=wz_r&randval='+ Math.random());
		  $("#wz_nz").load('hd_p_zgloszenia_live_view.php?typ=wz_nz&randval='+ Math.random());
		  $("#wz_z").load('hd_p_zgloszenia_live_view.php?typ=wz_z&randval='+ Math.random());
		  $("#wz_w").load('hd_p_zgloszenia_live_view.php?typ=wz_w&randval='+ Math.random());
		  
		  $("#mz_ak").load('hd_p_zgloszenia_live_view.php?typ=mz_ak&randval='+ Math.random());
		  $("#mz_az").load('hd_p_zgloszenia_live_view.php?typ=mz_az&randval='+ Math.random());
		  $("#mz_pzwrupw").load('hd_p_zgloszenia_live_view.php?typ=mz_pzwrupw&randval='+ Math.random());
		  $("#wz_ak").load('hd_p_zgloszenia_live_view.php?typ=wz_ak&randval='+ Math.random());
		  $("#wz_az").load('hd_p_zgloszenia_live_view.php?typ=wz_az&randval='+ Math.random());
		  $("#wz_pzwrupw").load('hd_p_zgloszenia_live_view.php?typ=wz_pzwrupw&randval='+ Math.random());
		});
}

</script>
<script>
if (readCookie('pokaz_informacje_z_helpdeska')=='TAK') {
	 $(document).ready(function() {
	 
		  $("#mz_p").load('hd_p_zgloszenia_live_view.php?typ=mz_p&randval='+ Math.random());
		  $("#mz_r").load('hd_p_zgloszenia_live_view.php?typ=mz_r&randval='+ Math.random());
		  $("#mz_nz").load('hd_p_zgloszenia_live_view.php?typ=mz_nz&randval='+ Math.random());
		  $("#mz_z").load('hd_p_zgloszenia_live_view.php?typ=mz_z&randval='+ Math.random());

		  $("#mz_ws").load('hd_p_zgloszenia_live_view.php?typ=mz_ws&randval='+ Math.random());
		  $("#wz_ws").load('hd_p_zgloszenia_live_view.php?typ=wz_ws&randval='+ Math.random());
		  
		  $("#wz_n").load('hd_p_zgloszenia_live_view.php?typ=wz_n&randval='+ Math.random());
		  $("#wz_p").load('hd_p_zgloszenia_live_view.php?typ=wz_p&randval='+ Math.random());
		  $("#wz_r").load('hd_p_zgloszenia_live_view.php?typ=wz_r&randval='+ Math.random());
		  $("#wz_nz").load('hd_p_zgloszenia_live_view.php?typ=wz_nz&randval='+ Math.random());
		  $("#wz_z").load('hd_p_zgloszenia_live_view.php?typ=wz_z&randval='+ Math.random());
		  $("#wz_w").load('hd_p_zgloszenia_live_view.php?typ=wz_w&randval='+ Math.random());
		  
		  $("#mz_ak").load('hd_p_zgloszenia_live_view.php?typ=mz_ak&randval='+ Math.random());
		  $("#mz_az").load('hd_p_zgloszenia_live_view.php?typ=mz_az&randval='+ Math.random());
		  $("#mz_pzwrupw").load('hd_p_zgloszenia_live_view.php?typ=mz_pzwrupw&randval='+ Math.random());
		  $("#wz_ak").load('hd_p_zgloszenia_live_view.php?typ=wz_ak&randval='+ Math.random());
		  $("#wz_az").load('hd_p_zgloszenia_live_view.php?typ=wz_az&randval='+ Math.random());
		  $("#wz_pzwrupw").load('hd_p_zgloszenia_live_view.php?typ=wz_pzwrupw&randval='+ Math.random());

	   var refreshId_co_1_minute = setInterval(function() { 
		  $("#mz_p").load('hd_p_zgloszenia_live_view.php?typ=mz_p&randval='+ Math.random());
		  $("#mz_r").load('hd_p_zgloszenia_live_view.php?typ=mz_r&randval='+ Math.random());
		  $("#mz_nz").load('hd_p_zgloszenia_live_view.php?typ=mz_nz&randval='+ Math.random());
		  $("#wz_n").load('hd_p_zgloszenia_live_view.php?typ=wz_n&randval='+ Math.random());
		  
		  $("#mz_ak").load('hd_p_zgloszenia_live_view.php?typ=mz_ak&randval='+ Math.random());
		  $("#mz_az").load('hd_p_zgloszenia_live_view.php?typ=mz_az&randval='+ Math.random());
		  $("#mz_pzwrupw").load('hd_p_zgloszenia_live_view.php?typ=mz_pzwrupw&randval='+ Math.random());
		  $("#wz_ak").load('hd_p_zgloszenia_live_view.php?typ=wz_ak&randval='+ Math.random());
		  $("#wz_az").load('hd_p_zgloszenia_live_view.php?typ=wz_az&randval='+ Math.random());
		  $("#wz_pzwrupw").load('hd_p_zgloszenia_live_view.php?typ=wz_pzwrupw&randval='+ Math.random());
		  
	   }, 60000);
	   
	   var refreshId_co_5_minut = setInterval(function() { 	
		//	$("#mz_z").load('hd_p_zgloszenia_live_view.php?typ=mz_z&randval='+ Math.random());
			$("#wz_p").load('hd_p_zgloszenia_live_view.php?typ=wz_p&randval='+ Math.random());
			$("#wz_r").load('hd_p_zgloszenia_live_view.php?typ=wz_r&randval='+ Math.random());
			$("#wz_nz").load('hd_p_zgloszenia_live_view.php?typ=wz_nz&randval='+ Math.random());		
		//	$("#wz_z").load('hd_p_zgloszenia_live_view.php?typ=wz_z&randval='+ Math.random());
			$("#wz_w").load('hd_p_zgloszenia_live_view.php?typ=wz_w&randval='+ Math.random());
			$("#mz_ws").load('hd_p_zgloszenia_live_view.php?typ=mz_ws&randval='+ Math.random());
			$("#wz_ws").load('hd_p_zgloszenia_live_view.php?typ=wz_ws&randval='+ Math.random());		
			
	   }, 300000);
	   
	});
}
</script>

<?php } ?>