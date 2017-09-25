<?php include_once('header_begin.php'); ?>

<?php if ($_GET[edit]==1) { ?>
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
<div id="podglad_img">
</div>

<?php
include_once('cfg_eserwis.php');
if ($_GET[edit]==1) {
	pageheader('Dodawanie załączników do kroku '.$_GET[krok].' zgłoszenia nr '.$_GET[zgl].'',0,0);
} else {
	pageheader('Przeglądanie załączników do kroku '.$_GET[krok].' zgłoszenia nr '.$_GET[zgl].'',0,0);
}
?>
<?php if ($_GET[edit]==1) { ?>

<?php   
   include_once('uploadfile/php_upload.php');

   $upload = new PhpUpload;
   $upload->FullDisplay(true);  // << To Display Full Form
   $upload->SetUploadDir('attachements'); // << Set Upload directory
   $upload->ReturnBool(false); // << Class returns only true, false
    
   $upload->SetExts('jpg|gif|ini');
   //$upload->NewName('subor.jpg'); // Set new filename
   
   //$upload->ReturnOutput(true); << Return Output 
   $upload->SetMaxSize(3000); // << Max File Size in KB
   $upload->SetErrorTypes('Problem z php.ini', 'WystÄ…piĹ‚ bĹ‚Ä…d podczas wysyĹ‚ania', 'Niedozwolone rodzaj pliku', 'Przekroczony maksymalny rozmiar wysyłanego pliku: $ kB');   // << Client error types
     
   $upload->ShowUpload('PhpUpload1');
   
   $upload->TryUpload(); // << If isset try upload file
?>  

<hr style="margin-top:10px;" />
<?php } ?>
<?php
$thelist = '';
$iii = 1;
$thelist .= '<center><table width=100%><tr><th width=20 style=\'text-align:center\'>LP</th><th>Nazwa</th>';
if ($_GET[edit]==1) $thelist .= '<th width=30 style=\'text-align:center\'>Opcje</th>';
$thelist .= '</tr>';

if ($handle = opendir('attachements')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
		
			$l = strlen($_GET[zglszcz_id])+1;
			
			$porow = $_GET[zglszcz_id].'_';
			
			$newname = substr($file,$l,strlen($file));
			
			$ext = strtoupper(substr($file,strlen($file)-3,3));
		
			if (substr($file,0,$l)==$porow) {
				
				if ($iii % 2 != 0 ) $thelist .= "<tr style='background-color:#E3E3E3'>";
				if ($iii % 2 == 0 ) $thelist .= "<tr style='background-color:#DEDEDE'>";
				
				$thelist .= "<td style='text-align:center'>".$iii."</td><td>";
				switch ($ext) {
					case "JPG" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '&nbsp;'; break;
									
					case "GIF" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '&nbsp;'; break;
									
					case "PNG" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '<img src=img/attachements_image.gif border=0>&nbsp;'; break;
					
					case "TXT" : $thelist .= '<a target=_blank class=normalfont href="attachements/'.$file.'">';
								$thelist .= '<img src=img/attachements_text.gif border=0>&nbsp;'; break;
					
					case "DOC" :$thelist .= '<a target=_blank class=normalfont href="attachements/'.$file.'">'; 
								$thelist .= '<img src=img/attachements_word.gif border=0>&nbsp;'; break;
					
					case "XLS" : $thelist .= '<a target=_blank class=normalfont href="attachements/'.$file.'">';
									$thelist .= '<img src=img/attachements_excel.gif border=0>&nbsp;'; break;
					
					case "ZIP" : $thelist .= '<a target=_blank class=normalfont href="attachements/'.$file.'">';
								$thelist .= '<img src=img/attachements_zip.gif border=0>&nbsp;'; break;
					
					case "RAR" : $thelist .= '<a target=_blank class=normalfont href="attachements/'.$file.'">';
								$thelist .= '<img src=img/attachements_rar.gif border=0>&nbsp;'; break;
					
					case ".7Z" : $thelist .= '<a target=_blank class=normalfont href="attachements/'.$file.'">';
								$thelist .= '<img src=img/attachements_7z.gif border=0>&nbsp;'; break;				
				}
				
				

				$thelist .= $newname.'</a></td>';

				if ($_GET[edit]==1) {		
					$thelist .= "<td style='text-align:center'>";
					$thelist .= "<a title=' Usuń załącznik o nazwie ".$file." z kroku nr ".$_GET[krok]." zgłoszenia nr ".$_GET[zgl]."'><input class=imgoption type=image src=img/attachements_delete.gif onclick=\"newWindow_r(600,150,'hd_u_zalacznik.php?path=".urlencode('attachements/'.$file.'')."&nazwapliku=".urlencode($newname)."');\"></a>";
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
okheader('Lista już dołączonych plików do kroku '.$_GET[krok].' zgłoszenia nr '.$_GET[zgl].' <input type=button class=buttons value=\'Odśwież listę\' onClick=\'self.location.reload(true); return false;\' />',0,0);
if (strlen($thelist)>161) {
	echo $thelist;
} else errorheader("Do tego kroku brak jest załączników");

echo "<span style='float:right'>";
echo "<input type=button class=buttons value='Zamknij' onClick='if (opener) opener.location.reload(true); self.close();'>";
?>

</body>
</html>