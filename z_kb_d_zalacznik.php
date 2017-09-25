<?php include_once('header_begin.php'); ?>

<?php if ($_GET[edit]==1) { ?>

<link rel="stylesheet" href="css/uploadify/uploadify.css" type="text/css" />
<link rel="stylesheet" href="css/uploadify/css/uploadify.jGrowl.css" type="text/css" />
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.uploadify.js"></script>
<script type="text/javascript" src="js/jquery/jquery.jgrowl_minimized.js"></script>
<script type="text/javascript">
function startUpload(id, conditional) {
	if(conditional.value.length != 0) {
		$('#'+id).fileUploadStart();
	} else
		alert("You must enter your name. Before uploading");
}
</script>

<script type="text/javascript">

$(document).ready(function() {
	$("#fileUploadgrowl").fileUpload({
		'uploader': 'uploadify/uploader.swf',
		'cancelImg': 'uploadify/cancel.png',
		'script': 'uploadify/upload_kb.php',
		'folder': 'attachements_kb',
		'fileDesc': '',
		'fileExt': '*.jpg;*.png;*.gif;*.zip;*.rar;*.7z;*.doc;*.txt;*.xls;*.pdf;',
		'fileDesc': '*.jpg;*.png;*.gif;*.zip;*.rar;*.7z;*.doc;*.txt;*.xls;*.pdf;',
		'multi': true,
		'buttonText':'Dodaj nowe pliki',
		'simUploadLimit': 1,
		'sizeLimit': 9437184,
		onError: function (event, queueID ,fileObj, errorObj) {
			var msg;
			if (errorObj.status == 404) {
				alert('Could not find upload script. Use a path relative to: '+'<?= getcwd() ?>');
				msg = 'Could not find upload script.';
			} else if (errorObj.type === "HTTP")
				msg = errorObj.type+": "+errorObj.status;
			else if (errorObj.type ==="File Size")
				msg = fileObj.name+'<br>Przekroczony max. rozmiar pliku: '+Math.round(errorObj.sizeLimit/(1024*1024))+'MB';
			else
				msg = errorObj.type+": "+errorObj.text;
			$.jGrowl('<p></p>'+msg, {
				theme: 	'error',
				header: 'Wystąpił błąd',
				sticky: true
			});			
			$('#startupload1').hide();
			$("#fileUploadgrowl" + queueID).fadeOut(250, function() { $("#fileUploadgrowl" + queueID).remove()});
			return false;
		},
		onCancel: function (a, b, c, d) {
			var msg = c.name;
			$.jGrowl('<p></p>'+msg, {
				theme: 	'warning',
				header: 'Usunięto plik z listy',
				life:	4000,
				sticky: false
			});
		},
		onClearQueue: function (a, b) {
			var msg = "Cleared "+b.fileCount+" files from queue";
			$.jGrowl('<p></p>'+msg, {
				theme: 	'warning',
				header: 'Cleared Queue',
				life:	4000,
				sticky: false
			});
		},
		onComplete: function (a, b ,c, d, e) {
			var size = Math.round(c.size/1024);
			$.jGrowl('<p></p>'+c.name+' - '+size+'KB', {
				theme: 	'success',
				header: 'Wysyłanie zakończone',
				life:	4000,
				sticky: false
			});
		},
		onAllComplete : function(event) {
			self.location.reload(true);
			}		
	});
	
	$('#startupload1').bind('click', function(){
		$('#fileUploadgrowl').fileUploadSettings('scriptData','&nameprefix='+$('#nameprefix').val());
	//	$('#startupload1').hide();
	//	$('#clearqueue').hide();
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
	pageheader('Dodawanie załączników do wątku z bazy wiedzy',0,0);
	infoheader(''.$_GET[pytanie].'');
} else {
	pageheader('Przeglądanie załączników do wątku z bazy wiedzy',0,0);
	infoheader(''.$_GET[pytanie].'');
}
?>
<?php if ($_GET[edit]==1) { ?>
<input name="nameprefix" id="nameprefix" type="hidden" maxlength="255" size="50" value="<?php echo $_GET[pytanie_id]; ?>" />
<input name="friendly_name" id="friendly_name" type="hidden" maxlength="50" size="20" value="" />
<div style="background-color:#FCFCAA; border:1px solid #F9F943; padding:5px; margin-bottom:2px">
Dozwolone pliki: <b>JPG, GIF, PNG, ZIP, RAR, 7Z, TXT, DOC, XLS, PDF</b><br />
</div>
<div style="background-color:#FCFCAA; border:1px solid #F9F943; padding:5px;margin-bottom:5px;">
Maksymalny rozmiar pojedyńczego pliku: <b>9MB</b>
</div>
&nbsp;
<div style="background-color:grey; border:1px solid #black; padding:5px;margin-bottom:5px;color:white;">Spacja oraz polskie znaki w nazwach plików będą konwertowane do znaku "_"</div>
&nbsp;
<div id="fileUploadgrowl"><h2>Aby móc załączać pliki, wymagany jest plugin do FlashPlayera.<br /><br />Ściągnij wymagany plugin:
<a class="buttons" target="_blank" href="download/IE/install_flash_player_ax.exe">Dla Internet Explorer'a</a>
&nbsp;<a class="buttons" target="_blank" href="download/nonIE/install_flash_player.exe">Ściągnij plugin dla pozostałych przeglądarek</a></h2>
</div>
<hr style="margin-bottom:10px;" />
&nbsp;
<a id="newueue" class="buttons normalfont" href="#" onClick="if (confirm('Czy napewno chcesz utworzyć nową listę plików do dodania ?')) self.location.reload(true); return false;">Utwórz nową listę</a>	
<a style="display:none" id="clearqueue" class="buttons normalfont"  href="javascript:$('#fileUploadgrowl').fileUploadClearQueue()">Czyść listę</a>
<span style="float:right">
<a id="startupload1" class="buttons normalfont" href="javascript:$('#fileUploadgrowl').fileUploadStart();">Zacznij wysyłać</a>
</span>
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

					case "PDF" :$thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">'; 
								$thelist .= '<img src=img/attachements_pdf.gif border=0>&nbsp;'; break;
					
					case "XLS" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
									$thelist .= '<img src=img/attachements_excel.gif border=0>&nbsp;'; break;
					
					case "ZIP" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_zip.gif border=0>&nbsp;'; break;
					
					case "RAR" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_rar.gif border=0>&nbsp;'; break;
					
					case ".7Z" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_7z.gif border=0>&nbsp;'; break;				
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
okheader('Lista już dołączonych plików do wątku<br /><br /><b>'.$_GET[pytanie].'</b><br /><br /><input type=button class=buttons value=\'Odśwież listę\' onClick=\'self.location.reload(true); return false;\' />',0,0);
if (strlen($thelist)>161) {
	echo $thelist;
} else errorheader("Do tego pytania brak jest załączników");

echo "<span style='float:right'>";
echo "<input type=button class=buttons value='Zamknij' onClick='self.close(); if (opener) { opener.location.reload(true); }'>";
?>
</body>
</html>