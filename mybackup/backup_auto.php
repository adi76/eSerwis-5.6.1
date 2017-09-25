<?php
// mySQL - variables
// D:\VertrigoServ\Php\php.exe D:\VertrigoServ\www\serwis\mybackup\backup_auto.php

include "D:/VertrigoServ/www/serwis/cfg_eserwis.php";

// number of backups to keep
$backups = 350;

// hours between backups
$interval = 1;

// 1 only with ZLib support, else change value to 0
$compression = 1;

// full path to phpMyBackup
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

} else {
	$dd=substr($PHP_SELF,strlen($PHP_SELF)-11,7);
	if ($dd!='restore') {
	}
}
?>