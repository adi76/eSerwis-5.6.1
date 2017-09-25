<?php 
require_once 'cfg_eserwis.php';
session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Wizytówka</title>
<style type="text/css">

.style8 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; }
.style11 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 30px;
}
.style20 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style21 {font-size: 12px}
.style23 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-weight: bold; }
.style24 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; }
.style25 {font-family: Arial, Helvetica, sans-serif}
td.lefttop{text-align:left;vertical-align:top;font-size:30px;}
hr.dotted {
		border-style: dashed;
}
</style>

<style type="text/css" media="print">
.hideme { display: none; }
</style>

</head>
<body>
<?php
$sql="SELECT * FROM $dbname.serwis_fz WHERE fz_id=$fzid LIMIT 1";
$result = mysql_query($sql, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);

$temp_id  			= $newArray['fz_id'];
$temp_nazwa			= $newArray['fz_nazwa'];
$temp_adres			= $newArray['fz_adres'];
$temp_telefon		= $newArray['fz_telefon'];
$temp_fax			= $newArray['fz_fax'];
$temp_email			= $newArray['fz_email'];
$temp_www			= $newArray['fz_www'];
$temp_belongs_to	= $newArray['belongs_to'];

echo "<br /><div class=hideme>";
echo "<a style=vertical-align:bottom; onClick=\"javascript:window.print();\"><img src=img/drukuj_protokol.jpg border=0></a>";
echo "</div>";

echo "<table width=100% border=0 cellpadding=5>";
echo "<tr height=100%>";
echo "<td class=lefttop>";
echo "Adresat:<hr />$temp_nazwa<br />";
echo "$temp_adres<br />";

$sql = "SELECT filia_adres FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1";
$result = mysql_query($sql, $conn) or die($k_b);
$daneadresowe = mysql_fetch_array($result);
$adres = $daneadresowe['filia_adres'];

echo "<br />&nbsp;";
echo "<br />&nbsp;";
echo "<br />&nbsp;";
echo "<hr class=dotted>";
echo "<br />&nbsp;";
echo "<br />&nbsp;";
echo "<br />&nbsp;";
br();

echo "Nadawca:<hr />";
echo "Postdata S.A.<br />";
echo "$adres<br />";
echo "</td>";
echo "</tr>";
endtable();

echo "<br /><div class=hideme>";
echo "<a style=vertical-align:bottom; onClick=\"javascript:window.print();\"><img src=img/drukuj_protokol.jpg border=0></a>";
echo "</div>";
?>
</body>
</html>
