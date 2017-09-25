<?php
include_once('header.php'); 
include_once('cfg_helpdesk.php');
//echo "<b>Trwa przekierowanie do modułu NAPRAWY...</b>";
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

$width = "<script>document.write((screen.width-300)/2); </script>"; 
$height = "<script>document.write((screen.height-20)/2); </script>"; 

ob_flush();	
flush();

$result99 = mysql_query("SELECT naprawa_id, naprawa_status FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$_REQUEST[hd_zgl_nr]' LIMIT 1");
$dane99 = mysql_fetch_array($result99);
$mstatus = $dane99['naprawa_status'];
$mid = $dane99['naprawa_id'];
?><script>
</script><?php 
if ($mstatus=='-1') { ?><script>self.location.href='main.php?action=npus&id=<?php echo $mid; ?>';</script><?php } 
if ($mstatus=='0') { ?><script>self.location.href='main.php?action=nwwz&id=<?php echo $mid; ?>';</script><?php } 
if ($mstatus=='1') { ?><script>self.location.href='main.php?action=npswsz&id=<?php echo $mid; ?>';</script><?php }
if ($mstatus=='2') { ?><script>self.location.href='main.php?action=nsnrl&id=<?php echo $mid; ?>';</script><?php }
if ($mstatus=='3') { ?><script>self.location.href='p_naprawy_zakonczone.php?id=<?php echo $mid; ?>';</script><?php }

if ($mstatus=='5') { ?><script>self.location.href='p_naprawy_historia_cala.php?id=<?php echo $mid; ?>';</script><?php }
if ($mstatus=='7') { ?><script>self.location.href='main.php?action=nsw&id=<?php echo $mid; ?>';</script><?php } 
if ($mstatus=='8') { ?><script>self.location.href='p_naprawy_historia_wycofane.php?id=<?php echo $mid; ?>';</script><?php } 
?>