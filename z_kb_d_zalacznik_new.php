<?php include_once('header_begin.php'); ?>

<?php if ($_GET[edit]==1) { ?>

<link rel="stylesheet" href="js/jquery/uploadify.css" type="text/css" />
<script type="text/javascript" src="js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.uploadify-3.1.js"></script>

<script type="text/javascript">

$(function() {
	$("#file_upload").uploadify({
		'swf'		: 'js/jquery/uploadify.swf',
		'uploader'	: 'js/jquery/uploadify.php',
		'buttonText' : 'Wybierz plik',
		'formData' 	: { 'pyt_id' : '<?php echo $_REQUEST[pytanie_id]; ?>'},
		'fileSizeLimit' : '10MB',
		'folder'	: 'attachements_kb',
		'auto' : true
	});
});

</script>

<?php } ?>

<link rel="stylesheet" href="js/highslide/highslide/highslide.css" type="text/css" />
<script type="text/javascript" src="js/highslide/highslide/highslide-full.packed.js"></script>

<script type="text/javascript">
//<![CDATA[
hs.registerOverlay({
	html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
	position: 'top right',
	fade: 2 // fading the semi-transparent overlay looks bad in IE
});

hs.graphicsDir = 'js/highslide/highslide/graphics/';
hs.wrapperClassName = 'borderless';
//]]>
</script>

</head>
<body>
<div id="podglad_img"></div>

<?php
include_once('cfg_eserwis.php');
if ($_GET[edit]==1) {
	pageheader('Dodawanie załączników do pytania z bazy wiedzy (<b>NOWE</b>)',0,0);
	infoheader(''.$_GET[pytanie].'');
} else {
	pageheader('Przeglądanie załączników do pytania z bazy wiedzy',0,0);
	infoheader(''.$_GET[pytanie].'');
}
?>
<?php if ($_GET[edit]==1) { ?>
<input name="nameprefix" id="nameprefix" type="hidden" maxlength="255" size="50" value="<?php echo $_GET[pytanie_id]; ?>" />
<input name="friendly_name" id="friendly_name" type="hidden" maxlength="50" size="20" value="" />
<div style="background-color:#FCFCAA; border:1px solid #F9F943; padding:5px; margin-bottom:2px">
Dozwolone pliki: <b>JPG, GIF, PNG, ZIP, RAR, 7Z, TXT, DOC, XLS</b><br />
</div>
<div style="background-color:#FCFCAA; border:1px solid #F9F943; padding:5px;margin-bottom:5px;">
Maksymalny rozmiar pojedyńczego pliku: <b>10MB</b>. Pliki o większym rozmiarze nie zostaną wgrane na serwer.
</div>
&nbsp;
<div style="background-color:grey; border:1px solid #black; padding:5px;margin-bottom:5px;color:white;">Spacja oraz polskie znaki w nazwach plików będą konwertowane do znaku "_"</div>
&nbsp;
<input type="file" name="file_upload" id="file_upload" />
<!--- &nbsp;&nbsp;<input type="button" class="buttons" onClick="javascript:$('#file_upload').uploadify('upload','*')" value="Rozpocznij wysyłanie" /> --->
&nbsp;<input type="button" class="buttons" onClick="javascript:$('#file_upload').uploadify('stop')" value="Zatrzymaj wysyłanie" />
<hr style="margin-top:10px;" />
<?php } ?>
<?php
$thelist = '';
$iii = 1;
$thelist .= '<center><table width=100%><tr><th width=20 style=\'text-align:center\'>LP</th><th>Nazwa</th>';
if ($_GET[edit]==1) $thelist .= '<th width=30 style=\'text-align:center\'>Opcje</th>';
$thelist .= '</tr>';

if ($handle = opendir('attachements_kb')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
		
			$l = strlen($_GET[pytanie_id])+1;
			
			$porow = $_GET[pytanie_id].'_';
			
			$newname = substr($file,$l,strlen($file));
			
			$ext = strtoupper(substr($file,strlen($file)-3,3));
		
			if (substr($file,0,$l)==$porow) {
				
				if ($iii % 2 != 0 ) $thelist .= "<tr style='background-color:#E3E3E3'>";
				if ($iii % 2 == 0 ) $thelist .= "<tr style='background-color:#DEDEDE'>";
				
				$thelist .= "<td style='text-align:center'>".$iii."</td><td>";
				switch ($ext) {
					case "JPG" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements_kb/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '&nbsp;'; break;
									
					case "GIF" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements_kb/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '&nbsp;'; break;
									
					case "PNG" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements_kb/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '<img src=img/attachements_image.gif border=0>&nbsp;'; break;
					
					case "TXT" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_text.gif border=0>&nbsp;'; break;
					
					case "DOC" :$thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">'; 
								$thelist .= '<img src=img/attachements_word.gif border=0>&nbsp;'; break;
					
					case "XLS" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
									$thelist .= '<img src=img/attachements_excel.gif border=0>&nbsp;'; break;
					
					case "ZIP" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_zip.gif border=0>&nbsp;'; break;
					
					case "RAR" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_rar.gif border=0>&nbsp;'; break;
					
					case ".7Z" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_7z.gif border=0>&nbsp;'; break;				
					case "PDF" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_pdf.gif border=0>&nbsp;'; break;		
				}
				
				

				$thelist .= $newname.'</a></td>';

				if ($_GET[edit]==1) {		
					$thelist .= "<td style='text-align:center'>";
					$thelist .= "<a title=' Usuń załącznik o nazwie ".$file." z pytania ".$_GET[pytanie]."'><input class=imgoption type=image src=img/attachements_delete.gif onclick=\"newWindow_r(600,150,'hd_u_zalacznik.php?path=".urlencode('attachements_kb/'.$file.'')."&nazwapliku=".urlencode($newname)."');\"></a>";
					$thelist .= "</td>";
				}

				$thelist .= "</tr>";
				$iii++;
			
				
			}
		}
		
	}
	closedir($handle);
}
$thelist.='</table></center>';
okheader('Lista już dołączonych plików do tego pytania '.$_GET[pytanie].' <input type=button class=buttons value=\'Odśwież listę\' onClick=\'self.location.reload(true); return false;\' />',0,0);
if (strlen($thelist)>161) {
	echo $thelist;
} else errorheader("Do tego pytania brak jest załączników");

echo "<span style='float:right'>";
echo "<input type=button class=buttons value='Zamknij' onClick='if (opener) opener.location.reload(true); self.close();'>";
?>
</body>
</html>