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
</head>
<body>
<?php
// mySQL - variables

include "../cfg_eserwis.php";

// number of backups to keep
$backups = 350;

// hours between backups
$interval = 0;

// 1 only with ZLib support, else change value to 0
$compression = 1;

// full path to phpMyBackup
//$path="C:/Program Files/VertrigoServ/www/serwis/backup/";
$path="D:/VertrigoServ/www/serwis/backup/";

// DO NOT CHANGE THE LINES BELOW

$version = "";
flush();
$conn = mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
$path = $path . "dump/";
if (!is_dir($path)) mkdir($path, 0777);

function get_def($dbname, $table) {
    global $conn;
    $def = "";
    $def .= "DROP TABLE IF EXISTS $table;#%%\n";
    $def .= "CREATE TABLE $table (\n";
    $result = mysql_db_query($dbname, "SHOW FIELDS FROM $table",$conn);
    while($row = mysql_fetch_array($result)) {
        $def .= "    $row[Field] $row[Type]";
        if ($row["Default"] != "") $def .= " DEFAULT '$row[Default]'";
        if ($row["Null"] != "YES") $def .= " NOT NULL";
       	if ($row[Extra] != "") $def .= " $row[Extra]";
        	$def .= ",\n";
     }
     $def = ereg_replace(",\n$","", $def);
     $result = mysql_db_query($dbname, "SHOW KEYS FROM $table",$conn);
     while($row = mysql_fetch_array($result)) {
          $kname=$row[Key_name];
          if(($kname != "PRIMARY") && ($row[Non_unique] == 0)) $kname="UNIQUE|$kname";
          if(!isset($index[$kname])) $index[$kname] = array();
          $index[$kname][] = $row[Column_name];
     }
     while(list($x, $columns) = @each($index)) {
          $def .= ",\n";
          if($x == "PRIMARY") $def .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
          else if (substr($x,0,6) == "UNIQUE") $def .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
          else $def .= "   KEY $x (" . implode($columns, ", ") . ")";
     }

     $def .= "\n);#%%";
     return (stripslashes($def));
}

function get_content($dbname, $table) {
     global $conn;
     $content="";
     $result = mysql_db_query($dbname, "SELECT * FROM $table",$conn);
     while($row = mysql_fetch_row($result)) {
         $insert = "INSERT INTO $table VALUES (";
         for($j=0; $j<mysql_num_fields($result);$j++) {
            if(!isset($row[$j])) $insert .= "NULL,";
            else if($row[$j] != "") $insert .= "'".addslashes($row[$j])."',";
            else $insert .= "'',";
         }
         $insert = ereg_replace(",$","",$insert);
         $insert .= ");#%%\n";
         $content .= $insert;
     }
     return $content;
}

if ($compression==1) $filetype = "sql.gz";
else $filetype = "sql";

if (filemtime($path . "0.$filetype") < time() - $interval * 60 && !eregi("/restore\.",$PHP_SELF)) {
	for ($i = $backups-1; $i > 0; $i--) {
		$oldname = $i-1 . ".$filetype";
		$newname = $i . ".$filetype";
		@rename($path.$oldname,$path.$newname);
	}
	
	$cur_time=date("Y-m-d H:i");
	$newfile="# Dump created on $cur_time\r\n";
	$tables = mysql_list_tables($dbname,$conn);
	$num_tables = @mysql_num_rows($tables);
	$i = 0;
	while($i < $num_tables) { 
	   $table = mysql_tablename($tables, $i);
	
	   $newfile .= "\n# ----------------------------------------------------------\n#\n";
	   $newfile .= "# structur for table '$table'\n#\n";
	   $newfile .= get_def($dbname,$table);
	   $newfile .= "\n\n";
	   $newfile .= "#\n# data for table '$table'\n#\n";
	   $newfile .= get_content($dbname,$table);
	   $newfile .= "\n\n";
	   $i++;
	}
	
	if ($compression==1) {
		$fp = gzopen($path."0.$filetype","w9");
		gzwrite ($fp,$newfile);
		gzclose ($fp);
	} else {
		$fp = fopen ($path."0.$filetype","w");
		fwrite ($fp,$newfile);
		fclose ($fp);
	}
	
	
	
	
	echo "<body bgcolor=lime><div align=center><h3>Backup bazy został wykonany</h3><br><input class=buttons type=button onClick=window.close() value='OK'></div></body>";
} else {
	$dd=substr($PHP_SELF,strlen($PHP_SELF)-11,7);
	if ($dd!='restore') {
	echo "<body bgcolor=red><div align=center><h2>Backup bazy pominięto</h2><br><input class=buttons type=button onClick=window.close() value='OK'></div></body>";
	}
}
?>
</body>
</html>