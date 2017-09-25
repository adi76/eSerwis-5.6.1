<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="javascript">

function eraseCookie(name) {
    createCookie(name,"",-1);
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

<?php include('cfg_adres.php'); ?>
//  wersja okienkowa

//	var winx=275;
//	var winy=160;
// 	var xx=Math.round((window.screen.width-winx)/2);
// 	var yy=Math.round((window.screen.height-winy)/2);

// wersja fullscreen

// 	var xx=window.screen.Width;
// 	var yy=window.screen.availHeight; 	

 	var xx=window.screen.availWidth-10;
 	var yy=window.screen.availHeight-30; 	

	var opcje='width='+xx+', innerWidth='+xx+', height='+yy+', innerHeight='+yy+', top=0, left=0, screenX=0, screenY=0, scrollbars, menubar, statusbar, ';
	
//	if (navigator.appName != "Opera")
//		{
			neww=window.open("<?php echo $linkdostronylogowania;?>login1.php?key=5c18tpdr749nkh60w3y2sbxvqfjzmg0ng3zwxq95ypdmj67tc8vkb2hsf14r","eSerwis",opcje);				
			var aw = screen.availWidth;
			var ah = screen.availHeight;
			neww.moveTo(0, 0);
			neww.resizeTo(aw, ah);
//	} else {
//			alert("Uruchom bazę używając Internet Explorer'a lub Mozilli Firefox");
//			self.close();
//			}

eraseCookie('max_x');
eraseCookie('max_y');

createCookie('max_x',xx,30);
createCookie('max_y',yy,30);

// wyczyść inne pliki cookie
eraseCookie('hd_p_zgloszenia_div_nr');

</script>
</head>
<body OnLoad="neww.focus();">
<script>	
	onload=window.opener=null;window.close();
	if (neww) neww.focus();
</script>

<noscript>
<center>
<span>
<br />
<font color="red">Do normalnej pracy bazy eSerwis wymagane jest włączenie obsługi JavaScript.</font>
</span>
</center>
</noscript>
</body>
</html>