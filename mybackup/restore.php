<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo "$nazwa_aplikacji"; ?></title>		
<style>
/* = body, html = */
body{margin:0;padding:0;top:0;left:0;font:70% Tahoma,Arial,Sans-serif;position:static;color:#000000;background:#F5F5F5;}
html{font-size:100%;}
html>body{font-size:11px;}
/* = komunikaty = */
h2 /* RED */{font-size:small;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF5959;color:#000000;display: block; border: 1px solid #D50000;}
h3 /* ZIELONY */{font-size:small;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background:#79FF7A;color:Black;display: block; border: 1px solid #00A601;}
h5 /* KREMOWY */{font-size:small;font-weight:normal;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background:#FFFFA1;color:Black;display: block; border: 1px solid #FFD700;}
h4 /* SIWY */{font-size: small;font-weight: bolder;color:#F5F5F5;padding-top:5px;padding-bottom:5px;margin-top:0px;margin-bottom:5px;text-align:center;background:#5F6676;display: block; border: 1px solid #838B9C;}
hr{border-width:1px;color:#5F6676;background-color:#5F5576;}
/* = tabele = */
table{width:98%;background:#9EA7B4;color:Black;margin-bottom:5px;}
table.mainpageheader{width:100%;background:#F5F5F5;color:Black;margin-bottom:0px;}
table.menu{width:100%;background:#434A56;color:black;margin-bottom:-8px;}
th{padding:5px 5px 5px 3px;text-align:left;white-space:nowrap;color:#F5F5F5;font-weight:bold;text-decoration:none;background:#5F6676;}
th.center{text-align:center;}
th.right{text-align:right;}
td{padding:1px 3px 1px 3px;text-align:left;white-space:nowrap;vertical-align:middle;}
td.wrap{white-space:normal;}
td.nowrap{white-space:nowrap;}
td.left{text-align:left;}
td.center{text-align:center;}
td.right{text-align:right;}
td.righttop{text-align:right;vertical-align:top;}
/* = kolory wierszy w tabelach = */
.nieparzyste{color:#000000;background:#C9CDD3;}
.parzyste{color:#000000;background:#AFB6BF;}
/* = img (opcje) = */
.imgoption{margin-left:1px;margin-right:1px;}
/* = p,div = */
.buttons{font-size:11px;color:#F5F5F5;border:1px #838B9C;margin-bottom:5px;border-style:solid;background:#5F6676;padding:3px 10px 4px;margin-right:3px;}
.buttons:hover{font-size:11px;color:Black;border:1px #838B9C;margin-bottom:5px;border-style:solid;background:#9198A7;padding:3px 10px 4px;margin-right:3px;}
p{margin-top:0px;margin-bottom:0px;}
div.show,p.show{margin-top:3px;margin-bottom:5px;margin-right:2px;margin-left:2px;text-align:center; display: inline-block;}
div.showl,p.showl{margin-top:3px;margin-bottom:5px;margin-right:4px;margin-left:4px;text-align:left; display: inline-block;}
div.showr,p.showr{margin-top:3px;margin-bottom:5px;margin-right:4px;margin-left:4px;text-align:right; display: inline-block;}
/* = logowanie = */
.loginwindow_pasek{background:transparent;color:#000000;}
.loginwindow_pass{border:none;background:#F5F5F5;color:#FF2A2A;}
.loginwindow_title{color:#F5F5F5;font-size:11px;font-weight:bold;margin:10px;height:24px;vertical-align:middle;border-width:0;border-style:none;background:#5F6676;}
.loginwindow_user{border:none;background:#F5F5F5;color:#000000;margin-bottom:2px;}
.loginwindow_tlo{color:#000000;background:#AFB6BF;}
/* = tooltip = */
.tooltip,.tooltip * {display:block;}
.tooltip{width:200px;color:#000000;font:bold 11px/1.3 Tahoma,Sans-serif;text-decoration:none;text-align:center;}
.tooltip b.bottom{padding:3px 5px 3px;color:#5F6676;background:url(bt.gif) no-repeat bottom;}
.tooltip span.top{padding:4px 5px 0;background:url(bt.gif) no-repeat top;}
/* = pagination = */
.pagingimage{vertical-align:top;}
.paging{display:inline;position:relative;text-align:center;padding:5px 5px 5px 5px;text-decoration:none;background:transparent;color:black;font-weight:normal;}
a.nav_normal{text-decoration:none;color:black;font-weight:normal;background:transparent;margin:0px 2px 0px 2px;}
a.nav_normal:hover,visited{text-decoration:none;color:#5F6676;font-weight:normal;background:transparent;}
a.nav_current,a.nav_current:hover,visited{text-decoration:none;font-weight:bold;color:#5F6676;background:transparent;margin:0px 2px 0px 2px;}
a.nav_current_first,a.nav_current_last,a.nav_current_first,a.nav_current_last:hover,visited{text-decoration:none;color:#CCCCCC;font-weight:normal;background:transparent;}
/* = fonty = */
.normalfont{text-decoration:none;color:#000000;}
.white_font{text-decoration:none;color:#FFFFFF;}
.wymagane{color:#000000;background:#FFFFA1;}
	</style>
	<script language="JavaScript">

	function info(co) {
		alert(co);
	}
	
	function restore(what) {
	   if (confirm("Czy na pewno chcesz odzyskać bazę z pliku " + what +  "?\n\nPo potwierdzeniu proszę poczekać  do momentu pojawienia się odpowiedniego komunikatu\n\nProces ten może potrwać chwilę...")) {
	         window.location = "restore.php?file=" + what;
	   }
	}
	</script>

</head>

<body>

<h4>Przywróć bazę eSerwis z kopii</h4>

<p align="center">
<?php//include('header.php'); ?>
<?
include "backup.php";
?>
<?php


if ($file!="") {
	$filename = $file;
	set_time_limit(180);
	if ($compression ==1) $file=gzread(gzopen($path.$file, "r"), 8388608);
	else $file=fread(fopen($path.$file, "r"), 8388608);
	$query=explode(";#%%\n",$file);
	for ($i=0;$i < count($query)-1;$i++) {
		mysql_db_query($dbname,$query[$i],$conn) or die(mysql_error());
	}
	//echo "<b>$filename poprawnie odtworzony</b>";
	?>
	<script>
		info('Baza została poprawnie odtworzona z pliku <?php echo $filename; ?>');
	</script>
	<?php
}
?></p>
<div align="center">
  <table border="0" cellspacing="1" cellpadding="5" style="width:400px">
    <tr> 
      <th width="50" class="center">Plik</td>
      <th width="100" class="right">Rozmiar</td>
      <th width="120" class="center">Data utworzenia</td>
	  <th width="50" class="center"><center>Opcje</center></td>
    </tr>
    <?
	$dir=opendir($path); 
	$i=0;
	while ($file = readdir ($dir)) { 
	    if ($file != "." && $file != ".." && eregi("\.sql",$file)) { 

		if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=rowOver('$i',1); this.style.cursor=arrow onmouseout=rowOver('$i',0) onclick=selectRow('$i') ondblclick=deSelectRow('$i')>";
		if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=rowOver('$i',1); this.style.cursor=arrow onmouseout=rowOver('$i',0) onclick=selectRow('$i') ondblclick=deSelectRow('$i')>";
		$i++;

		  echo "<td class=center><font size=2>$file&nbsp;</font></td>
	        	<td class=right><font size=2>&nbsp;" . bcdiv(filesize($path.$file),1024,1) . " kB&nbsp;</font></td>
	        	<td class=center><font size=2>&nbsp;" . date("Y-m-d H:i",filemtime($path.$file)) . "</font></td>
	        	<td class=center><font size=2><a title=' Odzyskaj bazę z kopii ' class=black_font href=\"javascript:restore('$file')\"><img border=0 src=$linkdostrony/img/restore.gif></a></font>
	        	<font size=2><a target=_blank title=' Zapisz wybraną kopię na dysku ' class=black_font href=\"$linkdostrony"."backup/dump/$file\"><img border=0 src=$linkdostrony/img/save.gif></a></td>";
		echo "</tr>";
	    } 
	}
	closedir($dir);
?>
  </table>
</div>

<div class="showr"><input class=buttons type=button onClick=window.close() value='Zamknij'></div>
</body>
</html>
